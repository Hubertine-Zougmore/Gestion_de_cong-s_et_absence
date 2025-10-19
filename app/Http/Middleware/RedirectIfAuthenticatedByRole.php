<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticatedByRole
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $role = Auth::user()->role;

           return match ($role) {
    'admin' => redirect()->route('admin.dashboard'),
    'agent' => redirect()->route('agent.dashboard'),
    'drh' => redirect()->route('drh.dashboard'),
    'sg' => redirect()->route('sg.dashboard'),
    'president' => redirect()->route('president.dashboard'),
    'responsable' => redirect()->route('responsable.dashboard'),
    default => redirect('/home'),
};

        }

        return $next($request);
    }
}
