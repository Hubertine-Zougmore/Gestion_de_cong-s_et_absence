<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckResponsible
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter.');
        }

        $user = Auth::user();
        
        // Vérifie si l'utilisateur est responsable OU DRH
        if ($user->role === 'responsible' || $user->role === 'drh') {
            return $next($request);
        }
        
        return redirect()->route('home')->with('error', 'Accès réservé aux responsables.');
    }
}