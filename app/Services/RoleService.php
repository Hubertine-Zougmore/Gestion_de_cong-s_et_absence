<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;

class RoleService
{
    public function hasRole($role)
    {
        return Auth::check() && Auth::user()->role === $role;
    }

    public function redirectToAppropriateDashboard()
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }

        switch ($user->role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'drh':
                return redirect()->route('drh.dashboard');
            case 'responsable_hierarchique':
                return redirect()->route('responsable.dashboard');
            case 'agent':
                return redirect()->route('agent.dashboard');
            default:
                return redirect()->route('home');
        }
    }

    public function checkAccess($requiredRole)
    {
        if (!$this->hasRole($requiredRole)) {
            return $this->redirectToAppropriateDashboard()
                       ->with('error', 'Accès non autorisé.');
        }
        
        return true;
    }
}