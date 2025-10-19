<?php

namespace App\Services;

use App\Models\Leave\LeaveValidator;
use App\Models\Leave\LeaveCalculator;
use App\Models\LeaveRequest;
use Illuminate\Support\Facades\DB;
use Exception;

class LeaveService
{
    public function requestLeave($user, array $data)
    {
        // Validation des données d'entrée
        $this->validateLeaveData($data);

        return DB::transaction(function() use ($user, $data) {
            // Calcul des jours disponibles
            $remainingDays = LeaveCalculator::calculateRemainingDays(
                $user->id,
                now()->year
            );

            // Vérification du solde
            $this->checkAvailableDays($data['days'], $remainingDays);

            // Création de la demande
            return LeaveRequest::create([
                'user_id'    => $user->id,
                'start_date' => $data['start_date'],
                'end_date'   => $data['end_date'],
                'days'       => $data['days'],
                'status'     => 'pending',
                'reason'     => $data['reason'] ?? null
            ]);
        });
    }

    /**
     * Valide les données de la demande de congé
     */
    protected function validateLeaveData(array $data): void
    {
        LeaveValidator::validatePeriod($data['start_date']);

        if ($data['days'] <= 0) {
            throw new Exception("Le nombre de jours doit être positif");
        }
    }

    /**
     * Vérifie le solde disponible
     */
    protected function checkAvailableDays(int $requestedDays, int $availableDays): void
    {
        if ($requestedDays > $availableDays) {
            throw new Exception(
                "Solde insuffisant. Jours disponibles: $availableDays"
            );
        }
    }
}