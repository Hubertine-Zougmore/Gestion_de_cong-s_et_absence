<?php

// app/Http/Middleware/RedirectBasedOnRole.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectBasedOnRole
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            if ($user->role === 'agent' && $request->is('dashboard')) {
                return redirect('/agent/dashboard');
            }
            
            if ($user->role === 'admin' && $request->is('dashboard')) {
                return redirect('/admin/dashboard');
            }
        }
        
        return $next($request);
    }
}