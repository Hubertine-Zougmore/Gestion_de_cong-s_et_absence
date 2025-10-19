<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\Demande;
use App\Traits\HasRoleChecks;
use App\Services\RoleService;

class AgentDashboardController extends Controller
{
    use HasRoleChecks;

    protected $roleService;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (auth()->user()->role !== 'agent') {
                abort(403, 'Accès non autorisé - Réservé aux agents');
            }
            return $next($request);
        });
    }
    /**
     * Afficher le tableau de bord de l'agent
     */
    public function dashboard()
    {
        $user = Auth::user();
        $congeService = new \App\Services\CongeService();
        
        // Statistiques des demandes de l'agent connecté
        $totalDemandes = Demande::where('user_id', $user->id)->count();
        $demandesEnAttente = Demande::where('user_id', $user->id)
                                   ->where('statut', 'en_attente')
                                   ->count();
        $demandesApprouvees = Demande::where('user_id', $user->id)
                                    ->where('statut', 'approuve')
                                    ->count();
        $demandesRejetees = Demande::where('user_id', $user->id)
                                  ->where('statut', 'rejete')
                                  ->count();

        // Dernières demandes
        $dernieresDemandes = Demande::where('user_id', $user->id)
                                   ->orderBy('created_at', 'desc')
                                   ->limit(5)
                                   ->get();

        // Demandes en attente (les plus récentes)
        $demandesEnAttenteDetails = Demande::where('user_id', $user->id)
                                          ->where('statut', 'en_attente')
                                          ->orderBy('created_at', 'desc')
                                          ->limit(3)
                                          ->get();

        // Résumé des congés et quotas
        $resumeConges = $congeService->getResumeCongés($user);

        return view('agent.dashboard', compact(
            'user',
            'totalDemandes',
            'demandesEnAttente',
            'demandesApprouvees', 
            'demandesRejetees',
            'dernieresDemandes',
            'demandesEnAttenteDetails',
            'resumeConges'
        ));
    }

    public function index()
    {
        // Vérification du rôle
        if (auth()->user()->role !== 'agent') {
            return redirect()->route('home')
                             ->with('error', 'Accès réservé aux agents.');
        }

        // Récupérer les demandes de l'agent connecté
        $demandes = Demande::where('user_id', auth()->id())
                          ->orderBy('created_at', 'desc')
                          ->paginate(10);

        return view('agent.dashboard', [
            'user' => auth()->user(),
            'demandes' => $demandes
        ]);
        
    }
 /*public function mesDemandes()
    {
        // Récupérer UNIQUEMENT les demandes de l'agent connecté
        $demandes = Demande::where('user_id', Auth::id())
                          ->orderBy('created_at', 'desc')
                          ->get();
        
        return view('agent.mes-demandes', compact('demandes'));
    }*/

    public function showDemande($id)
    {
        // Vérifier que la demande appartient bien à l'agent connecté
        $demande = Demande::where('id', $id)
                         ->where('user_id', Auth::id())
                         ->firstOrFail(); // 404 si pas trouvé ou pas propriétaire
        
        $this->authorize('view', $demande);
        return view('agent.demande-show', compact('demande'));
    }

    public function editDemande($id)
    {
        // Seul le propriétaire peut modifier sa demande
        $demande = Demande::where('id', $id)
                         ->where('user_id', Auth::id())
                         ->firstOrFail();
        
        // Optionnel : empêcher la modification si déjà approuvée
        if ($demande->statut === 'approuve') {
            return redirect()->back()->with('error', 'Vous ne pouvez pas modifier une demande déjà approuvée.');
        }
        $this->authorize('update', $demande);
        return view('agent.demande-edit', compact('demande'));
    }

    public function updateDemande(Request $request, $id)
    {
        $demande = Demande::where('id', $id)
                         ->where('user_id', Auth::id())
                         ->firstOrFail();
        
        // Empêcher la modification si déjà traitée
        if (in_array($demande->statut, ['approuve', 'rejete'])) {
            return redirect()->back()->with('error', 'Vous ne pouvez pas modifier une demande déjà traitée.');
        }
         $this->authorize('update', $demande);
        $demande->update($request->validated());
        
        return redirect()->route('agent.mes-demandes.index')->with('success', 'Demande mise à jour avec succès.');
    }

    public function deleteDemande($id)
    {
        $demande = Demande::where('id', $id)
                         ->where('user_id', Auth::id())
                         ->firstOrFail();
        
        // Empêcher la suppression si déjà traitée
        if (in_array($demande->statut, ['approuve', 'rejete'])) {
            return redirect()->back()->with('error', 'Vous ne pouvez pas supprimer une demande déjà traitée.');
        }
         $this->authorize('delete', $demande);
        $demande->delete();
        return redirect()->route('agent.mes-demandes.index')->with('success', 'Demande supprimée avec succès.');
    }


}