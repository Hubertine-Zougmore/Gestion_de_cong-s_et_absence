<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Vérifier que l'utilisateur est authentifié
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Si aucun rôle spécifié, laisser passer
        if (empty($roles)) {
            return $next($request);
        }

        // Vérifier si l'utilisateur a l'un des rôles requis
        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        // Si l'utilisateur n'a pas le bon rôle, le rediriger vers son dashboard approprié
        return $this->redirectToUserDashboard($user->role);
    }

    /**
     * Rediriger l'utilisateur vers son dashboard selon son rôle
     *
     * @param string $role
     * @return \Illuminate\Http\RedirectResponse
     */
    private function redirectToUserDashboard($role)
    {
        switch ($role) {
            case 'admin':
                return redirect()->route('admin.dashboard')
                    ->with('warning', 'Vous avez été redirigé vers votre espace.');
            case 'drh':
                return redirect()->route('drh.dashboard')
                    ->with('warning', 'Vous avez été redirigé vers votre espace.');
            case 'responsable_hierarchique':
                return redirect()->route('responsable.dashboard')
                    ->with('warning', 'Vous avez été redirigé vers votre espace.');
            case 'agent':
                return redirect()->route('agent.dashboard')
                    ->with('warning', 'Vous avez été redirigé vers votre espace.');
            case 'secretaire_general':
                return redirect()->route('sg.dashboard')
                    ->with('warning', 'Vous avez été redirigé vers votre espace.');
            case 'president':
                return redirect()->route('president.dashboard')
                    ->with('warning', 'Vous avez été redirigé vers votre espace.');
            default:
                return redirect()->route('home')
                    ->with('error', 'Accès non autorisé.');
        }
    }
}