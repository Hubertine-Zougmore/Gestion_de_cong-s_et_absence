<?php

namespace App\Http\Controllers;

use App\Services\LeaveValidationService;

class LeaveController extends Controller
{
    public function __construct(
        private LeaveValidationService $leaveService
    ) {}

    public function store()
    {
        try {
            $this->leaveService->validateLeavePeriod('july');
            $days = $this->leaveService->getAnnualLeaveDays();
            // ...
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}