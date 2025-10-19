<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\AdminDashboardController;  // Import correct
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AgentDashboardController;
use App\Http\Controllers\Admin\StatistiquesController;
use App\Http\Controllers\DemandeController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\DrhController;
use App\Http\Controllers\SgDashboardController;
use App\Http\Controllers\PresidentDashboardController;
use App\Http\Controllers\SecretaireGeneralController;
use App\Http\Controllers\ResponsableController;
use App\Http\Controllers\RapportController;
use App\Http\Controllers\DashboardController;
// Page d'accueil
Route::get('/', [AuthController::class, 'showWelcome'])->name('welcome');
//  les routes de vérification email pour lr frofil
Auth::routes(['verify' => true]);
// Routes d'authentification
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');


// Route pour le dashboard agent avec vérification manuelle
Route::get('/agent/dashboard', function () {
    if (auth()->check() && auth()->user()->role === 'agent') {
        return view('agent.dashboard');
    }
    
    return redirect()->route('home')->with('error', 'Accès non autorisé.');
})->name('agent.dashboard');
Route::post('/agent/demandes/store', [DemandeController::class, 'store'])->name('agent.demandes.store');


// Route pour le dashboard admin
Route::get('/admin/dashboard', function () {
    if (auth()->check() && auth()->user()->role === 'admin') {
        return view('admin.dashboard');
    }
    
    return redirect()->route('home')->with('error', 'Accès non autorisé.');
})->name('admin.dashboard');


Route::get('/dashboard', function () {
    if (!auth()->check()) {
        return redirect()->route('login');
    }
    $user = auth()->user();
    
    switch ($user->role) {
        case 'admin':
            return redirect()->route('admin.dashboard');
        case 'drh':
            return redirect()->route('drh.dashboard');
        case 'responsable_hierarchique':
            return redirect()->route('responsable.dashboard');
        case 'agent':
            return redirect()->route('agent.dashboard');
        case 'secretaire_general':
            return redirect()->route('sg.dashboard');
        case 'president':
            return redirect()->route('president.dashboard');
        default:
            return redirect()->route('home');
    }
})->middleware('auth')->name('dashboard');
 

// Routes des demandes
Route::middleware(['auth'])->group(function () {
    Route::resource('demandes', DemandeController::class);
    Route::get('/demandes/{demande}/download-justificatif', [DemandeController::class, 'downloadJustificatif'])
     ->name('demandes.downloadJustificatif');
    Route::get('/demandes/{id}/view-justificatif', [DemandeController::class, 'viewJustificatif'])
     ->name('demandes.viewJustificatif');
      Route::post('/demandes', [DemandeController::class, 'store'])->name('demandes.store');
});
Route::get('/amin/dashboard', [AdminDashboardController::class, 'dashboard'])->name('amin.dashboard');
// Routes admin
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/statistiques', [StatistiquesController::class, 'index'])->name('statistiques');
    Route::resource('utilisateurs', \App\Http\Controllers\Admin\AdminUserController::class);
    
});

// Gestion des utilisateurs
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/utilisateurs', [AdminUserController::class, 'index'])->name('admin.utilisateurs.index');
    Route::get('/utilisateurs/create', [AdminUserController::class, 'create'])->name('admin.utilisateurs.create');
    Route::post('/utilisateurs', [AdminUserController::class, 'store'])->name('admin.utilisateurs.store');
    Route::get('/utilisateurs/{utilisateur}/edit', [AdminUserController::class, 'edit'])->name('admin.utilisateurs.edit');
    Route::put('/utilisateurs/{id}', [AdminUserController::class, 'update'])->name('admin.utilisateurs.update');
   Route::delete('utilisateurs/{user}', [AdminUserController::class, 'destroy'])->name('admin.utilisateurs.destroy');
    Route::patch('/utilisateurs/{id}/activer', [AdminUserController::class, 'activer'])->name('admin.utilisateurs.activer');
    Route::patch('/utilisateurs/{id}/desactiver', [AdminUserController::class, 'desactiver'])->name('admin.utilisateurs.desactiver');
    Route::patch('/utilisateurs/{id}/reactiver', [AdminUserController::class, 'reactiver'])->name('admin.utilisateurs.reactiver');
});

