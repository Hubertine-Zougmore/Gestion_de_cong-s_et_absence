<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Demande;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NouvelleDemande;

class ResponsableController extends Controller
{
    
    public function dashboard()
    {
        $user = Auth::user();
        
        if ($user->role !== 'responsable_hierarchique') {
            abort(403, 'Accès réservé aux responsables hiérarchiques');
        }

        // Marquer les notifications de nouvelles demandes comme lues
        $user->unreadNotifications()
            ->where('type', 'App\Notifications\NouvelleDemande')
            ->update(['read_at' => now()]);
        
        // Récupérer TOUTES les demandes de la direction du responsable
        $toutesLesDemandesDeDepartement = Demande::with(['user'])
            ->whereHas('user', function ($query) use ($user) {
                $query->where('direction_id', $user->direction_id);
            })
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($demande) {
                $demande->date_debut_formatee = $demande->date_debut->format('d/m/Y');
                $demande->date_fin_formatee = $demande->date_fin->format('d/m/Y');
                $demande->nombre_jours = $demande->date_debut->diffInDays($demande->date_fin) + 1;
                return $demande;
            });

        // Récupérer les demandes que le responsable peut traiter 
        $demandesTraitable = $this->getDemandesTraitableParResponsable();
        
        // Pour le traitement de jouinssance de congés
$demandesTraitable = Demande::with(['user'])
    ->where('statut', 'en_attente')
    ->whereHas('user', function ($query) use ($user) {
        $query->where('direction_id', $user->direction_id);
    })
    ->get()
    ->filter(function ($demande) {
        return $demande->nombre_jours < 3; // Moins de 3 jours seulement
    });
        // Récupérer les demandes personnelles du responsable
        $mesDemandesResponsable = Demande::where('user_id', Auth::id())
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($demande) {
                $demande->date_debut_formatee = $demande->date_debut->format('d/m/Y');
                $demande->date_fin_formatee = $demande->date_fin->format('d/m/Y');
                $demande->nombre_jours = $demande->date_debut->diffInDays($demande->date_fin) + 1;
                return $demande;
            });
        
        // Calculer les stats
        $stats = [
            'total_demandes' => $toutesLesDemandesDeDepartement->count(),
            'total_a_traiter' => $demandesTraitable->count(),
            'en_attente' => $toutesLesDemandesDeDepartement->where('statut', 'en_attente')->count(),
            'en_attente_sans_moi' => $toutesLesDemandesDeDepartement->where('statut', 'en_attente')->where('user_id', '!=', Auth::id())->count(),
            'approuvees' => $toutesLesDemandesDeDepartement->where('statut', 'approuve')->count(),
            'rejetees' => $toutesLesDemandesDeDepartement->where('statut', 'rejete')->count(),
            'urgentes' => $demandesTraitable->filter(function($demande) {
                return $demande->date_debut <= now()->addHours(48);
            })->count(),
            'urgentes_sans_moi' => $demandesTraitable->filter(function($demande) {
                return $demande->date_debut <= now()->addHours(48) && $demande->user_id !== Auth::id();
            })->count(),
            'de_ma_direction' => $demandesTraitable->count(), // Toutes les demandes traitables sont de sa direction
            'autres_directions' => 0, // Le responsable ne traite que sa direction
            'total_demandes_departement' => $toutesLesDemandesDeDepartement->count(),
            'nouvelles_demandes' => $user->unreadNotifications()
                ->where('type', 'App\Notifications\NouvelleDemande')
                ->count(),
        ];

        // Variables pour la vue
        $departementResponsable = $user->direction->nom ?? 'Direction non définie';

        return view('responsable.dashboard', compact(
            'stats', 
            'demandesTraitable',
            'mesDemandesResponsable',
            'toutesLesDemandesDeDepartement',
            'departementResponsable',
            // Alias pour compatibilité avec la vue
            //'demandesATraiter',
            'mesDemandesResponsable',
            'toutesLesDemandesDeDepartement'
        ))
        ->with([
            'demandesATraiter' => $demandesTraitable,
            'mesDemandes' => $mesDemandesResponsable,
            'toutesLesDemandes' => $toutesLesDemandesDeDepartement,
        ]);
    }

    /**
     * Récupérer les demandes que le responsable hiérarchique peut traiter selon la logique métier
     * DIFFÉRENCE AVEC DRH : Ne peut traiter QUE les demandes de MOINS de 3 jours de sa direction
     */
    private function getDemandesTraitableParResponsable()
    {
        $user = Auth::user();
        
        return Demande::with(['user', 'user.direction'])
            ->where('statut', 'en_attente')
            ->whereHas('user', function ($query) use ($user) {
                // Uniquement les demandes de sa direction
                $query->where('direction_id', $user->direction_id);
            })
            ->get()
            ->filter(function ($demande) use ($user) {
                // Utiliser le nombre de jours existant
                $nombreJours = $demande->nombre_jours;
                
                // Vérifier si c'est de la même direction (toujours vrai grâce au whereHas)
                $estDeMaDirection = $demande->user->direction_id === $user->direction_id;
                $estMaDemande = $demande->user_id === $user->id;
                
                // RÈGLE SPÉCIFIQUE AU RESPONSABLE HIÉRARCHIQUE :
                // Peut traiter UNIQUEMENT les demandes de MOINS de 3 jours de sa direction
                if ($nombreJours < 3) {
                    return $estDeMaDirection || $estMaDemande;
                } 
                else {
                    // Les demandes de 3 jours et plus ne peuvent pas être traitées par le responsable
                    return false;
                }
            })
            ->each(function ($demande) use ($user) {
                // Ajouter les propriétés pour la vue
                $demande->est_de_ma_direction = $demande->user->direction_id === $user->direction_id;
                $demande->peut_traiter = true; // Toutes les demandes filtrées sont traitables
                $demande->est_urgent = $demande->date_debut <= now()->addHours(48);
                $demande->delai_restant_jours = now()->diffInHours($demande->date_debut);
                $demande->date_debut_formatee = $demande->date_debut->format('d/m/Y');
                $demande->date_fin_formatee = $demande->date_fin->format('d/m/Y');
                $demande->nombre_jours = $demande->date_debut->diffInDays($demande->date_fin) + 1;
            });
    }

    /**
     * Vérifier si le responsable hiérarchique peut traiter une demande spécifique
     */
    private function peutTraiterDemande(Demande $demande)
    {
        $user = Auth::user();
        
        // 1. La demande doit être en statut 'en_attente'
        if ($demande->statut !== 'en_attente') {
            return false;
        }
        
        // 2. Utiliser le nombre de jours EXISTANT (déjà calculé)
        $nombreJours = $demande->nombre_jours; 
        
        // 3. Vérifier si c'est de la même direction
        $estDeMaDirection = $demande->user->direction_id === $user->direction_id;
        $estMaDemande = $demande->user_id === $user->id;
        
        // 4. RÈGLE MÉTIER POUR RESPONSABLE HIÉRARCHIQUE :
        // Peut traiter UNIQUEMENT les demandes de MOINS de 3 jours de sa direction
        if ($nombreJours < 3) {
            // Moins de 3 jours : même direction OU ma demande
            return $estDeMaDirection || $estMaDemande;
        } 
        else {
            // 3 jours et plus : ne peut pas traiter (différence avec DRH)
            return false;
        }
    }

    public function showDemande(Demande $demande)
    {
        $demande->load(['user.direction']);
        $user = Auth::user();
        
        $nombreJours = $demande->nombre_jours;
        
        $estDeMaDirection = $demande->user->direction_id === $user->direction_id;
        $estMaDemande = $demande->user_id === $user->id;
        
        // Logique de traitement pour responsable hiérarchique
        $peutTraiter = false;
        $messageErreur = '';
        
        if ($nombreJours < 3) {
            $peutTraiter = $estDeMaDirection || $estMaDemande;
        } 
        else {
            $peutTraiter = false;
            if ($nombreJours == 3) {
                $messageErreur = 'Cette demande (3 jours) doit être traitée par le DRH.';
            } else {
                $messageErreur = 'Cette demande (>3 jours) doit être traitée par le président.';
            }
        }
        
        if (!$peutTraiter && $demande->statut === 'en_attente') {
            return redirect()->route('responsable.dashboard')->with('error', 
                $messageErreur ?: 'Vous ne pouvez pas traiter cette demande.');
        }
        
        return view('drh.demande-show', compact('demande', 'peutTraiter'));
    }

    public function traiterDemande(Request $request, Demande $demande)
    {
        // Debug pour voir ce qui arrive
        \Log::info('Traitement demande par responsable', [
            'demande_id' => $demande->id,
            'action' => $request->input('action'),
            'statut_actuel' => $demande->statut,
            'request_data' => $request->all()
        ]);

        $request->validate([
            'action' => 'required|in:accepter,rejeter',
            'commentaire' => 'nullable|string|max:500'
        ]);

        // Vérifier si le responsable peut traiter cette demande
        if (!$this->peutTraiterDemande($demande)) {
            return back()->with('error', 'Vous n\'êtes pas autorisé à traiter cette demande.');
        }

        // Vérifier si la demande est toujours traitable (moins de 72h)
        if ($demande->created_at->lt(Carbon::now()->subHours(72))) {
            return back()->with('error', 'Cette demande ne peut plus être traitée (délai de 72h dépassé).');
        }

        if ($demande->statut !== 'en_attente') {
            return back()->with('error', 'Cette demande a déjà été traitée.');
        }

        try {
            // Nouveau statut
            $nouveauStatut = $request->action === 'accepter' ? 'approuve' : 'rejete';
            
            // Mise à jour avec vérification
            $updated = $demande->update([
                'statut' => $nouveauStatut,
                'commentaire_responsable' => $request->commentaire, // Différent du DRH
                'traite_par' => auth()->id(),
                'traite_le' => Carbon::now()
            ]);

            \Log::info('Résultat mise à jour par responsable', [
                'updated' => $updated,
                'nouveau_statut' => $nouveauStatut,
                'demande_apres' => $demande->fresh()->statut
            ]);

            if (!$updated) {
                return back()->with('error', 'Erreur lors de la mise à jour de la demande.');
            }

            $message = $request->action === 'accepter' 
                ? 'Demande approuvée avec succès.' 
                : 'Demande rejetée.';

            return redirect()->route('responsable.dashboard')->with('success', $message);
        } catch (\Exception $e) {
            \Log::error('Erreur traitement demande par responsable', ['error' => $e->getMessage()]);
            return back()->with('error', 'Erreur lors du traitement de la demande: ' . $e->getMessage());
        }
    }

    public function createDemande()
    {
        return view('responsable.create-demande');
    }

    public function mesDemandes()
    {
        if (Auth::user()->role !== 'responsable_hierarchique') {
            abort(403, 'Accès réservé aux responsables hiérarchiques.');
        }

        // Récupérer seulement les demandes du responsable connecté
        $demandes = Demande::where('user_id', Auth::id())
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        // Formater les dates
        foreach ($demandes as $demande) {
            $demande->date_debut_formatee = $demande->date_debut->format('d/m/Y');
            $demande->date_fin_formatee = $demande->date_fin->format('d/m/Y');
            $demande->nombre_jours = $demande->date_debut->diffInDays($demande->date_fin) + 1;
        }

        return view('responsable.mes-demandes', compact('demandes'));
    }

    public function toutesLesDemandes()
    {
        if (Auth::user()->role !== 'responsable_hierarchique') {
            abort(403, 'Accès réservé aux responsables hiérarchiques.');
        }

        // Récupérer toutes les demandes de la direction du responsable
        $demandes = Demande::with('user')
            ->whereHas('user', function ($query) {
                $query->where('direction_id', Auth::user()->direction_id);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // Formater les dates
        foreach ($demandes as $demande) {
            $demande->date_debut_formatee = $demande->date_debut->format('d/m/Y');
            $demande->date_fin_formatee = $demande->date_fin->format('d/m/Y');
            $demande->nombre_jours = $demande->date_debut->diffInDays($demande->date_fin) + 1;
        }

        return view('responsable.toutes-demandes', compact('demandes'));
    }

    public function approuverDemande(Demande $demande)
    {
        // Vérifier que la demande est bien en attente
        if ($demande->statut !== 'en_attente') {
            return redirect()->back()
                ->with('error', 'Cette demande a déjà été traitée.');
        }
        
        // VÉRIFICATION DES RÈGLES MÉTIER POUR RESPONSABLE
        if (!$this->peutTraiterDemande($demande)) {
            $nombreJours = $demande->nombre_jours;
            $estDeMaDirection = $demande->user->direction_id === Auth::user()->direction_id;
            
            if ($nombreJours >= 3) {
                $message = 'Vous ne pouvez approuver que les demandes de moins de 3 jours de votre direction.';
            } elseif (!$estDeMaDirection && $demande->user_id !== Auth::id()) {
                $message = 'Vous ne pouvez approuver les demandes que pour votre direction.';
            } else {
                $message = 'Vous n\'êtes pas autorisé à traiter cette demande.';
            }
            
            return redirect()->back()->with('error', $message);
        }
        
        // Mettre à jour le statut
        $demande->update([
            'statut' => 'approuve',
            'traite_par' => Auth::id(),
            'traite_le' => now(),
        ]);
        
        return redirect()->back()
            ->with('success', 'La demande a été approuvée avec succès.');
    }

    public function rejeterDemande(Demande $demande)
    {
        // Vérifier que la demande est bien en attente
        if ($demande->statut !== 'en_attente') {
            return redirect()->back()
                ->with('error', 'Cette demande a déjà été traitée.');
        }
        
        // VÉRIFICATION DES RÈGLES MÉTIER POUR RESPONSABLE
        if (!$this->peutTraiterDemande($demande)) {
            $nombreJours = $demande->nombre_jours;
            $estDeMaDirection = $demande->user->direction_id === Auth::user()->direction_id;
            
            if ($nombreJours >= 3) {
                $message = 'Vous ne pouvez rejeter que les demandes de moins de 3 jours de votre direction.';
            } elseif (!$estDeMaDirection && $demande->user_id !== Auth::id()) {
                $message = 'Vous ne pouvez rejeter les demandes que pour votre direction.';
            } else {
                $message = 'Vous n\'êtes pas autorisé à traiter cette demande.';
            }
            
            return redirect()->back()->with('error', $message);
        }
        
        // Mettre à jour le statut
        $demande->update([
            'statut' => 'rejete',
            'traite_par' => Auth::id(),
            'traite_le' => now(),
        ]);
        
        return redirect()->back()
            ->with('success', 'La demande a été rejetée.');
    }

    /**
     * Voir les quotas d'un employé de la direction
     */
    public function voirQuotasEmploye(User $user)
    {
        // Vérifier que l'employé est de la même direction
        if ($user->direction_id !== Auth::user()->direction_id) {
            abort(403, 'Vous ne pouvez consulter que les quotas de votre direction.');
        }
        
        $congeService = new \App\Services\CongeService();
        $resumeConges = $congeService->getResumeCongés($user);
        
        return view('responsable.quotas-employe', compact('user', 'resumeConges'));
    }

    /**
     * Statistiques des congés de la direction
     */
    public function statistiques()
    {
        $congeService = new \App\Services\CongeService();
        $anneeActuelle = date('Y');
        $user = Auth::user();
        
        // Statistiques pour la direction du responsable uniquement
        $totalDemandes = Demande::pourAnnee($anneeActuelle)
            ->whereHas('user', function ($query) use ($user) {
                $query->where('direction_id', $user->direction_id);
            })
            ->count();
            
        $demandesEnAttente = Demande::pourAnnee($anneeActuelle)
            ->enAttente()
            ->whereHas('user', function ($query) use ($user) {
                $query->where('direction_id', $user->direction_id);
            })
            ->count();
            
        $demandesApprouvees = Demande::pourAnnee($anneeActuelle)
            ->approuvees()
            ->whereHas('user', function ($query) use ($user) {
                $query->where('direction_id', $user->direction_id);
            })
            ->count();
            
        $demandesRejetees = Demande::pourAnnee($anneeActuelle)
            ->rejetees()
            ->whereHas('user', function ($query) use ($user) {
                $query->where('direction_id', $user->direction_id);
            })
            ->count();
        
        // Statistiques par type de congé pour la direction
        $statsParType = [];
        foreach (config('conges.types') as $type => $config) {
            $statsParType[$type] = [
                'nom' => $config['nom'],
                'total' => Demande::pourAnnee($anneeActuelle)
                    ->parType($type)
                    ->whereHas('user', function ($query) use ($user) {
                        $query->where('direction_id', $user->direction_id);
                    })
                    ->count(),
                'approuvees' => Demande::pourAnnee($anneeActuelle)
                    ->parType($type)
                    ->approuvees()
                    ->whereHas('user', function ($query) use ($user) {
                        $query->where('direction_id', $user->direction_id);
                    })
                    ->count(),
                'jours_consommes' => Demande::pourAnnee($anneeActuelle)
                    ->parType($type)
                    ->approuvees()
                    ->whereHas('user', function ($query) use ($user) {
                        $query->where('direction_id', $user->direction_id);
                    })
                    ->sum('nombre_jours_demandes'),
            ];
        }
        
        return view('responsable.statistiques', compact(
            'totalDemandes',
            'demandesEnAttente', 
            'demandesApprouvees',
            'demandesRejetees',
            'statsParType',
            'anneeActuelle'
        ));
    }

    /**
     * Méthode pour envoyer une notification au responsable hiérarchique
     * Appelée depuis le DemandeController ou lors de la création de demande
     */
    public static function notifierNouvelleDemande(Demande $demande)
    {
        // Trouver le responsable hiérarchique de la direction de l'employé
        $responsable = User::where('role', 'responsable_hierarchique')
            ->where('direction_id', $demande->user->direction_id)
            ->first();

        if ($responsable && $demande->nombre_jours < 3) {
            // Envoyer notification seulement si le responsable peut traiter cette demande (< 3 jours)
            $responsable->notify(new NouvelleDemande($demande));
            
            \Log::info('Notification envoyée au responsable', [
                'responsable_id' => $responsable->id,
                'demande_id' => $demande->id,
                'nombre_jours' => $demande->nombre_jours
            ]);
        }
    }

    /**
     * Obtenir les notifications non lues du responsable
     */
    public function getNotificationsNonLues()
    {
        $user = Auth::user();
        
        $notifications = $user->unreadNotifications()
            ->where('type', 'App\Notifications\NouvelleDemande')
            ->limit(10)
            ->get()
            ->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'type' => 'nouvelle_demande',
                    'data' => $notification->data,
                    'created_at' => $notification->created_at->diffForHumans(),
                    'url' => route('responsable.demandes.show', $notification->data['demande_id'] ?? null)
                ];
            });

        return response()->json($notifications);
    }

    /**
     * Marquer une notification comme lue
     */
    public function marquerNotificationCommeLue(Request $request, $notificationId)
    {
        $user = Auth::user();
        
        $notification = $user->notifications()->findOrFail($notificationId);
        $notification->markAsRead();

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Notification marquée comme lue.');
    }

    /**
     * Marquer toutes les notifications comme lues
     */
    public function marquerToutesNotificationsCommeLues(Request $request)
    {
        $user = Auth::user();
        
        $user->unreadNotifications()
            ->where('type', 'App\Notifications\NouvelleDemande')
            ->update(['read_at' => now()]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Toutes les notifications ont été marquées comme lues.');
    }

    /**
     * Obtenir le nombre de notifications non lues
     */
    public function getNombreNotificationsNonLues()
    {
        $user = Auth::user();
        
        $count = $user->unreadNotifications()
            ->where('type', 'App\Notifications\NouvelleDemande')
            ->count();

        return response()->json(['count' => $count]);
    }
}