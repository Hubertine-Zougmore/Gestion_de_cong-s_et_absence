<?php

namespace App\Http\Controllers;

use App\Models\QuotaUtilisateur;
use Illuminate\Support\Facades\Storage;
use App\Models\Demande;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Carbon\Carbon;
use App\Models\User;
use App\Notifications\DemandeNotification;
use App\Models\TypeConge;

class DemandeController extends Controller
{
    /**
     * Afficher la liste des demandes
     */
   // Dans votre contrôleur (DemandeRhController.php ou autre)
public function index()
{
    $user = auth()->user();
    
$stats = [
    'total_demandes' => Demande::count(),
    'en_attente' => Demande::where('statut', 'en_attente')->count(),
    'approuvees' => Demande::where('statut', 'approuve')->count(),
    'rejetees' => Demande::where('statut', 'rejete')->count(),
    'urgentes' => Demande::where('statut', 'en_attente')
        ->where('date_debut', '<=', now()->addHours(72))
        ->count(),
];



// REMPLACER par cette nouvelle logique pour le DRH :
if ($user->role === 'drh') {
    // Statistiques supplémentaires pour le DRH
    $stats['de_ma_direction'] = Demande::where('statut', 'en_attente')
        ->whereHas('user', function($query) use ($user) {
            // Supposons que vous avez une relation 'direction' sur le modèle User
            $query->where('direction_id', $user->direction_id);
        })
        ->count();
        
    $stats['urgentes_de_ma_direction'] = Demande::where('statut', 'en_attente')
        ->where('date_debut', '<=', now()->addHours(72))
        ->whereHas('user', function($query) use ($user) {
            $query->where('direction_id', $user->direction_id);
        })
        ->count();
        
    $stats['mes_demandes'] = Demande::where('statut', 'en_attente')
        ->where('user_id', $user->id)
        ->count();
        
    $stats['mes_demandes_urgentes'] = Demande::where('statut', 'en_attente')
        ->where('user_id', $user->id)
        ->where('date_debut', '<=', now()->addHours(72))
        ->count();
}
elseif($user->role === 'responsable_hierarchique') {
    // Statistiques supplémentaires pour le DRH
    $stats['de_ma_direction'] = Demande::where('statut', 'en_attente')
        ->whereHas('user', function($query) use ($user) {
            // Supposons que vous avez une relation 'direction' sur le modèle User
            $query->where('direction_id', $user->direction_id);
        })
        ->count();
        
    $stats['urgentes_de_ma_direction'] = Demande::where('statut', 'en_attente')
        ->where('date_debut', '<=', now()->addHours(72))
        ->whereHas('user', function($query) use ($user) {
            $query->where('direction_id', $user->direction_id);
        })
        ->count();
        
    $stats['mes_demandes'] = Demande::where('statut', 'en_attente')
        ->where('user_id', $user->id)
        ->count();
        
    $stats['mes_demandes_urgentes'] = Demande::where('statut', 'en_attente')
        ->where('user_id', $user->id)
        ->where('date_debut', '<=', now()->addHours(72))
        ->count();

}

    if ($user->role === 'agent') {
        # code...
        $demandes = Demande::where('user_id', Auth::id())
                          ->orderBy('created_at', 'desc')
                          ->get();
    }
    elseif($user->role === 'responsable_hierarchique') {
        # code...
        $demandes = Demande::where('user_id', Auth::id())
                          ->orderBy('created_at', 'desc')
                          ->get();
    }
    elseif($user->role === 'drh') {
        # code...
        $demandes = Demande::where('user_id', Auth::id())
                          ->orderBy('created_at', 'desc')
                          ->get();
    }
    elseif($user->role === 'president') {
        # code...
        $demandes = Demande::where('user_id', Auth::id())
                          ->orderBy('created_at', 'desc')
                          ->get();
    }
    elseif($user->role === 'secretaire_general') {
        # code...
        $demandes = Demande::where('user_id', Auth::id())
                          ->orderBy('created_at', 'desc')
                          ->get();
    }
    
    else{
        $demandes = Demande::orderBy('created_at', 'desc')->get();
    }

    return view('demandes.index', compact('demandes', 'stats'));
}
    /**
     * Afficher le formulaire de création
     */
    public function create(): View
    {
        return view('demandes.create');
    }
/**
 * Afficher les détails d'une demande
 */
public function show($id)
{
    $demande = Demande::with('user')->findOrFail($id);
    
    // Vérifier les permissions
    if ($demande->user_id !== Auth::id() && !in_array(Auth::user()->role, ['drh', 'admin','agent','responsable_hierarchique', 'president', 'secretaire_general'])) {
        abort(403, 'Accès non autorisé.');
    }
$demande->load(['user', 'demandeJouissance']);
    return view('demandes.show', compact('demande'));
}

