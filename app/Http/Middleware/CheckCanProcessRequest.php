<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Demande;

class CheckCanProcessRequest
{
    public function handle(Request $request, Closure $next)
    {
                // Récupérer l'ID de la demande depuis la route
        $demandeId = $request->route('demande');
        
        // Si c'est un objet Demande (route model binding)
        if ($demandeId instanceof Demande) {
            $demande = $demandeId;
        } else {
            // Si c'est un ID, chercher la demande
            $demande = Demande::find($demandeId);
            
            if (!$demande) {
                return redirect()->back()
                    ->with('error', 'Demande introuvable.');
            }
        }
        
        // Empêcher de traiter sa propre demande
        if ($demande->user_id === Auth::id()) {
            return redirect()->back()
                ->with('error', 'Vous ne pouvez pas traiter votre propre demande.');
        }
        
        return $next($request);
    }
}