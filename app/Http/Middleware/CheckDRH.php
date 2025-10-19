<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckDRH
{
    public function handle(Request $request, Closure $next)
    {
        // Debug: vérifiez si l'utilisateur est connecté
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter.');
            
        }

        // Debug: vérifiez le rôle de l'utilisateur
        $user = Auth::user();
        \Log::info('User role: ' . $user->role);
        \Log::info('User ID: ' . $user->id);

        if ($user->role === 'drh') {
            return $next($request);
        }
        
        \Log::warning('Accès refusé pour l\'utilisateur: ' . $user->id . ' - Rôle: ' . $user->role);
        return redirect()->route('home')->with('error', 'Accès réservé aux DRH.');
    }
}