    /**
     * Enregistrer une nouvelle demande
     */
 public function store(Request $request): RedirectResponse
{
    $user = Auth::user();

    $validated = $request->validate([
        'type' => 'required|string|max:255',
        'date_debut' => 'required|date',
        'date_fin' => 'required|date|after_or_equal:date_debut',
        'motif' => 'nullable|string',
        'justificatif' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        'type_justificatif' => 'nullable|string|max:255'
    ]);

       // VALIDATION SERVEUR DES CONGÉS ANNUELS
    if ($request->type === 'conge_annuel') {
        $dateDebut = Carbon::parse($request->date_debut);
        $dateFin = Carbon::parse($request->date_fin);
        
        $moisDebut = $dateDebut->month;
        $moisFin = $dateFin->month;
        
        // Vérifier les mois
        if ($moisDebut < 8 || $moisDebut > 9 || $moisFin < 8 || $moisFin > 9) {
            return redirect()->back()
                ->withInput()
                ->with('error', '❌ Les congés annuels sont strictement réservés aux mois d\'août (8) et septembre (9).');
        }
        
        // Vérifier l'année
        if ($dateDebut->year !== $dateFin->year) {
            return redirect()->back()
                ->withInput()
                ->with('error', '❌ Les congés annuels doivent être pris dans la même année civile.');
        }
        
        // Vérifier la durée
        $nombreJours = $dateDebut->diffInDays($dateFin) + 1;
        $congeRestant = Auth::user()->conge_annuel_restant ?? 0;
        
        if ($nombreJours > $congeRestant) {
            return redirect()->back()
                ->withInput()
                ->with('error', "❌ Vous n'avez que {$congeRestant} jours de congés annuels restants. Vous avez sélectionné {$nombreJours} jours.");
        }
    }

    try {
        // Création de la demande
        Demande::create([
            'user_id' => Auth::id(),
            'type' => $request->type,
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
            'motif' => $request->motif,
            'statut' => 'en_attente',
            'nombre_jours' => $nombreJours ?? $dateDebut->diffInDays($dateFin) + 1
        ]);

        return redirect()->route('demandes.index')
            ->with('success', '✅ Votre demande a été soumise avec succès !');

    } catch (\Exception $e) {
        return redirect()->back()
            ->withInput()
            ->with('error', '❌ Erreur lors de la soumission : ' . $e->getMessage());
    }


    $dateDebut = Carbon::parse($validated['date_debut']);
    $dateFin = Carbon::parse($validated['date_fin']);
    $joursDemandes = $dateDebut->diffInDays($dateFin) + 1;

    // 1. Récupérer le type de congé
    $typeConge = TypeConge::where('nom', $validated['type'])->first();
    if (!$typeConge) {
        return back()->withErrors(['type' => 'Type de congé inconnu.']);
    }

    if ($validated['type'] === 'conge_maternite' && $user->sexe !== 'feminin') {
        return back()->withErrors([
            'quota' => 'Le congé maternité est reservé aux personnes de sexe féminin.'
        ]);
    }

    // 2. Vérifier conditions
    if ($typeConge->conditions && !$this->verifierCondition($typeConge->conditions, $user)) {
        return back()->withErrors([
            'conditions' => "Vous ne remplissez pas les conditions pour ce type de congé."
        ]);
    }

    // 3. Vérifier quota
    $joursDejaPris = Demande::where('user_id', $user->id)
        ->where('type', $validated['type'])
        ->whereYear('date_debut', now()->year)
        ->where('statut', 'approuve')
        ->sum('nombre_jours');

    if ($joursDejaPris + $joursDemandes > $typeConge->duree_max) {
        return back()->withErrors([
            'quota' => "Quota dépassé. Maximum autorisé : {$typeConge->duree_max} jours par an."
        ]);
    }

    // 4. Sauvegarde du justificatif
    $justificatifPath = $request->hasFile('justificatif')
        ? $request->file('justificatif')->store('justificatifs', 'public')
        : null;

    // 5. Enregistrement
    $demande = Demande::create([
        'user_id' => $user->id,
        'type' => $validated['type'],
        'date_debut' => $validated['date_debut'],
        'date_fin' => $validated['date_fin'],
        'motif' => $validated['motif'],
        'nombre_jours' => $joursDemandes,
        'statut' => 'en_attente',
        'justificatif' => $justificatifPath,
        'type_justificatif' => $validated['type_justificatif'],
    ]);

    // ⚡ Notifications après création
$responsable = $demande->user->responsable ?? null;
$drh = User::where('role', 'drh')->first();
$president = User::where('role', 'president')->first();
$president = User::where('role', 'secretaire_general')->first();

$message = "Nouvelle demande de {$demande->type} soumise par {$demande->user->prenom} {$demande->user->nom}";

// Notifier le responsable hiérarchique (si défini)
//if ($responsable) {
    //$responsable->notify(new DemandeNotification($message, $demande->id));
//}
// notifier le responsable hiérarchique de l’agent
if ($demande->user->direction_id) {
    $responsables = User::where('role', 'responsable_hierarchique')
                        ->where('direction_id', $demande->user->direction_id)
                        ->get();
    foreach ($responsables as $resp) {
        $resp->notify(new DemandeNotification("Demande de congé d’un agent de votre direction"));
    }
}

// Notifier le DRH
if ($drh) {
    $drh->notify(new DemandeNotification($message, $demande->id));
}

// Notifier le président
if ($president) {
    $president->notify(new DemandeNotification($message, $demande->id));
}

// 🔄 Redirection
if ($user->role === 'responsable_hierarchique') {
    return redirect()->route('responsable.mes-demandes')
                     ->with('success', 'Votre demande a été soumise avec succès !');
} else {
    return redirect()->route('demandes.index')
                     ->with('success', 'Votre demande a été soumise avec succès !');
}

}


