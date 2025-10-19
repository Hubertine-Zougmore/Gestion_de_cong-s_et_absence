<?php

namespace App\Policies;

use App\Models\Rapport;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class RapportPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // DRH et President peuvent voir les rapports
        return $user->hasRole(['drh', 'president']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Rapport $rapport): bool
    {
        // DRH peut voir ses propres rapports
        if ($user->hasRole('drh') && $rapport->redige_par === $user->id) {
            return true;
        }
        
        // President peut voir les rapports finalisés/validés
        if ($user->hasRole('president') && in_array($rapport->statut, ['finalise', 'envoye'])) {
            return true;
        }
        
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Seul le DRH peut créer des rapports
        return $user->hasRole('drh');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Rapport $rapport): bool
    {
        // DRH peut modifier seulement ses propres brouillons
        return $user->hasRole('drh') && 
               $rapport->redige_par === $user->id && 
               $rapport->statut === 'brouillon';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Rapport $rapport): bool
    {
        // DRH peut supprimer seulement ses propres brouillons
        return $user->hasRole('drh') && 
               $rapport->redige_par === $user->id && 
               $rapport->statut === 'brouillon';
    }

    /**
     * Determine whether the user can validate reports.
     */
    public function validateReport(User $user, Rapport $rapport): bool
    {
        // Seul le président peut valider les rapports finalisés
        return $user->hasRole('president') && $rapport->statut === 'finalise';
    }

    /**
     * Determine whether the user can send back for revision.
     */
    public function sendForRevision(User $user, Rapport $rapport): bool
    {
        // Seul le président peut renvoyer en révision
        return $user->hasRole('president') && $rapport->statut === 'finalise';
    }
}