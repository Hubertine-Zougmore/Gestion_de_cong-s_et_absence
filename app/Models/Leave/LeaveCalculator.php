<?php
namespace App\Models\Leave;

class LeaveCalculator
{
    public static function calculateRemainingDays($userId, $year)
    {
        return LeaveSetting::getSetting('annual_leave_days') 
             - LeaveRequest::where('user_id', $userId)
                           ->whereYear('start_date', $year)
                           ->sum('days_taken');
    }
}