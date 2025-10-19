<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Services\CongeService;

class CongesTestSeeder extends Seeder
{
    public function run()
    {
        $congeService = new CongeService();
        
        // Créer des utilisateurs de test s'ils n'existent pas
        $agent1 = User::firstOrCreate([
            'email' => 'agent1@test.com'
        ], [
            'name' => 'Agent Test 1',
            'role' => 'agent',
            'password' => bcrypt('password'),
        ]);

        $agent2 = User::firstOrCreate([
            'email' => 'agent2@test.com'
        ], [
            'name' => 'Agent Test 2', 
            'role' => 'agent',
            'password' => bcrypt('password'),
        ]);

        // Initialiser les quotas pour ces agents
        $congeService->initialiserQuotasAnnuels(date('Y'));
        
        // Créer quelques demandes de test
        try {
            $congeService->enregistrerDemande($agent1, [
                'type_conge' => 'conge_annuel',
                'date_debut' => date('Y-m-d', strtotime('+1 week')),
                'date_fin' => date('Y-m-d', strtotime('+1 week +4 days')),
                'motif' => 'Vacances d\'été',
            ]);

            $congeService->enregistrerDemande($agent2, [
                'type_conge' => 'autorisation_absence',
                'date_debut' => date('Y-m-d', strtotime('+2 weeks')),
                'date_fin' => date('Y-m-d', strtotime('+2 weeks +1 day')),
                'motif' => 'Rendez-vous médical',
            ]);

            $this->command->info('✅ Données de test créées avec succès !');
            $this->command->info('👤 Agents créés : agent1@test.com, agent2@test.com');
            $this->command->info('🔑 Mot de passe : password');
            
        } catch (\Exception $e) {
            $this->command->error('❌ Erreur : ' . $e->getMessage());
        }
    }
}