// Planning
use App\Http\Controllers\Admin\PlanningController;
Route::get('/admin/planning', [PlanningController::class, 'index'])->name('admin.planning');

// Paramètres
use App\Http\Controllers\ParametreController;
Route::middleware(['auth'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::resource('parametres', ParametreController::class);
    });

// Paramètres généraux
Route::get('/parametres', function () {
    return view('parametres.index');
})->name('parametres')->middleware('auth');
Route::get('/parametres', [ParametresController::class, 'index'])->name('paramètres');
// Note: Le nom "paramètres" contient un accent grave

Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
//Pour la route du tableau de bord 
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
//Route pour les paramètres
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Paramètres
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings/general', [SettingsController::class, 'updateGeneralSettings'])->name('settings.general');
    
    // Types de congés
    Route::post('/leave-types', [SettingsController::class, 'storeLeaveType'])->name('leave-types.store');
    Route::put('/leave-types/{leaveType}', [SettingsController::class, 'updateLeaveType'])->name('leave-types.update');
    
    // Quotas utilisateurs
    Route::get('/user-quotas', [SettingsController::class, 'manageUserQuotas'])->name('user-quotas');
    Route::post('/user-quotas', [SettingsController::class, 'updateUserQuota'])->name('user-quotas.update');
    Route::post('/bulk-quotas', [SettingsController::class, 'bulkAssignQuotas'])->name('bulk-quotas');
    
    // Périodes et notifications
    Route::post('/leave-periods', [SettingsController::class, 'storeLeavePeriod'])->name('leave-periods.store');
Route::post('/notifications', [SettingsController::class, 'storeNotification'])->name('notifications.store');
    Route::get('/execution-dashboard', [SettingsController::class, 'executionDashboard'])->name('execution-dashboard');
});
//pour definir les mois et jours
Route::middleware('auth')->group(function() {
    Route::apiResource('leaves', LeaveController::class);
    Route::get('leaves/remaining-days', [LeaveController::class, 'remainingDays']);
});
use App\Http\Controllers\UserController;
Route::get('/users', [UserController::class, 'index']);

use App\Http\Controllers\Auth\VerificationController;

// Routes de vérification d'email
Route::middleware(['auth'])->group(function () {
    Route::get('/email/verify', [VerificationController::class, 'show'])
        ->name('verification.notice');
        
    Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])
        ->middleware('signed')
        ->name('verification.verify');
        
    Route::post('/email/verification-notification', [VerificationController::class, 'send'])
        ->middleware('throttle:6,1')
        ->name('verification.send'); // Route manquante
});  
//Pour le profil
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});     

