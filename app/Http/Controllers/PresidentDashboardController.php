<?php

namespace App\Http\Controllers;

use App\Models\Demande;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PresidentDashboardController extends Controller
{
     public function dashboard()
    {
        $user = Auth::user();
        
        // Vérifier que seul le président peut accéder
        if ($user->role !== 'president') {
            abort(403, 'Accès réservé au président.');
        }
        
        // 1. Demandes que le président peut traiter (≥ 4 jours, en attente, pas les siennes)
        $demandesTraitable = $this->getDemandesTraitableParPresident($user);

        // 2. Demandes personnelles du président
        $mesDemandesPresident = Demande::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // 3. Toutes les demandes (pour vue d'ensemble)
        $toutesLesDemandes = Demande::with('user')
            ->orderBy('created_at', 'desc')
            ->take(50) // Limiter pour les performances
            ->get();

        // 4. Statistiques globales
        $stats = $this->calculerStatistiques($user);

        return view('president.dashboard', compact(
            'demandesTraitable',
            'mesDemandesPresident', 
            'toutesLesDemandes',
            'stats'
        ));
    }
    /**
     * Affiche le tableau de bord du président
     */
    public function index()
    {
        $user = Auth::user();
        
        // Vérification des permissions
        //if (!$user->hasRole('president')) {
           // abort(403, 'Accès non autorisé');
        //}

        // 1. Demandes que le président peut traiter (≥ 4 jours, en attente, pas les siennes)
        $demandesTraitable = $this->getDemandesTraitableParPresident($user);

        // 2. Demandes personnelles du président
        $mesDemandesPresident = Demande::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // 3. Toutes les demandes (pour vue d'ensemble)
        $toutesLesDemandes = Demande::with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        // 4. Statistiques globales
        $stats = $this->calculerStatistiques($user);

        return view('president.dashboard', compact(
            'demandesTraitable',
            'mesDemandesPresident', 
            'toutesLesDemandes',
            'stats'
        ));
    }

    /**
     * Récupère les demandes que le président peut traiter
     * Critère : demandes ≥ 4 jours, en attente, pas ses propres demandes
     */
   private function getDemandesTraitableParPresident(User $president)
{
    // Récupère les demandes en attente avec leur utilisateur associé
    return Demande::with('user.direction')
        ->where('statut', 'en_attente')
        ->get()
        ->filter(function ($demande) use ($president) {
            // Calcul du nombre de jours
            $nombreJours = $demande->date_debut->diffInDays($demande->date_fin) + 1;

            // ✅ Cas 1 : ses propres demandes de moins de 3 jours
            if ($demande->user_id === $president->id && $nombreJours < 3) {
                return true;
            }

            // ✅ Cas 2 : demandes de sa direction
            if (
                $demande->user 
                && $demande->user->direction_id 
                && $president->direction_id 
                && $demande->user->direction_id === $president->direction_id
            ) {
                return true;
            }

            return false; // sinon non traitable
        })
        ->map(function ($demande) {
            // Ajouter les propriétés utiles à l’affichage
            $demande->nombre_jours = $demande->date_debut->diffInDays($demande->date_fin) + 1;
            $demande->date_debut_formatee = $demande->date_debut->format('d/m/Y');
            $demande->date_fin_formatee = $demande->date_fin->format('d/m/Y');

            // Indicateurs visuels
            $demande->est_tres_longue = $demande->nombre_jours >= 7;
            $demande->peut_traiter = true;

            return $demande;
        })
        ->sortByDesc('nombre_jours');
}

    /**
     * Calcule les statistiques pour le tableau de bord
     */
    private function calculerStatistiques(User $president)
    {
        $stats = [];
        
        // Total de toutes les demandes
        $stats['total_demandes'] = Demande::count();
        
        // Demandes en attente (excluant celles du président)
        $stats['en_attente_sans_moi'] = Demande::where('statut', 'en_attente')
            ->where('user_id', '!=', $president->id)
            ->count();
        
        // Demandes approuvées
        $stats['approuvees'] = Demande::where('statut', 'approuve')->count();
        
        // Demandes rejetées
        $stats['rejetees'] = Demande::where('statut', 'rejete')->count();
        
        // Demandes de longue durée (≥4 jours) en attente
        $demandesLongueDuree = Demande::where('statut', 'en_attente')
            ->where('user_id', '!=', $president->id)
            ->get()
            ->filter(function ($demande) {
                $nombreJours = $demande->date_debut->diffInDays($demande->date_fin) + 1;
                return $nombreJours >= 4;
            });
        
        $stats['longue_duree'] = $demandesLongueDuree->count();
        
        // Total des demandes en attente (incluant celles du président)
        $stats['en_attente'] = Demande::where('statut', 'en_attente')->count();
        
        return $stats;
    }

    /**
     * Affiche les détails d'une demande pour traitement par le président
     */
public function showDemande(Demande $demande)
{
    $user = Auth::user();

    // Charger les relations
    $demande->load(['user.direction']);

    // Vérifier si la demande a bien un utilisateur
    if (!$demande->user) {
        abort(404, "Cette demande (ID: {$demande->id}) n’est associée à aucun utilisateur.");
    }

    // Vérifier si l'utilisateur de la demande a bien une direction
    if (!$demande->user->direction) {
        abort(404, "L’utilisateur associé à la demande (ID: {$demande->user->id}) n’a pas de direction définie.");
    }

    // Calcul du nombre de jours
    $nombreJours = $demande->date_debut->diffInDays($demande->date_fin) + 1;

    // Cas : demandes exactement 3 jours → réservées aux DRH
    if ($nombreJours == 3) {
        abort(403, 'Les demandes de 3 jours doivent être traitées par le DRH.');
    }

    // Cas : demandes >= 4 jours → président peut traiter toutes (y compris les siennes)
    if ($nombreJours >= 4) {
        // OK
    }
    // Cas : demandes <= 2 jours → président peut traiter uniquement ses propres demandes ou celles de sa direction
    elseif ($nombreJours <= 2) {
        $estDeMaDirection = $demande->user->direction_id === $user->direction_id;
        $estMaDemande = $demande->user_id === $user->id;

        if (!($estDeMaDirection || $estMaDemande)) {
            abort(403, 'Vous ne pouvez traiter que vos propres demandes ou celles de votre direction (≤2 jours).');
        }
    }

    // Vérifier que la demande est encore en attente
    if ($demande->statut !== 'en_attente') {
        abort(403, 'Cette demande a déjà été traitée');
    }

    // Ajouter infos calculées
    $demande->nombre_jours = $nombreJours;
    $demande->est_tres_longue = $nombreJours >= 7;

    return view('president.demandes-show', compact('demande'));
}


    /**
     * Traite une demande (approuve ou rejette)
     */
    public function traiterDemande(Request $request, Demande $demande)
    {
        //$user = Auth::user();
        
        
        $nombreJours = $demande->date_debut->diffInDays($demande->date_fin) + 1;
        
        if ($nombreJours < 4) {
            return back()->with('error', 'Vous ne pouvez traiter que les demandes de 4 jours ou plus');
        }
        
        if ($demande->user_id === $user->id) {
            return back()->with('error', 'Vous ne pouvez pas traiter vos propres demandes');
        }
        
        if ($demande->statut !== 'en_attente') {
            return back()->with('error', 'Cette demande a déjà été traitée');
        }

        // Validation de la requête
        $request->validate([
            'action' => 'required|in:approuver,rejeter',
            'commentaire' => 'nullable|string|max:500'
        ]);

        // Traitement de la demande
        $demande->statut = $request->action === 'approuver' ? 'approuve' : 'rejete';
        $demande->commentaire_drh = $request->commentaire;
        $demande->traite_par = $user->id;
        $demande->traite_le = now();
        $demande->save();

        // Créer automatiquement une demande de jouissance si c'est un congé annuel approuvé
if ($demande->statut === 'approuve' && $demande->type === 'conge_annuel') {
    $demandeJouissance = $demande->creerDemandeJouissance();
    if ($demandeJouissance) {
        \Log::info('Demande de jouissance créée automatiquement', ['id' => $demandeJouissance->id]);
    }
}
        // Envoyer notification à l'employé (optionnel)
        // $this->envoyerNotification($demande, $user);

        $message = $request->action === 'approuver' 
            ? 'Demande approuvée avec succès' 
            : 'Demande rejetée avec succès';
            
        return redirect()->route('president.dashboard')
            ->with('success', $message);
    }

    /**
     * Affiche une demande spécifique (pour consultation)
     */
    public function voirDemande(Demande $demande)
    {
        $user = Auth::user();
        
if ($user->role !== 'president') {
        abort(403, 'Accès non autorisé');
    }

        // Ajouter les informations calculées
        $demande->nombre_jours = $demande->date_debut->diffInDays($demande->date_fin) + 1;
        
        return view('president.demandes-show', compact('demande'));
    }

    /**
     * Affiche le planning global (vue président)
     */
   /* public function planning()
    {


        // Récupérer toutes les demandes approuvées pour le planning
        $demandesApprouvees = Demande::with('user')
            ->where('statut', 'approuve')
            ->whereBetween('date_debut', [
                now()->startOfMonth()->subMonths(1),
                now()->endOfMonth()->addMonths(2)
            ])
            ->orderBy('date_debut')
            ->get();

        return view('planning.index', compact('demandesApprouvees'));
    }

    /**
     * Affiche les rapports exécutifs
     */
/*    public function rapports()
    {
        $user = Auth::user();
        
        if (!$user->hasRole('president')) {
            abort(403, 'Accès non autorisé');
        }

        // Statistiques avancées pour les rapports
        $rapportStats = [
            'total_demandes_mois' => Demande::whereMonth('created_at', now()->month)->count(),
            'taux_approbation' => $this->calculerTauxApprobation(),
            'demandes_par_type' => $this->getDemandesParType(),
            'demandes_longue_duree_stats' => $this->getStatsLongueDuree(),
            'employes_plus_actifs' => $this->getEmployesPlusActifs()
        ];

        return view('president.rapports', compact('rapportStats'));
    }*/

    /**
     * Calcule le taux d'approbation
     */
    private function calculerTauxApprobation()
    {
        $totalTraitees = Demande::whereIn('statut', ['approuve', 'rejete'])->count();
        $approuvees = Demande::where('statut', 'approuve')->count();
        
        return $totalTraitees > 0 ? round(($approuvees / $totalTraitees) * 100, 2) : 0;
    }

    /**
     * Statistiques par type de demande
     */
    private function getDemandesParType()
    {
        return Demande::selectRaw('type, COUNT(*) as total')
            ->groupBy('type')
            ->get()
            ->pluck('total', 'type')
            ->toArray();
    }

    /**
     * Statistiques des demandes de longue durée
     */
    private function getStatsLongueDuree()
    {
        $demandes = Demande::all();
        
        $stats = [
            'total' => 0,
            'moyenne_jours' => 0,
            'plus_longue' => 0
        ];
        
        $demandesLongues = $demandes->filter(function ($demande) {
            $jours = $demande->date_debut->diffInDays($demande->date_fin) + 1;
            return $jours >= 4;
        });
        
        if ($demandesLongues->count() > 0) {
            $stats['total'] = $demandesLongues->count();
            $stats['moyenne_jours'] = round($demandesLongues->avg(function ($demande) {
                return $demande->date_debut->diffInDays($demande->date_fin) + 1;
            }), 1);
            $stats['plus_longue'] = $demandesLongues->max(function ($demande) {
                return $demande->date_debut->diffInDays($demande->date_fin) + 1;
            });
        }
        
        return $stats;
    }

    /**
     * Employés les plus actifs (plus de demandes)
     */
    private function getEmployesPlusActifs()
    {
        return Demande::with('user')
            ->selectRaw('user_id, COUNT(*) as total_demandes')
            ->groupBy('user_id')
            ->orderByDesc('total_demandes')
            ->limit(5)
            ->get();
    }
}