<?php

namespace App\Http\Middleware;

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class ShareNotifications
{
    public function handle($request, Closure $next)
    {
        $notifications = collect();

        if (Auth::check()) {
            $notifications = Auth::user()->notifications;
        }

        View::share('notifications', $notifications);

        return $next($request);
    }
}

