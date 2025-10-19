<?php
namespace App\Http\Controllers;

use App\Http\Requests\StoreLeaveRequest;
use App\Services\LeaveValidationService;
use App\Services\LeaveService;

class LeaveController extends Controller
{
    public function __construct(
        private LeaveService $leaveService
    ) {}

    public function store(StoreLeaveRequest $request, LeaveValidationService $service)
    {
        $availableDays = $service->getAvailableDays();
        $requestedDays ='' /* calcul des jours demandés */;
        
        if ($requestedDays > $availableDays) {
            abort(422, "Solde insuffisant. Jours disponibles : $availableDays");
        }
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'days' => 'required|integer|min:1',
            'reason' => 'nullable|string'
        ]);

       try {
    $leave = app(LeaveService::class)->requestLeave(
        auth()->user(),
        $request->validated()
    );
    return response()->json($leave, 201);
} catch (Exception $e) {
    return response()->json([
        'error' => $e->getMessage()
    ], 400);
}
        }
    }
