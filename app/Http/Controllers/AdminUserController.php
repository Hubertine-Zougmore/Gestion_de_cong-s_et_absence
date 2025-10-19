<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{

public static function getRolesList()
    {
        return [
            'agent' => 'Agent',
            'responsable_hierarchique' => 'Responsable Hiérarchique',
            'drh' => 'DRH',
            'admin' => 'Administrateur',
            'secretaire_general' => 'Secrétaire Général',
            'president' => 'Président',
        ];
    }
    
   public function index()
{
    $query = User::query();
    
    // Appliquer les filtres
    switch (request('filter', 'all')) {
        case 'active':
            $query->where('is_active', true);
            break;
        case 'inactive':
            $query->where('is_active', false);
            break;
    }
    
    // Ajouter la pagination
    $users = $query->orderBy('is_active', 'desc')
                   ->orderBy('nom')
                   ->paginate(10); // 10 utilisateurs par page
    
    return view('admin.utilisateurs.index', compact('users'));
}

    public function create()
    {
        return view('admin.utilisateurs.create');
    }

  public function store(Request $request)
{
    $validated = $request->validate([
        'nom' => 'required|string|max:255',
        'prenom' => 'required|string|max:255',
        'email' => 'required|email|unique:users',
        'role' => 'required|in:agent,responsable_hierarchique,drh,admin,sg,president',
        'password' => 'required|confirmed',
        'date_embauche' => 'nullable|date',
        'matricule' => 'nullable|string|max:255',
        'direction' => 'nullable|string|max:255',
        'poste' => 'nullable|string|max:255',
        'telephone' => 'nullable|string|max:20',
        'is_active' => 'nullable|boolean'
    ]);
    // Traitement du mot de passe
    $validated['password'] = Hash::make($validated['password']);
    
    // Valeur par défaut pour is_active
    $validated['is_active'] = $validated['is_active'] ?? true;

    $user = User::create($validated);

    return redirect()->route('admin.utilisateurs.index')
                     ->with('success', 'Utilisateur créé avec succès.');
}



    public function edit($id)
{
    $utilisateur = User::findOrFail($id);
    
    $roles = [
        'agent' => 'Agent',
        'responsable_hierarchique' => 'Responsable Hiérarchique',
        'drh' => 'DRH',
        'admin' => 'Administrateur',
        'secretaire_general' => 'Secrétaire Général',
        'president' => 'Président',
    ];
    
    return view('admin.utilisateurs.edit', compact('utilisateur', 'roles'));
}
public function update(Request $request, $id)
{
    \Log::info('=== DÉBUT MISE À JOUR UTILISATEUR ===');
    \Log::info('ID utilisateur: ' . $id);
    \Log::info('Données reçues:', $request->all());
    
    $utilisateur = User::find($id);
    
    if (!$utilisateur) {
        \Log::error('Utilisateur non trouvé avec ID: ' . $id);
        return redirect()->back()->with('error', 'Utilisateur non trouvé.');
    }
    
    \Log::info('Utilisateur avant mise à jour:', $utilisateur->toArray());
    
    // Validation des données
    $validatedData = $request->validate([
        'nom' => 'required|string|max:255',
        'prenom' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $id,
        'matricule' => 'nullable|string|max:255',
        'direction' => 'nullable|string|max:255',
        'poste' => 'nullable|string|max:255',
        'telephone' => 'nullable|string|max:255',
        'role' => 'required|in:agent,responsable_hierarchique,drh,admin,secretaire_general,president',
        'date_embauche' => 'nullable|date',
        'is_active' => 'sometimes|boolean'
    ]);

    \Log::info('Données validées:', $validatedData);
    
    // Vérification spécifique du rôle
    if (!isset($validatedData['role'])) {
        \Log::error('Role manquant dans les données validées');
    } else {
        \Log::info('Rôle à appliquer: ' . $validatedData['role']);
        \Log::info('Rôle actuel: ' . $utilisateur->role);
    }
    
    // Conversion de la checkbox
    $isActive = $request->has('is_active') ? 1 : 0;
    \Log::info('Statut is_active: ' . $isActive);
    
    // Mise à jour MANUELLE pour debug
    $utilisateur->nom = $validatedData['nom'];
    $utilisateur->prenom = $validatedData['prenom'];
    $utilisateur->email = $validatedData['email'];
    $utilisateur->matricule = $validatedData['matricule'];
    $utilisateur->direction = $validatedData['direction'];
    $utilisateur->poste = $validatedData['poste'];
    $utilisateur->telephone = $validatedData['telephone'];
    $utilisateur->role = $validatedData['role']; // Ligne cruciale
    $utilisateur->date_embauche = $validatedData['date_embauche'];
    $utilisateur->is_active = $isActive;
    
    \Log::info('Données à sauvegarder:', $utilisateur->toArray());
    
    $saved = $utilisateur->save();
    
    \Log::info('Résultat de la sauvegarde: ' . ($saved ? 'SUCCÈS' : 'ÉCHEC'));
    
    // Recharger depuis la base
    $utilisateur->refresh();
    \Log::info('Utilisateur après sauvegarde:', $utilisateur->toArray());
    
    \Log::info('=== FIN MISE À JOUR UTILISATEUR ===');
    
    if ($saved) {
        return redirect()->route('admin.utilisateurs.index')
                         ->with('success', 'Utilisateur mis à jour avec succès.');
    } else {
        return redirect()->back()
                         ->with('error', 'Erreur lors de la mise à jour de l\'utilisateur.')
                         ->withInput();
    }
}

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.utilisateurs.index')->with('success', 'Utilisateur supprimé.');
    
    
    }
public function desactiver($id)
{
    try {
        $user = User::findOrFail($id);
        
        // Empêcher l'auto-désactivation
        if (Auth::id() == $user->id) {
            return redirect()->route('admin.utilisateurs.index')
                             ->with('error', 'Vous ne pouvez pas désactiver votre propre compte.');
        }
        
        // Empêcher la désactivation du dernier admin actif
        if ($user->role === 'admin' && User::where('role', 'admin')->active()->count() <= 1) {
            return redirect()->route('admin.utilisateurs.index')
                             ->with('error', 'Impossible de désactiver le dernier administrateur actif.');
        }
        
        $user->update(['is_active' => false]);
        
        \Log::info('Utilisateur désactivé: ' . $user->email . ' par ' . Auth::user()->email);
        
        return redirect()->route('admin.utilisateurs.index')
                         ->with('success', 'Utilisateur désactivé avec succès.');
                         
    } catch (\Exception $e) {
        \Log::error('Erreur désactivation utilisateur: ' . $e->getMessage());
        return redirect()->route('admin.utilisateurs.index')
                         ->with('error', 'Erreur lors de la désactivation.');
    }
}
//Fonction pour reactiver le compte 
public function reactiver($id)
{
    try {
        $user = User::findOrFail($id);
        
        $user->update(['is_active' => true]);
        
        \Log::info('Utilisateur réactivé: ' . $user->email . ' par ' . Auth::user()->email);
        
        return redirect()->route('admin.utilisateurs.index')
                         ->with('success', 'Utilisateur réactivé avec succès.');
                         
    } catch (\Exception $e) {
        \Log::error('Erreur réactivation utilisateur: ' . $e->getMessage());
        return redirect()->route('admin.utilisateurs.index')
                         ->with('error', 'Erreur lors de la réactivation.');
    }
}
    
}