Route::middleware(['auth'])->group(function () {
    Route::resource('demandes', DemandeController::class);
    
    // Routes pour les administrateurs
    Route::middleware(['admin'])->group(function () {
        Route::patch('demandes/{demande}/approve', [DemandeController::class, 'approve'])->name('demandes.approve');
        Route::patch('demandes/{demande}/reject', [DemandeController::class, 'reject'])->name('demandes.reject');
    });
});
// --- ROUTES COMMUNES (pour tous les rôles) ---
Route::middleware(['auth'])->group(function () {
    // Demandes (COMMUN à tous)
    Route::get('/demandes', [DemandeController::class, 'index'])->name('demandes.index');
    Route::get('/demandes/create', [DemandeController::class, 'create'])->name('demandes.create');
    Route::post('/demandes', [DemandeController::class, 'store'])->name('demandes.store');
    Route::get('/demandes/{demande}', [DemandeController::class, 'show'])->name('demandes.show');
    Route::get('/demandes/{demande}/edit', [DemandeController::class, 'edit'])->name('demandes.edit');
    Route::put('/demandes/{demande}', [DemandeController::class, 'update'])->name('demandes.update');
    Route::delete('/demandes/{demande}', [DemandeController::class, 'destroy'])->name('demandes.destroy');
});

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Routes protégées par le middleware 'drh'
Route::middleware(['auth'])->group(function () {
    
    // Tableau de bord DRH
    Route::get('/drh/dashboard', [DrhController::class, 'dashboard'])->name('drh.dashboard');
    
    // Planning des congés
    //Route::get('/drh/planning', [DrhController::class, 'planning'])->name('drh.planning');
    
    // Gestion des demandes
    Route::get('/drh/demande/{demande}', [DrhController::class, 'showDemande'])->name('drh.demande-show');
    Route::post('/drh/demande/{demande}/traiter', [DrhController::class, 'traiterDemande'])->name('drh.demande.traiter');
    
    // Demandes personnelles du DRH
    Route::get('/drh/mes-demandes', [DrhController::class, 'mesDemandes'])->name('drh.mes-demandes.index');
    Route::get('/drh/mes-demandes/create', [DrhController::class, 'createDemande'])->name('drh.mes-demandes.create');
    Route::get('/drh/mes-demandes/{demande}/edit', [DemandeController::class, 'edit'])->name('drh.mes-demandes.edit');
    Route::get('/mes-demandes', [DrhController::class, 'mesDemandes'])->name('drh.mes-demandes');
    Route::get('/toutes-demandes', [DrhController::class, 'toutesLesDemandes'])->name('drh.toutes-demandes');
    //Route::get('/drh/mes-demandes/{demande}/show', [DrhController::class, 'showDemande'])->name('drh.mes-demandes.show');
    Route::put('/drh/mes-demandes/{demande}/update', [DrhController::class, 'updateDemande'])->name('drh.mes-demandes.update');
    Route::delete('/drh/mes-demandes/{demande}/delete', [DrhController::class, 'deleteDemande'])->name('drh.mes-demandes.delete');
    // Export des données
    Route::get('/drh/export/demandes', [DrhController::class, 'exportDemandes'])->name('drh.export.demandes');
    Route::get('/drh/export/planning', [DrhController::class, 'exportPlanning'])->name('drh.export.planning');
    
    // Statistiques et rapports
    Route::get('/drh/statistiques', [DrhController::class, 'statistiques'])->name('drh.statistiques');
    Route::get('/drh/rapports', [DrhController::class, 'rapports'])->name('drh.rapports');
    Route::post('/drh/demandes/{demande}/approuver', [DrhController::class, 'approuverDemande'])->name('drh.demande.approuver');
    Route::post('/drh/demandes/{demande}/rejeter', [DrhController::class, 'rejeterDemande'])->name('drh.demande.rejeter');
    

});

// Route de fallback pour l'accès non autorisé
Route::get('/drh/dashboard/fallback', function () {
    if (auth()->check() && auth()->user()->role === 'drh') {
        return redirect()->route('drh.dashboard');
    }
    return redirect()->route('home')->with('error', 'Accès non autorisé.');
})->name('drh.dashboard.fallback');
// AJOUTEZ CETTE ROUTE :
 Route::get('/drh/rapports', [DrhController::class, 'rapports'])->name('drh.rapports');
    
////////////////////////////////////////////////////////////////////////////////////////////////
Route::middleware(['auth'])->group(function () {
    Route::get('/secretaire_general/dashboard', [SecretaireGeneralController::class, 'dashboard'])->name('secretaire_general.dashboard');
    Route::get('/secretaire_general/demande/{demande}', [SecretaireGeneralController::class, 'showDemande'])->name('secretaire_general.demande-show');
    Route::post('/demandes/{demande}/traiter', [SecretaireGeneralController::class, 'traiterDemande'])->name('demande.traiter');
    Route::post('/demande/{demande}/approuver', [SecretaireGeneralController::class, 'approuverDemande'])->name('secretaire_general.demande.approuver');
    Route::post('/demande/{demande}/rejeter', [SecretaireGeneralController::class, 'rejeterDemande'])->name('secretaire_general.demande.rejeter');
    
});

    /////////////////////////////////////////////////////////////////////////////////////:
