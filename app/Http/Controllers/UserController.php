<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'matricule' => 'required|unique:users',
            'role' => 'required|in:admin,agent,user' ,
            'direction' => 'nullable|string|max:255',
            'poste' => 'nullable|string|max:255',
           'telephone' => 'nullable|string|regex:/^([+]?[\s0-9]+)?(\d{3}|[(]?[0-9]+[)])?([-]?[\s]?[0-9])+$/',
            'sexe' => 'required|in:masculin,feminin',

            // autres validations éventuelles
        ]);

        $user = User::create([
            'nom' => $validated['nom'],
            'prenom' => $validated['prenom'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'matricule' => $validated['matricule']
            // autres champs...
        ]);
// Dans votre méthode store()
$validated = $request->validate([
    'password' => 'required|min:8|confirmed',
    // ... autres règles de validation
    'sexe' => 'required|in:masculin,feminin', // Assurez-vous que c'est bien 'feminin' et non 'féminin'
    ]);

    $user = User::create([
        // ... autres champs
        'sexe' => $validated['sexe'], // Doit être exactement 'feminin'
    ]);

          $user->assignRole($validated['role']); // Assigne le rôle 'agent' par défaut

        return redirect()->route('users.index')->with('success', 'Utilisateur créé avec succès');
    }
    /*public function index()
{
    $users = User::all(); // Ou avec pagination: User::paginate(10);

    return view('users.index', compact('users'));
}*/
public function index(Request $request)
{
     $query = User::query();
        
        // Appliquer les filtres
        $filter = $request->get('filter', 'all');
        
        switch ($filter) {
            case 'active':
                $query->where('is_active', true);
                break;
            case 'inactive':
                $query->where('is_active', false);
                break;
        }
        
        $users = $query->orderBy('created_at', 'desc')->paginate(10);
        
        // Calculer les statistiques AVANT la pagination
        $totalUsers = User::count();
        $activeUsers = User::where('is_active', true)->count();
        $inactiveUsers = User::where('is_active', false)->count();
        
        return view('admin.utilisateurs.index', compact(
            'users', 
            'totalUsers', 
            'activeUsers', 
            'inactiveUsers',
            'filter'
        ));
    }
       /**
     * Désactiver un utilisateur
     */
    public function desactiver(User $user)
    {
        // Empêcher l'auto-désactivation
        if ($user->id === Auth::id()) {
            return redirect()->back()
                ->with('error', 'Vous ne pouvez pas désactiver votre propre compte.');
        }

        $user->update([
            'is_active' => false
        ]);

        // Déconnecter l'utilisateur s'il est connecté
        $this->deconnecterUtilisateur($user);

        return redirect()->back()
            ->with('success', "Le compte de {$user->prenom} {$user->nom} a été désactivé avec succès.");
    }

    /**
     * Réactiver un utilisateur
     */
    public function reactiver(User $user)
    {
        $user->update([
            'is_active' => true
        ]);

        return redirect()->back()
            ->with('success', "Le compte de {$user->prenom} {$user->nom} a été réactivé avec succès.");
    }

    /**
     * Déconnecter un utilisateur spécifique
     */
    private function deconnecterUtilisateur(User $user)
    {
        // Ici vous pourriez invalider les sessions de l'utilisateur
        // Cette partie est plus complexe et nécessite une gestion des sessions
        \Log::info("L'utilisateur {$user->email} a été désactivé et devrait être déconnecté.");
    }

    /**
     * Supprimer un utilisateur
     */
    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return redirect()->back()
                ->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $nomComplet = "{$user->prenom} {$user->nom}";
        
        $user->delete();

        return redirect()->back()
            ->with('success', "L'utilisateur {$nomComplet} a été supprimé définitivement.");
    }
}


