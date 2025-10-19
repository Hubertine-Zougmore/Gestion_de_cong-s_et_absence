<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ServiceServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
        $this->app->bind(LeaveValidationService::class, function () {
    return new LeaveValidationService();
});
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
