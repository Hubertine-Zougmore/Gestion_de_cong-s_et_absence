<?php

namespace App\Services;

use App\Models\User;
use App\Models\Demande;
use App\Models\QuotaUtilisateur;
use Carbon\Carbon;

class CongeService
{
    /**
     * Obtenir les quotas d'un utilisateur pour une année donnée
     */
    public function getQuotasUtilisateur(User $user, int $annee = null)
    {
        $annee = $annee ?? date('Y');
        $typesConges = config('conges.types');
        $quotas = [];

        foreach ($typesConges as $type => $config) {
            $quota = QuotaUtilisateur::firstOrCreate(
                [
                    'user_id' => $user->id,
                    'type_conge' => $type,
                    'annee' => $annee,
                ],
                [
                    'jours_disponibles' => $config['quota_jours'] ?? 0,
                    'jours_utilises' => 0,
                ]
            );

            $quotas[$type] = $quota;
        }

        return $quotas;
    }

    /**
     * Calculer le nombre de jours ouvrables entre deux dates
     */
    public function calculerJoursOuvrables($dateDebut, $dateFin)
    {
        $debut = Carbon::parse($dateDebut);
        $fin = Carbon::parse($dateFin);
        
        $jours = 0;
        $current = $debut->copy();

        while ($current->lte($fin)) {
            // Exclure les weekends (samedi = 6, dimanche = 0)
            if (!in_array($current->dayOfWeek, [0, 6])) {
                $jours++;
            }
            $current->addDay();
        }

        return $jours;
    }

    /**
     * Vérifier si une demande est possible
     */
    public function verifierDisponibilite(User $user, string $typeConge, $dateDebut, $dateFin, int $annee = null)
    {
        $annee = $annee ?? date('Y');
        $nombreJours = $this->calculerJoursOuvrables($dateDebut, $dateFin);
        
        // Obtenir le quota de l'utilisateur
        $quota = QuotaUtilisateur::where('user_id', $user->id)
                                ->where('type_conge', $typeConge)
                                ->where('annee', $annee)
                                ->first();

        if (!$quota) {
            // Créer le quota s'il n'existe pas
            $configType = config("conges.types.{$typeConge}");
            $quota = QuotaUtilisateur::create([
                'user_id' => $user->id,
                'type_conge' => $typeConge,
                'annee' => $annee,
                'jours_disponibles' => $configType['quota_jours'] ?? 0,
                'jours_utilises' => 0,
            ]);
        }

        $joursRestants = $quota->joursRestants();

        return [
            'possible' => $joursRestants >= $nombreJours,
            'jours_demandes' => $nombreJours,
            'jours_disponibles' => $quota->jours_disponibles,
            'jours_utilises' => $quota->jours_utilises,
            'jours_restants' => $joursRestants,
            'quota' => $quota,
        ];
    }

    /**
     * Enregistrer une demande et mettre à jour les quotas
     */
    public function enregistrerDemande(User $user, array $data)
    {
        $verification = $this->verifierDisponibilite(
            $user,
            $data['type_conge'],
            $data['date_debut'],
            $data['date_fin']
        );

        if (!$verification['possible']) {
            throw new \Exception(
                "Quota insuffisant. Vous avez {$verification['jours_restants']} jours restants, " .
                "mais vous demandez {$verification['jours_demandes']} jours."
            );
        }

        // Créer la demande
        $demande = Demande::create([
            'user_id' => $user->id,
            'type_conge' => $data['type_conge'],
            'date_debut' => $data['date_debut'],
            'date_fin' => $data['date_fin'],
            'motif' => $data['motif'] ?? null,
            'nombre_jours_demandes' => $verification['jours_demandes'],
            'annee_conge' => date('Y'),
            'statut' => 'en_attente',
        ]);

        // Réserver temporairement les jours (sera confirmé à l'approbation)
        // Note: On peut choisir de réserver immédiatement ou seulement à l'approbation
        
        return $demande;
    }

    /**
     * Approuver une demande et décrémenter le quota
     */
    public function approuverDemande(Demande $demande)
    {
        if ($demande->statut !== 'en_attente') {
            throw new \Exception('Cette demande a déjà été traitée.');
        }

        $quota = QuotaUtilisateur::where('user_id', $demande->user_id)
                                ->where('type_conge', $demande->type_conge)
                                ->where('annee', $demande->annee_conge)
                                ->first();

        if ($quota) {
            $quota->increment('jours_utilises', $demande->nombre_jours_demandes);
        }

        $demande->update(['statut' => 'approuve']);

        return $demande;
    }

    /**
     * Rejeter une demande (libérer la réservation si applicable)
     */
    public function rejeterDemande(Demande $demande, string $motifRejet = null)
    {
        $demande->update([
            'statut' => 'rejete',
            'motif_rejet' => $motifRejet,
        ]);

        return $demande;
    }

    /**
     * Obtenir un résumé des congés pour un utilisateur
     */
    public function getResumeCongés(User $user, int $annee = null)
    {
        $annee = $annee ?? date('Y');
        $quotas = $this->getQuotasUtilisateur($user, $annee);
        $typesConges = config('conges.types');

        $resume = [];

        foreach ($quotas as $type => $quota) {
            $config = $typesConges[$type];
            
            $resume[$type] = [
                'nom' => $config['nom'],
                'quota_total' => $quota->jours_disponibles,
                'jours_utilises' => $quota->jours_utilises,
                'jours_restants' => $quota->joursRestants(),
                'pourcentage_utilise' => $quota->pourcentageUtilise(),
                'config' => $config,
            ];
        }

        return $resume;
    }

    /**
     * Initialiser les quotas pour tous les utilisateurs pour une nouvelle année
     */
    public function initialiserQuotasAnnuels(int $annee)
    {
        $users = User::where('role', 'agent')->get();
        $typesConges = config('conges.types');

        foreach ($users as $user) {
            foreach ($typesConges as $type => $config) {
                if ($config['renouvelable'] && $config['periode_renouvellement'] === 'annuel') {
                    QuotaUtilisateur::updateOrCreate(
                        [
                            'user_id' => $user->id,
                            'type_conge' => $type,
                            'annee' => $annee,
                        ],
                        [
                            'jours_disponibles' => $config['quota_jours'] ?? 0,
                            'jours_utilises' => 0,
                        ]
                    );
                }
            }
        }
    }
}