    private function verifierCondition(string $condition, $user): bool
{
    if (str_starts_with($condition, 'anciennete>=')) {
        $moisMin = (int) str_replace('anciennete>=', '', $condition);
        $anciennete = \Carbon\Carbon::parse($user->date_embauche)->diffInMonths(now());
        return $anciennete >= $moisMin;
    }

    if ($condition === 'sexe=feminin') {
        return $user->sexe === 'feminin';
    }
     if ($typeConge->conditions === 'anciennete>=11') {
            $anciennete = \Carbon\Carbon::parse($user->date_embauche)->diffInMonths(now());
            return $anciennete >= 11;
        }

        if ($typeConge->conditions === 'sexe=feminin') {
            return $user->sexe === 'feminin';
        }

    return true; // si aucune condition particulière
}


    /**
     * Afficher une demande spécifique
     */
    

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(Demande $demande): View
    {
         //$demande = Demande::findOrFail($id);
        // Vérifier que l'utilisateur peut modifier cette demande
        if ($demande->user_id !== auth()->id()) {
            abort(403);
        }

        // Vérifier que la demande peut être modifiée (seulement si en attente)
        if ($demande->statut !== 'en_attente') {
            return redirect()->route('demandes.index')
                           ->with('error', 'Vous ne pouvez modifier que les demandes en attente.');
        }

        return view('demandes.edit', compact('demande'));
    }

