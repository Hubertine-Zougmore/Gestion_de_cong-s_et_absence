<?php
namespace App\Models\Leave;

use Carbon\Carbon;

class LeaveValidator
{
    public static function validatePeriod(string $startDate): void
    {
        $allowedMonths = config('leave.allowed_months'); // Ou via LeaveSetting
        
        $month = Carbon::parse($startDate)->format('F');
        
        if (!in_array(strtolower($month), $allowedMonths)) {
            throw new \InvalidArgumentException(
                "Les congés ne sont autorisés qu'aux mois de: "
                . implode(', ', $allowedMonths)
            );
        }
    }
}