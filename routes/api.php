<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LdapLoginController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Routes d'authentification LDAP
Route::prefix('auth')->group(function () {
    Route::post('/login', [LdapLoginController::class, 'login']);
    Route::post('/logout', [LdapLoginController::class, 'logout'])->middleware('auth:sanctum');
    Route::get('/test-ldap', [LdapLoginController::class, 'testLdapConnection']);
});

// Routes protégées
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    // Vos routes existantes pour la gestion des congés
    // Route::resource('users', UserController::class);
    // Route::resource('conges', CongeController::class);
    // etc.
});