<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SecretaireGeneralMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Vérifier si l'utilisateur est authentifié
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Vérifier si l'utilisateur a le rôle secretaire_general
        if (auth()->user()->role !== 'secretaire_general') {
            abort(403, 'Accès réservé au Secrétaire Général');
        }

        return $next($request);
    }
}