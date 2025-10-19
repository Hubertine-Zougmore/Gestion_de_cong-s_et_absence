<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Demande; // Si vous avez ce modèle

class AdminDashboardController extends Controller
{
     protected $roleService;

     //RoleService dans l controller
    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }
  use HasRoleChecks;

    public function adminDashboard()
    {
        //RoleService dans l controller
$accessCheck = $this->roleService->checkAccess('agent');
        
        if ($accessCheck !== true) {
            return $accessCheck;
        }
        
        if (!$this->hasRole('admin')) {
            return $this->redirectToRoleDashboard()
                       ->with('error', 'Accès non autorisé.');
        }
         if (Gate::denies('access-admin-dashboard')) {
        return redirect()->route('home')
                         ->with('error', 'Accès non autorisé.');
    }

    return view('admin.dashboard', [
        'user' => Auth::user(),
        'dernieresDemandes' => collect()
    ]);


        // Récupérer l'utilisateur connecté
        $user = Auth::user();
        
        // Récupérer les 5 dernières demandes (si le modèle existe)
        $dernieresDemandes = collect(); // Collection vide pour l'instant
        // ou si vous avez le modèle : $dernieresDemandes = Demande::latest()->take(5)->get();
   
        return view('admin.dashboard', compact('user', 'dernieresDemandes'));
    }
    
public function index()
{
    // Récupère les 10 dernières demandes avec les relations utilisateur
    $dernieresDemandes = Demande::with('user')
                                ->latest()
                                ->take(10)
                                ->get();

    return view('Admin.dashboard', compact('dernieresDemandes'));
}
}