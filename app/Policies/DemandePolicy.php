<?php

namespace App\Policies;

use App\Models\Demande;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DemandePolicy
{
    use HandlesAuthorization;

    /**
     * Déterminer si l'utilisateur peut voir la demande
     */
    public function view(User $user, Demande $demande)
    {
        // L'agent peut voir sa propre demande
        // OU son responsable/DRH peut la voir
        return $user->id === $demande->user_id 
            || $user->role === 'responsable_hierarchique' 
            || $user->role === 'drh';
    }

    /**
     * Déterminer si l'utilisateur peut modifier la demande
     */
    public function update(User $user, Demande $demande)
    {
        // Seul le propriétaire peut modifier et seulement si pas encore traitée
        return $user->id === $demande->user_id 
            && !in_array($demande->statut, ['approuve', 'rejete']);
    }

    /**
     * Déterminer si l'utilisateur peut supprimer la demande
     */
    public function delete(User $user, Demande $demande)
    {
        // Seul le propriétaire peut supprimer et seulement si pas encore traitée
        return $user->id === $demande->user_id 
            && !in_array($demande->statut, ['approuve', 'rejete']);
    }
}