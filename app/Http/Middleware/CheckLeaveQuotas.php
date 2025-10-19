<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckLeaveQuotas
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
   public function handle($request, Closure $next)
{
    $user = Auth::user();
    $jours = calculateDays($request->date_debut, $request->date_fin);
    
    if ($request->type == 'conge_annuel' && !$user->isCongeAnnuelPeriod()) {
        return redirect()->back()
            ->with('error', 'Les congés annuels ne sont autorisés qu\'en août et septembre');
    }
    
    return $next($request);
}
}
