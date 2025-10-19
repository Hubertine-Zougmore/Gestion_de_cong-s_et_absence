<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Demande;

class CheckDRHPermissions
{
    public function handle(Request $request, Closure $next)
    {
        $demandeId = $request->route('demande');
        $demande = $demandeId instanceof Demande ? $demandeId : Demande::find($demandeId);
        
        if (!$demande) {
            return redirect()->back()->with('error', 'Demande introuvable.');
        }
        
        // 1. Ne peut pas traiter ses propres demandes
        if ($demande->user_id === Auth::id()) {
            return redirect()->back()->with('error', 'Vous ne pouvez pas traiter votre propre demande.');
        }
        
        // 2. Vérification des permissions métier
        $estDeMaDirection = $demande->user->departement === Auth::user()->departement;
        $estUrgent = $demande->created_at->diffInHours(now()) < 72;
        $peutTraiter = $estUrgent || !$estDeMaDirection;
        
        if (!$peutTraiter) {
            return redirect()->back()->with('error', 
                'Vous ne pouvez traiter que les demandes de votre direction de moins de 72h.');
        }
        
        return $next($request);
    }
}