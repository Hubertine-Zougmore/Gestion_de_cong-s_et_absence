<?php

// app/Console/Kernel.php - Configuration du planificateur de tâches
namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Les commandes Artisan fournies par l'application.
     */
    protected $commands = [
        Commands\SendScheduledNotifications::class,
        Commands\CreateLeaveReminders::class,
        Commands\UpdateLeaveQuotas::class,
        Commands\GenerateLeaveReports::class,
    ];

    /**
     * Définir le planning des commandes de l'application.
     */
    protected function schedule(Schedule $schedule)
    {
        // Envoyer les notifications programmées toutes les heures
        $schedule->command('notifications:send')
                 ->hourly()
                 ->withoutOverlapping()
                 ->runInBackground();

        // Créer des rappels automatiques pour les périodes de congés
        $schedule->command('leave:create-reminders')
                 ->daily()
                 ->at('08:00')
                 ->withoutOverlapping();

        // Mettre à jour les quotas en début d'année
        $schedule->command('leave:update-quotas')
                 ->yearlyOn(1, 1, '00:01')
                 ->withoutOverlapping();

        // Générer des rapports mensuels
        $schedule->command('leave:generate-reports')
                 ->monthlyOn(1, '09:00')
                 ->withoutOverlapping();

        // Nettoyer les notifications anciennes
        $schedule->command('leave:cleanup-notifications')
                 ->weekly()
                 ->sundays()
                 ->at('02:00');
                 $schedule->command('reset:leave-quotas')->yearlyOn(1, 1); // 1er janvier
    }
    

}