// Routes pour les AGENTS
Route::prefix('agent')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', [AgentDashboardController::class, 'dashboard'])->name('agent.dashboard');
   // Route::get('/mes-demandes', [AgentDashboardController::class, 'mesDemandes'])->name('agent.mes-demandes.index');
   // Route::get('/mes-demandes/create', [AgentDashboardController::class, 'createDemande'])->name('agent.mes-demandes.create');
    Route::post('/mes-demandes', [AgentDashboardController::class, 'storeDemande'])->name('agent.mes-demandes.store');
    Route::get('/mes-demandes/{demande}', [AgentDashboardController::class, 'showDemande'])->name('agent.mes-demandes.show');
    Route::get('/mes-demandes/{demande}/edit', [AgentDashboardController::class, 'editDemande'])->name('agent.mes-demandes.edit');
    Route::put('/mes-demandes/{demande}', [AgentDashboardController::class, 'updateDemande'])->name('agent.mes-demandes.update');
    Route::delete('/mes-demandes/{demande}', [AgentDashboardController::class, 'deleteDemande'])->name('agent.mes-demandes.delete');
});
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Routes pour les RESPONSABLES
 Route::prefix('responsable')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', [ResponsableController::class, 'dashboard'])->name('responsable.dashboard');
    Route::get('/demandes', [ResponsableController::class, 'toutesLesDemandes'])->name('responsable.demandes.index');
    Route::get('/demandes/{demande}', [ResponsableController::class, 'showDemande'])->name('responsable.demandes-show');
    Route::post('/demandes/{demande}/approuver', [ResponsableController::class, 'approuver'])->name('responsable.demandes.approuver');
    Route::post('/demandes/{demande}/rejeter', [ResponsableController::class, 'rejeter'])->name('responsable.demandes.rejeter');
    
    // Demandes personnelles du responsable
    Route::get('/mes-demandes', [ResponsableController::class, 'mesDemandes'])->name('responsable.mes-demandes');
    Route::get('/mes-demandes/create', [ResponsableController::class, 'createDemande'])->name('responsable.mes-demandes.create');
    // ... autres routes personnelles
});
/////////////////////////////////////////////////President/////////////////////////////////////////////////////////////////////////////////////////////////////

// CORRECTION COMPLÈTE DES ROUTES
Route::middleware(['auth'])->group(function () {
    
    // Tableau de bord
    Route::get('/president/dashboard', [PresidentDashboardController::class, 'dashboard'])->name('president.dashboard');
    
    // Gestion des demandes - CORRIGÉ
    Route::get('/president/demandes/{demande}', [PresidentDashboardController::class, 'showDemande'])->name('president.demandes-show');
    
    // Traitement des demandes - CORRIGÉ
    Route::post('/president/demandes/{demande}/traiter', [PresidentDashboardController::class, 'traiterDemande'])->name('president.demandes.traiter');
     Route::post('/demandes/{demande}/approuver', [PresidentDashboardController::class, 'approuver'])->name('president.demande.approuver');
    Route::post('/demandes/{demande}/rejeter', [PresidentDashboardController::class, 'rejeter'])->name('president.demande.rejeter');
});
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// routes/web.php



// Alternative si vous préférez juste utiliser auth (la vérification se fait dans le contrôleur)

