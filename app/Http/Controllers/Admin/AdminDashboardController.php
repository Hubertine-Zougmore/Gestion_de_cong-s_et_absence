<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller; 
use App\Models\User;
use App\Models\Conge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Demande;


class AdminDashboardController extends Controller
{
    public function index()
    {
         $users = User::all();
        $stats = [
            'total_users' => User::count(),
            'total_conges' => Conge::count(),
            'conges_en_attente' => Conge::where('statut', 'en_attente')->count(),
            'conges_approuves' => Conge::where('statut', 'approuvé')->count(),
            
        ];
         //$utilisateurs = User::all();
        //return view('admin.utilisateurs.index', compact('users'));
        // Ajouter les dernières demandes
    $dernieresDemandes = Demande::latest()->take(5)->with('user')->get();

    return view('admin.dashboard', [
        'stats' => $stats,
        'dernieresDemandes' => $dernieresDemandes,
        ]);


        $recentConges = Conge::with('user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            

        return view('admin.dashboard', compact('stats', 'recentConges'));
    }
      public function create()
    {
        return view('admin.utilisateurs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'role' => 'required|in:admin,agent,drh,responsable,secretariat',
            'password' => 'required|min:6',
            'date_embauche' => 'nullable|date',
        ]);
$validated['password'] = Hash::make($validated['password']);
    $validated['is_active'] = true;

    User::create($validated);
        User::create([
            'nom' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => bcrypt($request->password),
            'is_active' => true,
        ]);

        return redirect()->route('admin.utilisateurs.index')->with('success', 'Utilisateur ajouté avec succès.');
    }

public function dashboard()
{
    return view('admin.dashboard'); 
}

    public function edit($id)
    {
        $utilisateur = User::findOrFail($id);
        return view('admin.utilisateurs.edit', compact('utilisateur'));
    }

    public function update(Request $request, $id)
    {
        $utilisateur = User::findOrFail($id);
        $request->validate([
            'nom' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required'
        ]);

        $utilisateur->update([
            'nom' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);

        return redirect()->route('admin.utilisateurs.index')->with('success', 'Utilisateur modifié.');
    }

    public function destroy($id)
    {
        User::destroy($id);
        return redirect()->route('admin.utilisateurs.index')->with('success', 'Utilisateur supprimé.');
    }

    public function activer($id)
    {
        $user = User::findOrFail($id);
        $user->update(['is_active' => true]);
        return redirect()->route('admin.utilisateurs.index')->with('success', 'Utilisateur activé.');
    }

    public function desactiver($id)
    {
        $user = User::findOrFail($id);
        $user->update(['is_active' => false]);
        return redirect()->route('admin.utilisateurs.index')->with('success', 'Utilisateur désactivé.');
    }

}

/*class ManagerDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $stats = [
            'conges_a_approuver' => Conge::where('statut', 'en_attente')->count(),
            'conges_approuves_par_moi' => Conge::where('approuve_par', $user->id)->count(),
            'mes_conges' => Conge::where('user_id', $user->id)->count(),
        ];

        $congesEnAttente = Conge::with('user')
            ->where('statut', 'en_attente')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        $mesConges = Conge::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('dashboard.manager', compact('stats', 'congesEnAttente', 'mesConges'));
    }
}

class EmployeeDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $stats = [
            'mes_conges_total' => Conge::where('user_id', $user->id)->count(),
            'conges_en_attente' => Conge::where('user_id', $user->id)
                ->where('statut', 'en_attente')->count(),
            'conges_approuves' => Conge::where('user_id', $user->id)
                ->where('statut', 'approuve')->count(),
            'jours_pris_cette_annee' => Conge::where('user_id', $user->id)
                ->where('statut', 'approuve')
                ->whereYear('date_debut', date('Y'))
                ->sum('nb_jours'),
        ];

        $mesConges = Conge::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('dashboard.employee', compact('stats', 'mesConges'));
    }
}*/