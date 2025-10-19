<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Demande;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SecretaireGeneralController extends Controller
{
   
    public function dashboard()
    {
        $user = Auth::user();
        
        if ($user->role !== 'secretaire_general') {
            abort(403, 'Accès réservé au Secrétaire Général');
        }
        
        // 1. Toutes les demandes avec calcul des jours
        $toutesLesDemandes = Demande::with(['user'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($demande) {
                $demande->date_debut_formatee = $demande->date_debut->format('d/m/Y');
                $demande->date_fin_formatee = $demande->date_fin->format('d/m/Y');
                $demande->nombre_jours = $demande->date_debut->diffInDays($demande->date_fin) + 1;
                return $demande;
            });

        // 2. Demandes que le SG peut traiter (≥ 4 jours, en attente)
        /*$demandesTraitable = $toutesLesDemandes->filter(function($demande) use ($user) {
            return $demande->statut === 'en_attente' 
                && $demande->nombre_jours >= 4
                && $demande->user_id !== $user->id; // Exclure ses propres demandes
        });*/
            // 2. Demandes que le SG peut traiter 
    // ✅ UNIQUEMENT les congés annuels de ≥ 4 jours
    $demandesTraitable = $toutesLesDemandes->filter(function($demande) use ($user) {
        return $demande->statut === 'en_attente' 
            && $demande->nombre_jours >= 4
            && $demande->user_id !== $user->id
            && $demande->type === 'conge_annuel'; // ← FILTRE IMPORTANT
    });

        // 3. Demandes personnelles du SG
        $mesDemandesSecretaire = Demande::where('user_id', $user->id)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($demande) {
                $demande->date_debut_formatee = $demande->date_debut->format('d/m/Y');
                $demande->date_fin_formatee = $demande->date_fin->format('d/m/Y');
                $demande->nombre_jours = $demande->date_debut->diffInDays($demande->date_fin) + 1;
                return $demande;
            });

        // 4. Statistiques
        $stats = [
            'total_demandes' => $toutesLesDemandes->count(),
            'total_demandes_4_jours_plus' => $toutesLesDemandes->where('nombre_jours', '>=', 4)->count(),
            'total_a_traiter' => $demandesTraitable->count(),
            'en_attente' => $toutesLesDemandes->where('statut', 'en_attente')->count(),
            'approuvees' => $toutesLesDemandes->where('statut', 'approuvee')->count(),
            'rejetees' => $toutesLesDemandes->where('statut', 'rejetee')->count(),
            'moins_4_jours' => $toutesLesDemandes->where('nombre_jours', '<', 4)->count(),
        ];

        return view('secretaire_general.dashboard', compact(
            'stats', 
            'demandesTraitable',
            'mesDemandesSecretaire',
            'toutesLesDemandes'
        ));
    }

    /**
     * Afficher une demande spécifique pour le SG
     */
    public function showDemande(Demande $demande)
    {
        $user = Auth::user();
        
        // Vérifier que l'utilisateur est secrétaire général
        if ($user->role !== 'secretaire_general') {
            abort(403, 'Accès réservé au Secrétaire Général.');
        }
        
        $demande->load(['user']);
        
        // Calculer le nombre de jours
        $demande->nombre_jours = $demande->date_debut->diffInDays($demande->date_fin) + 1;
        
        // Déterminer si le SG peut traiter la demande
        $demande->peut_traiter_par_sg = $demande->statut === 'en_attente' && $demande->nombre_jours >= 4;

        return view('secretaire_general.demande-show', compact('demande'));
    }

    /**
     * Traiter une demande (accepter/rejeter) - VERSION CORRIGEE
     */
    public function traiterDemande(Request $request, Demande $demande)
{
    \Log::info('Traitement demande par secrétaire général - DOUBLE VALIDATION', [
        'demande_id' => $demande->id,
        'action' => $request->input('action'),
        'user_id' => Auth::id(),
        'user_role' => Auth::user()->role,
        'statut_actuel' => $demande->statut,
        'type_demande' => $demande->type
    ]);

    $request->validate([
        'action' => 'required|in:accepter,rejeter',
        'commentaire' => 'nullable|string|max:500'
    ]);

    // Vérifications de sécurité
    if (Auth::user()->role !== 'secretaire_general') {
        abort(403, 'Accès réservé au Secrétaire Général.');
    }
    
    // Recharger la demande
    $demande->refresh();
    
    // Vérifications métier
    if (!in_array($demande->statut, ['en_attente', 'en_attente_secretaire'])) {
        return back()->with('error', 'Cette demande a déjà été traitée (statut: ' . $demande->statut . ').');
    }

    // Pour les congés annuels : vérification spéciale
    if ($demande->type === 'conge_annuel') {
        return $this->traiterCongeAnnuel($request, $demande);
    }

    // Pour les autres types de demandes : traitement normal
    return $this->traiterDemandeNormale($request, $demande);
}

/**
 * Traiter un congé annuel (double validation)
 */
private function traiterCongeAnnuel(Request $request, Demande $demande)
{
    try {
        if ($request->action === 'accepter') {
            // ✅ PREMIÈRE VALIDATION - Le SG approuve
            $updateData = [
                'validation_secretaire' => true,
                'date_validation_secretaire' => Carbon::now(),
                'statut' => 'en_attente_responsable', // Passe au responsable
                'traite_par' => Auth::id(),
                'traite_le' => Carbon::now(),
                'niveau_validation' => 'premiere_validation'
            ];
            
            // Ajouter le commentaire
            if ($request->filled('commentaire')) {
                $updateData['commentaire_secretaire_general'] = $request->commentaire;
            }
            
            $updated = $demande->update($updateData);

            if (!$updated) {
                \Log::error('Échec mise à jour demande congé annuel');
                return back()->with('error', 'Erreur lors de la validation de la demande.');
            }

            \Log::info('Congé annuel validé par SG - en attente responsable', [
                'demande_id' => $demande->id,
                'prochain_statut' => 'en_attente_responsable'
            ]);

            $message = '✅ Demande de congé annuel validée. En attente de la validation du responsable hiérarchique.';

        } else {
            // ❌ REJET PAR LE SG
            $updateData = [
                'statut' => 'rejetee',
                'traite_par' => Auth::id(),
                'traite_le' => Carbon::now(),
                'niveau_validation' => 'rejet_secretaire'
            ];
            
            if ($request->filled('commentaire')) {
                $updateData['commentaire_secretaire_general'] = $request->commentaire;
            }
            
            $demande->update($updateData);

            $message = '❌ Demande de congé annuel rejetée par le Secrétaire Général.';
        }

        return redirect()->route('secretaire_general.dashboard')->with('success', $message);
        
    } catch (\Exception $e) {
        \Log::error('Erreur traitement congé annuel par SG', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        return back()->with('error', 'Erreur lors du traitement: ' . $e->getMessage());
    }
}

/**
 * Traiter une demande normale (validation simple)
 */
private function traiterDemandeNormale(Request $request, Demande $demande)
{
    // Calculer le nombre de jours pour la vérification
    $nombreJours = $demande->date_debut->diffInDays($demande->date_fin) + 1;
    
    // Vérification durée minimale pour le SG
    if ($nombreJours < 4) {
        return back()->with('error', 'Le Secrétaire Général ne peut traiter que les demandes de 4 jours ou plus. Cette demande fait ' . $nombreJours . ' jours.');
    }

    try {
        // Déterminer le nouveau statut
        $nouveauStatut = $request->action === 'accepter' ? 'approuvee' : 'rejetee';
        
        $updateData = [
            'statut' => $nouveauStatut,
            'traite_par' => Auth::id(),
            'traite_le' => Carbon::now(),
            'niveau_validation' => 'secretaire_general'
        ];
        
        // Ajouter le commentaire
        if ($request->filled('commentaire')) {
            $updateData['commentaire_secretaire_general'] = $request->commentaire;
        }
        
        $updated = $demande->update($updateData);

        if (!$updated) {
            \Log::error('Échec mise à jour demande normale');
            return back()->with('error', 'Erreur lors de la mise à jour de la demande.');
        }

        \Log::info('Demande normale traitée par le SG', [
            'nouveau_statut' => $nouveauStatut,
            'demande_id' => $demande->id
        ]);

        // Créer une demande de jouissance si approuvée (uniquement pour les demandes normales)
        if ($updated && $nouveauStatut === 'approuvee' && $demande->type !== 'conge_annuel') {
            $demandeJouissance = $demande->creerDemandeJouissance();
            if ($demandeJouissance) {
                \Log::info('Demande de jouissance créée', ['id' => $demandeJouissance->id]);
            }
        }

        $message = $request->action === 'accepter' 
            ? '✅ Demande approuvée avec succès.' 
            : '❌ Demande rejetée.';

        return redirect()->route('secretaire_general.dashboard')->with('success', $message);
        
    } catch (\Exception $e) {
        \Log::error('Erreur traitement demande normale par SG', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        return back()->with('error', 'Erreur lors du traitement: ' . $e->getMessage());
    }
}
   
   public function approuverDemande(Demande $demande)
{
    $user = Auth::user();
    
    /*if ($user->role !== 'secretaire_general') {
        abort(403, 'Accès réservé au Secrétaire Général.');
    }*/
    
    if ($demande->statut !== 'en_attente') {
        return redirect()->back()->with('error', 'Cette demande a déjà été traitée.');
    }
    
    $nombreJours = $demande->date_debut->diffInDays($demande->date_fin) + 1;
    
    if ($nombreJours <= 3) {
        return redirect()->back()->with('error', 
            'Le Secrétaire Général ne peut traiter que les demandes de plus de 3 jours.');
    }
    
    // SOLUTION TEMPORAIRE : Utiliser une colonne existante
    $demande->update([
        'statut' => 'approuvee',
        'traite_par' => $user->id,
        'traite_le' => now(),
        'commentaire_drh' => request('commentaire', null) // Utilisez commentaire_drh temporairement
        // 'niveau_validation' => 'secretaire_general' // Supprimez temporairement
    ]);
    
    return redirect()->route("{{ url('/secretaire_general/dashboard') }}")
        ->with('success', 'La demande a été approuvée avec succès.');
}

public function rejeterDemande(Demande $demande)
{
    $user = Auth::user();
    
    /*if ($user->role !== 'secretaire_general') {
        abort(403, 'Accès réservé au Secrétaire Général.');
    }*/
    
    if ($demande->statut !== 'en_attente') {
        return redirect()->back()->with('error', 'Cette demande a déjà été traitée.');
    }
    
    request()->validate([
        'commentaire' => 'required|string|min:10|max:500'
    ]);
    
    $nombreJours = $demande->date_debut->diffInDays($demande->date_fin) + 1;
    
    if ($nombreJours <= 3) {
        return redirect()->back()->with('error', 
            'Le Secrétaire Général ne peut traiter que les demandes de plus de 3 jours.');
    }
    
    // SOLUTION TEMPORAIRE : Utiliser une colonne existante
    $demande->update([
        'statut' => 'rejetee',
        'traite_par' => $user->id,
        'traite_le' => now(),
        'commentaire_drh' => request('commentaire') // Utilisez commentaire_drh temporairement
        // 'niveau_validation' => 'secretaire_general' // Supprimez temporairement
    ]);
    
    return redirect()->route('secretaire_general.dashboard')
        ->with('success', 'La demande a été rejetée avec succès.');
}

/**
 * Vérifie si le secrétaire général peut traiter la demande
 */
private function peutTraiterDemandeSecretaireGeneral(Demande $demande)
{
    $user = Auth::user();
    $nombreJours = $demande->date_debut->diffInDays($demande->date_fin) + 1;
    
    // Le SG peut traiter si :
    // 1. L'utilisateur est SG
    // 2. La demande est en attente
    // 3. La durée est > 3 jours
    // 4. Ce n'est pas sa propre demande (optionnel)
    
    return $user->role === 'secretaire_general' 
        && $demande->statut === 'en_attente'
        && $nombreJours > 3
        && $demande->user_id !== $user->id;
}
    /**
     * Créer une nouvelle demande (pour le SG lui-même)
     */
    public function createDemande()
    {
        return view('demandes.create');
    }

    /**
     * Soumettre une nouvelle demande
     */
    public function storeDemande(Request $request)
    {
        $request->validate([
            'type' => 'required|in:conge,absence',
            'date_debut' => 'required|date|after_or_equal:today',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'motif' => 'required|string|max:1000'
        ]);

        try {
            $nombreJours = Carbon::parse($request->date_debut)->diffInDays(Carbon::parse($request->date_fin)) + 1;
            
            Demande::create([
                'user_id' => Auth::id(),
                'type' => $request->type,
                'date_debut' => $request->date_debut,
                'date_fin' => $request->date_fin,
                'motif' => $request->motif,
                'statut' => 'en_attente',
                'nombre_jours' => $nombreJours
            ]);

            return redirect()->route('secretaire_general.dashboard')
                ->with('success', 'Votre demande a été soumise avec succès.');

        } catch (\Exception $e) {
            \Log::error('Erreur création demande SG', ['error' => $e->getMessage()]);
            return back()->with('error', 'Erreur lors de la soumission: ' . $e->getMessage());
        }
    }

    /**
     * Gestion des employés
     */
    public function gestionEmployes()
    {
        $employes = User::with(['direction'])
            ->where('role', '!=', 'secretaire_general')
            ->orderBy('nom')
            ->paginate(20);

        return view('secretaire_general.gestion-employes', compact('employes'));
    }

    /**
     * Statistiques avancées
     */
    public function statistiquesAvancees()
    {
        $anneeActuelle = date('Y');
        
        $statsGlobales = [
            'total_employes' => User::where('role', '!=', 'secretaire_general')->count(),
            'total_demandes_annee' => Demande::whereYear('created_at', $anneeActuelle)->count(),
            'taux_approbation' => $this->calculerTauxApprobation($anneeActuelle),
        ];

        return view('secretaire_general.statistiques', compact('statsGlobales', 'anneeActuelle'));
    }

    private function calculerTauxApprobation($annee)
    {
        $totalTraitees = Demande::whereYear('created_at', $annee)
            ->whereIn('statut', ['approuve', 'rejete'])
            ->count();
            
        if ($totalTraitees === 0) return 0;
        
        $approuvees = Demande::whereYear('created_at', $annee)
            ->where('statut', 'approuve')
            ->count();
            
        return round(($approuvees / $totalTraitees) * 100, 1);
    }
}