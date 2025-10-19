<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\CongeService;
use App\Models\User;

class InitialiserQuotasAnnuels extends Command
{
    protected $signature = 'conges:init-quotas {annee?} {--force}';
    
    protected $description = 'Initialise les quotas de congés pour tous les agents pour une année donnée';

    public function handle()
    {
        $annee = $this->argument('annee') ?? date('Y');
        $force = $this->option('force');

        if (!$force) {
            if (!$this->confirm("Voulez-vous initialiser les quotas pour l'année {$annee} ?")) {
                $this->info('Opération annulée.');
                return 0;
            }
        }

        $this->info("Initialisation des quotas pour l'année {$annee}...");

        $congeService = new CongeService();
        
        try {
            $congeService->initialiserQuotasAnnuels($annee);
            
            $nbAgents = User::where('role', 'agent')->count();
            $nbTypesConges = count(config('conges.types'));
            
            $this->info("✅ Quotas initialisés avec succès !");
            $this->info("📊 {$nbAgents} agents × {$nbTypesConges} types de congés");
            $this->info("📅 Année : {$annee}");
            
        } catch (\Exception $e) {
            $this->error("❌ Erreur lors de l'initialisation : " . $e->getMessage());
            return 1;
        }

        return 0;
    }
}