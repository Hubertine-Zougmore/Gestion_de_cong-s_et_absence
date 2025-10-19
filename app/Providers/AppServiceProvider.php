<?php

namespace App\Providers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
class AppServiceProvider extends ServiceProvider
{
    
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
  
        //
        // App\Providers\AppServiceProvider.php


public function boot()
{
    View::composer('*', function ($view) {
        if (auth()->check()) {
            
            $view->with('notifications', auth()->user()->notifications);
        }
    });
}

        
         //Paginator::defaultView('vendor.pagination.default');
    //Paginator::defaultSimpleView('vendor.pagination.simple-default');
    
    protected $policies = [
    Demande::class => DemandePolicy::class,
];
}