Route::middleware(['auth'])->prefix('secretaire_general')->name('secretaire_general.')->group(function () {
    Route::get('/dashboard', [SecretaireGeneralController::class, 'dashboard'])->name('secretaire_general.dashboard');
    Route::get('/demandes/{demande}', [SecretaireGeneralController::class, 'showDemande'])->name('demandes.show');
    Route::post('/demandes/{demande}/traiter', [SecretaireGeneralController::class, 'traiterDemande'])->name('demandes.traiter');
    Route::get('/employes', [SecretaireGeneralController::class, 'gestionEmployes'])->name('employes');
    Route::get('/employes/{user}/quotas', [SecretaireGeneralController::class, 'voirQuotasEmploye'])->name('employes.quotas');
    Route::get('/statistiques', [SecretaireGeneralController::class, 'statistiquesAvancees'])->name('statistiques');
    Route::get('/rapports/export-demandes', [SecretaireGeneralController::class, 'exporterDemandes'])->name('rapports.export-demandes');
    Route::get('/rapports/export-employes', [SecretaireGeneralController::class, 'exporterEmployes'])->name('rapports.export-employes');
});
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

Route::middleware(['auth'])->group(function () {
    Route::prefix('rapports')->group(function () {
        Route::get('/', [RapportController::class, 'index'])->name('rapports.index');
        Route::get('/create', [RapportController::class, 'create'])->name('rapports.create');
        Route::post('/', [RapportController::class, 'store'])->name('rapports.store');
        Route::get('/{rapport}', [RapportController::class, 'show'])->name('rapports.show');
        Route::get('/{rapport}/pdf', [RapportController::class, 'pdf'])->name('rapports.pdf');
        Route::delete('/{rapport}', [RapportController::class, 'destroy'])->name('rapports.destroy');
        Route::get('/rapports/{rapport}/excel', [RapportController::class, 'excel'])->name('rapports.excel');
    });
});
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

////////////////////////////////Notifications/////////////////////////////////////////////////////////////////////////////////
Route::get('/notifications', function () {
    return view('notifications.index', [
        'notifications' => auth()->user()->notifications
    ]);
})->name('notifications.index');
Route::post('/notifications/{id}/read', function ($id) {
    $notification = auth()->user()->notifications()->findOrFail($id);
    $notification->markAsRead();
    return response()->json(['success' => true]);
})->name('notifications.read');

use App\Http\Controllers\NotificationController;

Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
// Dans web.php ou responsable.php
Route::get('/notifications/non-lues', [ResponsableHierarchiqueController::class, 'getNotificationsNonLues'])
    ->name('responsable.notifications.non-lues');
    
Route::get('/notifications/count', [ResponsableHierarchiqueController::class, 'getNombreNotificationsNonLues'])
    ->name('responsable.notifications.count');
    
Route::post('/notifications/{id}/lue', [ResponsableHierarchiqueController::class, 'marquerNotificationCommeLue'])
    ->name('responsable.notifications.marquer-lue');
    
Route::post('/notifications/toutes-lues', [ResponsableHierarchiqueController::class, 'marquerToutesNotificationsCommeLues'])
    ->name('responsable.notifications.toutes-lues');
Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');

    Route::middleware(['auth'])->group(function () {
    // page du formulaire
    Route::get('/notifications/form', [NotificationController::class, 'form'])
        ->name('notifications.form');

    // traitement de l’envoi
    Route::post('/notifications/envoyer', [NotificationController::class, 'envoyerATous'])
        ->name('notifications.envoyer');
});
    /////////////////////////////////////joinssance////////////////////////////////////////////////////////////
    // Demandes de jouissance (pour responsables hiérarchiques)
Route::middleware(['auth'])->group(function () {
    Route::get('/jouissance', [DemandeJouissanceController::class, 'index'])->name('jouissance.index');
    Route::get('/jouissance/{demandeJouissance}', [DemandeJouissanceController::class, 'show'])->name('jouissance.show');
    Route::post('/jouissance/{demandeJouissance}/approuver', [DemandeJouissanceController::class, 'approuver'])->name('jouissance.approuver');
    Route::post('/jouissance/{demandeJouissance}/rejeter', [DemandeJouissanceController::class, 'rejeter'])->name('jouissance.rejeter');
});