    /**
     * Mettre à jour une demande
     */
   public function update(Request $request, Demande $demande): RedirectResponse
{
    // Vérifications de sécurité
    if ($demande->user_id !== auth()->id()) {
        abort(403);
    }

    if ($demande->statut !== 'en_attente') {
        return redirect()->route('demandes.index')
                       ->with('error', 'Vous ne pouvez modifier que les demandes en attente.');
    }

    $validated = $request->validate([
        'type' => 'required|string|in:conge_annuel,conge_maladie,conge_maternite,autorisation_absence',
        'date_debut' => 'required|date|after_or_equal:today',
        'date_fin' => 'required|date|after_or_equal:date_debut',
        'motif' => 'required|string|min:10|max:500',
        'justificatif' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        'type_justificatif' => 'nullable|string|max:255'
    ], [
        'type.required' => 'Le type de congé est requis.',
        'date_debut.required' => 'La date de début est requise.',
        'date_fin.required' => 'La date de fin est requise.',
        'date_fin.after_or_equal' => 'La date de fin doit être égale ou postérieure à la date de début.',
        'motif.required' => 'Le motif est requis.',
        'motif.min' => 'Le motif doit contenir au moins 10 caractères.',
    ]);

    // Calculer le nombre de jours demandés
    $nombreJours = $this->calculerJoursOuvrables($validated['date_debut'], $validated['date_fin']);
    
    // Vérifier si l'utilisateur a assez de jours disponibles
    if (!$this->verifierQuotas(auth()->user(), $validated['type'], $nombreJours, $demande)) {
        return redirect()->back()
                       ->with('error', 'Solde insuffisant pour ce type de congé.')
                       ->withInput();
    }

    // Traitement du justificatif
    if ($request->hasFile('justificatif')) {
        // Supprimer l'ancien justificatif s'il existe
        if ($demande->justificatif) {
            Storage::disk('public')->delete($demande->justificatif);
        }
        
        $justificatifPath = $request->file('justificatif')->store('justificatifs', 'public');
        $validated['justificatif'] = $justificatifPath;
    }

    // Mettre à jour la demande avec le nombre de jours
    $validated['nombre_jours'] = $nombreJours;
    $demande->update($validated);

    return redirect()->route('demandes.index')
                     ->with('success', 'Demande mise à jour avec succès.');
}

// Méthodes helper à ajouter à votre contrôleur
private function calculerJoursOuvrables($dateDebut, $dateFin)
{
    $debut = Carbon::parse($dateDebut);
    $fin = Carbon::parse($dateFin);
    return $debut->diffInWeekdays($fin) + 1; // +1 pour inclure le premier jour
}

private function verifierQuotas($user, $typeConge, $joursDemandes, $demandeExistante = null)
{
    // Récupérer le quota pour ce type de congé
    $quota = QuotaUtilisateur::where('user_id', $user->id)
                            ->where('type_conge', $typeConge)
                            ->where('annee', now()->year)
                            ->first();
    
    if (!$quota) {
        // Créer un quota par défaut si inexistant
        $joursAlloues = $this->getJoursAllouesParDefaut($typeConge);
        $quota = QuotaUtilisateur::create([
            'user_id' => $user->id,
            'type_conge' => $typeConge,
            'annee' => now()->year,
            'jours_alloues' => $joursAlloues,
            'jours_utilises' => 0,
            'jours_restants' => $joursAlloues
        ]);
    }
    
    // Calculer les jours actuellement utilisés (hors la demande actuelle si modification)
    $joursUtilises = $quota->jours_utilises;
    
    // Si on modifie une demande existante, soustraire ses jours pour avoir le vrai solde
    if ($demandeExistante && $demandeExistante->type === $typeConge) {
        $joursUtilises -= $demandeExistante->nombre_jours;
    }
    
    // Vérifier si le solde est suffisant
    return ($quota->jours_alloues - $joursUtilises) >= $joursDemandes;
}

private function getJoursAllouesParDefaut($typeConge)
{
    switch ($typeConge) {
        case 'conge_annuel': return 30;
        case 'conge_maladie': return 15;
        case 'conge_maternite': return 98;
        case 'autorisation_absence': return 10;
        default: return 0;
    }
}

/**
 * Télécharger un justificatif
 */
public function downloadJustificatif($id)
{
    $demande = Demande::findOrFail($id);
    
    /*Vérifier les permissions
    if ($demande->user_id !== Auth::id() && !in_array(Auth::user()->role, ['drh', 'admin'])) {
        abort(403, 'Accès non autorisé.');
    }*/

    if (!$demande->justificatif) {
        abort(404, 'Aucun justificatif trouvé pour cette demande.');
    }

    $filePath = storage_path('app/public/' . $demande->justificatif);
    
    if (!file_exists($filePath)) {
        abort(404, 'Fichier non trouvé.');
    }

    return response()->download($filePath);
}

/**
 * Afficher un justificatif dans le navigateur
 */
public function viewJustificatif($id)
{
    $demande = Demande::findOrFail($id);
    
    /* Vérifier les permissions
    if ($demande->user_id !== Auth::id() && !in_array(Auth::user()->role, ['drh', 'admin'])) {
        abort(403, 'Accès non autorisé.');
    }*/

    if (!$demande->justificatif) {
        abort(404, 'Aucun justificatif trouvé pour cette demande.');
    }

    $filePath = storage_path('app/public/' . $demande->justificatif);
    
    if (!file_exists($filePath)) {
        abort(404, 'Fichier non trouvé.');
    }

    return response()->file($filePath);

/**
 * Afficher un justificatif dans le navigateur
 */



        // Recalculer le nombre de jours
        $timestampDebut = strtotime($validated['date_debut']);
        $timestampFin = strtotime($validated['date_fin']);
        $nombreJours = ($timestampFin - $timestampDebut) / (60 * 60 * 24) + 1;

        try {
            $demande->update([
                'type' => $validated['type'],
                'date_debut' => $validated['date_debut'],
                'date_fin' => $validated['date_fin'],
                'motif' => $validated['motif'],
                'nombre_jours' => $nombreJours,
            ]);

            return redirect()->route('demandes.index')
                           ->with('success', 'Votre demande a été mise à jour avec succès.');

        } catch (\Exception $e) {
            return back()->withInput()
                        ->with('error', 'Une erreur est survenue lors de la mise à jour.');
        }
    }


    
    /**
     * Supprimer une demande
     */
    public function destroy(Demande $demande): RedirectResponse
    {
        // Vérifications de sécurité
        if ($demande->user_id !== auth()->id()) {
            abort(403);
        }

        if ($demande->statut !== 'en_attente') {
            return redirect()->route('demandes.index')
                           ->with('error', 'Vous ne pouvez supprimer que les demandes en attente.');
        }

        try {
            $demande->delete();
            
            return redirect()->route('demandes.index')
                           ->with('success', 'Votre demande a été supprimée avec succès.');
                           
        } catch (\Exception $e) {
            return redirect()->route('demandes.index')
                           ->with('error', 'Une erreur est survenue lors de la suppression.');
        }
    }

    /**
     * Approuver une demande (pour les administrateurs)
     */
    public function approve(Demande $demande): RedirectResponse
    {
        // Vérifier les permissions d'administrateur
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $demande->update(['statut' => 'approuve']);

        return back()->with('success', 'Demande approuvée avec succès.');
    }

    /**
     * Refuser une demande (pour les administrateurs)
     */
    public function reject(Request $request, Demande $demande): RedirectResponse
    {
        // Vérifier les permissions d'administrateur
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $demande->update([
            'statut' => 'rejete',
            'commentaire_admin' => $request->input('commentaire', '')
        ]);

        return back()->with('success', 'Demande rejetée avec succès.');
    }
    /////////////////////////Notification de l'agent///////////////////
    public function traiter(Request $request, $id)
{
    $demande = Demande::findOrFail($id);

    $demande->statut = $request->input('statut'); // accepté ou rejeté
    $demande->save();

    // ✅ Notifier uniquement l’agent concerné
    $message = "Votre demande de {$demande->type} a été {$demande->statut}.";
    $demande->user->notify(new DemandeNotification($message, $demande->id));

    return back()->with('success', "La demande a été {$demande->statut} et l’agent a été notifié.");
}

}