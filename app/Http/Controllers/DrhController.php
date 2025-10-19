<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Demande;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DrhController extends Controller
{
    
    public function dashboard()
    {
        $user = Auth::user();
        
        if ($user->role !== 'drh') {
            abort(403, 'Accès réservé au DRH');
        }
        

        // Récupérer TOUTES les demandes avec relations
        $toutesLesDemandes = Demande::with(['user'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($demande) {
                $demande->date_debut_formatee = $demande->date_debut->format('d/m/Y');
                $demande->date_fin_formatee = $demande->date_fin->format('d/m/Y');
                $demande->nombre_jours = $demande->date_debut->diffInDays($demande->date_fin) + 1;
                return $demande;
            });

        // Récupérer les demandes que le DRH peut traiter 
        $demandesTraitable = $this->getDemandesTraitableParDRH();
        
        // Récupérer les demandes personnelles du DRH
        $mesDemandesDrh = Demande::where('user_id', Auth::id())
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
            'total_demandes' => $toutesLesDemandes->count(),
            'total_a_traiter' => $demandesTraitable->count(),
            'en_attente' => $toutesLesDemandes->where('statut', 'en_attente')->count(),
            'en_attente_sans_moi' => $toutesLesDemandes->where('statut', 'en_attente')->where('user_id', '!=', Auth::id())->count(),
            'approuvees' => $toutesLesDemandes->where('statut', 'approuve')->count(),
            'rejetees' => $toutesLesDemandes->where('statut', 'rejete')->count(),
            'urgentes' => $demandesTraitable->filter(function($demande) {
                return $demande->date_debut <= now()->addHours(48);
            })->count(),
            'urgentes_sans_moi' => $demandesTraitable->filter(function($demande) {
                return $demande->date_debut <= now()->addHours(48) && $demande->user_id !== Auth::id();
            })->count(),
            'de_ma_direction' => $demandesTraitable->filter(function($demande) use ($user) {
                return $demande->user->departement === $user->departement;
            })->count(),
            'autres_directions' => $demandesTraitable->filter(function($demande) use ($user) {
                return $demande->user->departement !== $user->departement;
            })->count(),
        ];

        // Pagination pour les demandes à traiter
        $page = request()->get('page', 1);
        $perPage = 10;
        
        $demandesPaginated = new \Illuminate\Pagination\LengthAwarePaginator(
            $demandesTraitable->forPage($page, $perPage),
            $demandesTraitable->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('drh.dashboard', compact(
            'stats', 
            'demandesPaginated', 
            'demandesTraitable',
            'mesDemandesDrh',
            'toutesLesDemandes'
        ));
    }

    /**
     * Récupérer les demandes que le DRH peut traiter selon la logique métier
     */
   private function getDemandesTraitableParDRH()
{
    $user = Auth::user();
    
    return Demande::with(['user', 'user.direction']) // ← Ajouter 'user.direction'
        ->where('statut', 'en_attente')
        ->get()
        ->filter(function ($demande) use ($user) {
            // Utiliser le nombre de jours existant
            $nombreJours = $demande->nombre_jours;
            $dureeHeures = $nombreJours * 24;
            
            // Vérifier si c'est de la même direction
            $estDeMaDirection = $demande->user->direction_id === $user->direction_id;
            $estMaDemande = $demande->user_id === $user->id;
            
            // SUPPRIMER l'ancienne logique et garder seulement la nouvelle :
            if ($dureeHeures < 72) {
                return $estDeMaDirection || $estMaDemande;
            } 
            elseif ($dureeHeures == 72) {
                return true;
            }
            else {
                return false;
            }
            
            // ⚠️ SUPPRIMER tout le reste de l'ancienne logique ici ⚠️
        })
        ->each(function ($demande) use ($user) {
            // Ajouter les propriétés pour la vue
            $demande->est_de_ma_direction = $demande->user->direction_id === $user->direction_id;
            $demande->peut_traiter = true; // Toutes les demandes filtrées sont traitables
            $demande->est_urgent = $demande->date_debut <= now()->addHours(48);
            $demande->delai_restant = now()->diffInHours($demande->date_debut);
        });

                // Ajouter des propriétés calculées pour la vue
                $demande->duree_heures = $dureeHeures;
                $demande->est_de_ma_direction = $estDeMaDirection;
                $demande->peut_traiter = $peutTraiter;
                $demande->est_urgent = $demande->date_debut <= now()->addHours(48);
                $demande->delai_restant = now()->diffInHours($demande->date_debut);
                $demande->date_debut_formatee = $demande->date_debut->format('d/m/Y');
                $demande->date_fin_formatee = $demande->date_fin->format('d/m/Y');
                $demande->nombre_jours = $demande->date_debut->diffInDays($demande->date_fin) + 1;
                
                return $peutTraiter;   
                // Dans DrhController.php
$demandesTraitable = Demande::with(['user'])
    ->where('statut', 'en_attente')
    ->get()
    ->filter(function ($demande) use ($user) {
        if ($demande->nombre_jours == 3) {
            return true; // 3 jours exactement
        }
        if ($demande->nombre_jours < 3 && $demande->user->direction_id === $user->direction_id) {
            return true; // Moins de 3 jours de sa direction
        }
        return false;
    });
    }

    /**
     * Vérifier si le DRH peut traiter une demande spécifique
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
    
    
    // 5. Application des règles métier CORRIGÉES :
    // Règles métier en JOURS
    if ($nombreJours < 3) {
        // Moins de 3 jours : même direction OU ma demande
        return $estDeMaDirection || $estMaDemande;
    } 
    elseif ($nombreJours == 3) {
        // Exactement 3 jours : toutes les demandes
        return true;
    }
    else {
        // Plus de 3 jours : aucune demande
        return false;
    }
}

    /*public function planning()
    {
        try {
            $demandes = Demande::where('statut', 'approuve')
                ->with('user')
                ->orderBy('date_debut')
                ->get()
                ->map(function ($demande) {
                    $demande->date_debut_formatee = $demande->date_debut ? 
                        Carbon::parse($demande->date_debut)->format('d/m/Y') : 'N/A';
                    $demande->date_fin_formatee = $demande->date_fin ? 
                        Carbon::parse($demande->date_fin)->format('d/m/Y') : 'N/A';
                    return $demande;
                });

            return view('drh.planning', compact('demandes'));
        } catch (\Exception $e) {
            $demandes = collect();
            return view('drh.planning', compact('demandes'))
                ->with('error', 'Erreur lors du chargement du planning.');
        }
    }*/
public function showDemande(Demande $demande)
{
    
    $demande->load(['user.direction']);
    $user = Auth::user();
    
    $nombreJours = $demande->nombre_jours;
    $dureeHeures = $nombreJours * 24;
    
    $estDeMaDirection = $demande->user->direction_id === $user->direction_id;
    $estMaDemande = $demande->user_id === $user->id;
    
    // Logique de traitement
    $peutTraiter = false;
    $messageErreur = '';
    
    if ($dureeHeures == 72) {
        $peutTraiter = true; // 3 jours: toujours traitable par DRH
    } 
    
    elseif ($dureeHeures < 72) {
        $peutTraiter = $estDeMaDirection || $estMaDemande; // 1-2 jours: sa direction ou sa demande
    } 
    elseif ($dureeHeures > 72) {
        $peutTraiter = false;
        $messageErreur = 'Cette demande (>72h) doit être traitée par le président.';
    }

    if ($user->role == 'secretaire_general' || $user->role == 'president')  {
        # code...
        $peutTraiter = true;
    }


    if (!$peutTraiter && $demande->statut === 'en_attente') {
        return redirect()->route('drh.dashboard')->with('error', 
            $messageErreur ?: 'Vous ne pouvez pas traiter cette demande.');
    }

   
    
    return view('drh.demande-show', compact('demande', 'peutTraiter'));
}

    public function traiterDemande(Request $request, Demande $demande)
    {
        
        // Debug pour voir ce qui arrive
        \Log::info('Traitement demande', [
            'demande_id' => $demande->id,
            'action' => $request->input('action'),
            'statut_actuel' => $demande->statut,
            'request_data' => $request->all()
        ]);

        $request->validate([
            'action' => 'required|in:accepter,rejeter',
            'commentaire' => 'nullable|string|max:500'
        ]);

        // Vérifier si le DRH peut traiter cette demande
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
                'commentaire_drh' => $request->commentaire,
                'traite_par' => auth()->id(),
                'traite_le' => Carbon::now()
            ]);
            // Après la mise à jour du statut
      if ($updated && $nouveauStatut === 'approuve') {
        $demandeJouissance = $demande->creerDemandeJouissance();
      if ($demandeJouissance) {
        \Log::info('Demande de jouissance créée', ['id' => $demandeJouissance->id]);
    }
}

            \Log::info('Résultat mise à jour', [
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

            return redirect()->route('drh.dashboard')->with('success', $message);
        } catch (\Exception $e) {
            \Log::error('Erreur traitement demande', ['error' => $e->getMessage()]);
            return back()->with('error', 'Erreur lors du traitement de la demande: ' . $e->getMessage());
        }
    }

    public function createDemande()
    {
        return view('drh.create-demande');
    }

    public function mesDemandes()
    {
        if (Auth::user()->role !== 'drh') {
            abort(403, 'Accès réservé aux DRH.');
        }

        // Récupérer seulement les demandes du DRH connecté
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

        return view('drh.mes-demandes', compact('demandes'));
    }

    public function toutesLesDemandes()
    {
        if (Auth::user()->role !== 'drh') {
            abort(403, 'Accès réservé aux DRH.');
        }

        // Récupérer toutes les demandes
        $demandes = Demande::with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        // Formater les dates
        foreach ($demandes as $demande) {
            $demande->date_debut_formatee = $demande->date_debut->format('d/m/Y');
            $demande->date_fin_formatee = $demande->date_fin->format('d/m/Y');
            $demande->nombre_jours = $demande->date_debut->diffInDays($demande->date_fin) + 1;
        }

        return view('drh.toutes-demandes', compact('demandes'));
    }

    public function approuverDemande(Demande $demande)
    {
        
        // Vérifier que la demande est bien en attente
        if ($demande->statut !== 'en_attente') {
            return redirect()->back()
                ->with('error', 'Cette demande a déjà été traitée.');
        }
        
         // VÉRIFICATION DES RÈGLES MÉTIER (autorise les propres demandes de 3 jours)
    if (!$this->peutTraiterDemande($demande)) {
        $nombreJours = $demande->nombre_jours;
        $estDeMaDirection = $demande->user->direction_id === Auth::user()->direction_id;
  
          if ($nombreJours < 3 && !$estDeMaDirection && $demande->user_id !== Auth::id()) {
            $message = 'Vous ne pouvez approuver les demandes de moins de 3 jours que pour votre direction.';
        } elseif ($nombreJours > 3) {
            $message = 'Les demandes de plus de 3 jours doivent être approuvées par le président.';
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
        
        // VÉRIFICATION DES RÈGLES MÉTIER
        if (!$this->peutTraiterDemande($demande)) {
        $nombreJours = $demande->nombre_jours;
        $estDeMaDirection = $demande->user->direction_id === Auth::user()->direction_id;
            if ($nombreJours < 3 && !$estDeMaDirection && $demande->user_id !== Auth::id()) {
            $message = 'Vous ne pouvez approuver les demandes de moins de 3 jours que pour votre direction.';
        } elseif ($nombreJours > 3) {
            $message = 'Les demandes de plus de 3 jours doivent être approuvées par le président.';
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
    
    return redirect()->back()->with('success', 'La demande a été approuvée avec succès.');
}

    /**
     * Voir les quotas d'un employé
     */
    public function voirQuotasEmploye(User $user)
    {
        $congeService = new \App\Services\CongeService();
        $resumeConges = $congeService->getResumeCongés($user);
        
        return view('drh.quotas-employe', compact('user', 'resumeConges'));
    }

    /**
     * Statistiques des congés
     */
    public function statistiques()
    {
        $congeService = new \App\Services\CongeService();
        $anneeActuelle = date('Y');
        
        // Statistiques générales
        $totalDemandes = Demande::pourAnnee($anneeActuelle)->count();
        $demandesEnAttente = Demande::pourAnnee($anneeActuelle)->enAttente()->count();
        $demandesApprouvees = Demande::pourAnnee($anneeActuelle)->approuvees()->count();
        $demandesRejetees = Demande::pourAnnee($anneeActuelle)->rejetees()->count();
        
        // Statistiques par type de congé
        $statsParType = [];
        foreach (config('conges.types') as $type => $config) {
            $statsParType[$type] = [
                'nom' => $config['nom'],
                'total' => Demande::pourAnnee($anneeActuelle)->parType($type)->count(),
                'approuvees' => Demande::pourAnnee($anneeActuelle)->parType($type)->approuvees()->count(),
                'jours_consommes' => Demande::pourAnnee($anneeActuelle)
                                          ->parType($type)
                                          ->approuvees()
                                          ->sum('nombre_jours_demandes'),
            ];
        }
        
        return view('drh.statistiques', compact(
            'totalDemandes',
            'demandesEnAttente', 
            'demandesApprouvees',
            'demandesRejetees',
            'statsParType',
            'anneeActuelle'
        ));
    }
}