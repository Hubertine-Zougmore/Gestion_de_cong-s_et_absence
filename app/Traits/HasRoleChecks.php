<?php

namespace App\Traits;

trait HasRoleChecks
{
    /**
     * Vérifie si l'utilisateur a le rôle requis
     */
    public function hasRole($role)
    {
        return auth()->check() && auth()->user()->role === $role;
    }

    /**
     * Vérifie si l'utilisateur a l'un des rôles requis
     */
    public function hasAnyRole($roles)
    {
        if (!auth()->check()) return false;
        
        if (is_array($roles)) {
            return in_array(auth()->user()->role, $roles);
        }
        
        return auth()->user()->role === $roles;
    }

    /**
     * Redirige l'utilisateur vers son dashboard approprié
     */
    public function redirectToRoleDashboard()
    {
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
    }
}