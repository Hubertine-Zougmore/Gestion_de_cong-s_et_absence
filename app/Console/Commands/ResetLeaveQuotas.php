<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ResetLeaveQuotas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:reset-leave-quotas';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
{
    User::query()->update([
        'conge_annuel_restant' => DB::raw('conge_annuel_total'),
        'absence_restante' => DB::raw('absence_total'),
        'maternite_restante' => DB::raw('maternite_total')
    ]);
    
    $this->info('Quotas de congés réinitialisés pour tous les utilisateurs');
}
}
