<?php
namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Le chemin vers la page d'accueil.
     */
    public const HOME = '/dashboard';

    /**
     * Définition des routes.
     */
    public function boot()
    {
          parent::boot();

    Route::bind('utilisateur', function ($value) {
        return App\Models\User::find($value) ?? abort(404);
    });
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::prefix('api')
                ->middleware('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }

    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });
    }

    /**
     * Redirige selon le rôle de l'utilisateur.
     */
    public static function redirectTo()
    {
        $user = Auth::user();
        
        if (!$user) {
            return '/login';
        }

        return match($user->role) {
            'admin' => '/admin/dashboard',
            'agent' => '/agent/dashboard',
            default => '/user/dashboard'
        };
    }
}