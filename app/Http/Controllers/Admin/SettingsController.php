<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SystemSetting;
use App\Models\LeaveType;
use App\Models\LeavePeriod;
use App\Models\ScheduledNotification;
use App\Models\UserLeaveQuota;
use App\Models\User;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    // ================================
    // GESTION DES PARAMÈTRES GÉNÉRAUX
    // ================================
    
    public function index()
    {
        $settings = SystemSetting::all()->groupBy('category');
        $leaveTypes = LeaveType::all();
        $leavePeriods = LeavePeriod::all();
        $notifications = ScheduledNotification::latest()->get();
        
        return view('admin.settings.index', compact(
            'settings', 'leaveTypes', 'leavePeriods', 'notifications'
        ));
    }

    public function updateGeneralSettings(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'max_leave_days_per_year' => 'required|integer|min:1',
            'advance_notice_days' => 'required|integer|min:1',
            'auto_approve_requests' => 'boolean',
            'weekend_included' => 'boolean',
        ]);

        SystemSetting::setValue('company_name', $request->company_name);
        SystemSetting::setValue('max_leave_days_per_year', $request->max_leave_days_per_year, 'number');
        SystemSetting::setValue('advance_notice_days', $request->advance_notice_days, 'number');
        SystemSetting::setValue('auto_approve_requests', $request->has('auto_approve_requests'), 'boolean');
        SystemSetting::setValue('weekend_included', $request->has('weekend_included'), 'boolean');

        return back()->with('success', 'Paramètres généraux mis à jour avec succès.');
    }

    // ===============================
    // GESTION DES TYPES DE CONGÉS
    // ===============================
    
    public function storeLeaveType(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:leave_types,code',
            'max_days_per_year' => 'required|integer|min:1',
            'min_days_request' => 'required|integer|min:1',
            'max_days_request' => 'nullable|integer|min:1',
            'advance_notice_days' => 'required|integer|min:0',
            'allowed_months' => 'array',
        ]);

        LeaveType::create([
            'name' => $request->name,
            'code' => $request->code,
            'description' => $request->description,
            'max_days_per_year' => $request->max_days_per_year,
            'min_days_request' => $request->min_days_request,
            'max_days_request' => $request->max_days_request,
            'advance_notice_days' => $request->advance_notice_days,
            'requires_approval' => $request->has('requires_approval'),
            'is_paid' => $request->has('is_paid'),
            'allowed_months' => $request->allowed_months,
        ]);

        return back()->with('success', 'Type de congé créé avec succès.');
    }

    public function updateLeaveType(Request $request, LeaveType $leaveType)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:leave_types,code,' . $leaveType->id,
            'max_days_per_year' => 'required|integer|min:1',
            'min_days_request' => 'required|integer|min:1',
            'max_days_request' => 'nullable|integer|min:1',
            'advance_notice_days' => 'required|integer|min:0',
            'allowed_months' => 'array',
        ]);

        $leaveType->update([
            'name' => $request->name,
            'code' => $request->code,
            'description' => $request->description,
            'max_days_per_year' => $request->max_days_per_year,
            'min_days_request' => $request->min_days_request,
            'max_days_request' => $request->max_days_request,
            'advance_notice_days' => $request->advance_notice_days,
            'requires_approval' => $request->has('requires_approval'),
            'is_paid' => $request->has('is_paid'),
            'allowed_months' => $request->allowed_months,
            'is_active' => $request->has('is_active'),
        ]);

        return back()->with('success', 'Type de congé mis à jour avec succès.');
    }

    // ==================================
    // GESTION DES QUOTAS UTILISATEURS
    // ==================================
    
    public function manageUserQuotas()
    {
        $users = User::role('agent')->with('leaveQuotas.leaveType')->get();
        $leaveTypes = LeaveType::active()->get();
        $currentYear = date('Y');
        
        return view('admin.settings.user-quotas', compact('users', 'leaveTypes', 'currentYear'));
    }

    public function updateUserQuota(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'leave_type_id' => 'required|exists:leave_types,id',
            'allocated_days' => 'required|integer|min:0',
            'year' => 'required|integer|min:2020|max:2030',
        ]);

        UserLeaveQuota::updateOrCreate([
            'user_id' => $request->user_id,
            'leave_type_id' => $request->leave_type_id,
            'year' => $request->year,
        ], [
            'allocated_days' => $request->allocated_days,
        ]);

        return back()->with('success', 'Quota utilisateur mis à jour avec succès.');
    }

    public function bulkAssignQuotas(Request $request)
    {
        $request->validate([
            'leave_type_id' => 'required|exists:leave_types,id',
            'allocated_days' => 'required|integer|min:0',
            'year' => 'required|integer|min:2020|max:2030',
        ]);

        $users = User::role('agent')->get();
        
        foreach ($users as $user) {
            UserLeaveQuota::updateOrCreate([
                'user_id' => $user->id,
                'leave_type_id' => $request->leave_type_id,
                'year' => $request->year,
            ], [
                'allocated_days' => $request->allocated_days,
            ]);
        }

        return back()->with('success', 'Quotas assignés à tous les agents avec succès.');
    }

    // ===================================
    // GESTION DES PÉRIODES DE CONGÉS
    // ===================================
    
    public function storeLeavePeriod(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        LeavePeriod::create([
            'name' => $request->name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'description' => $request->description,
        ]);

        return back()->with('success', 'Période de congés créée avec succès.');
    }

    // ===============================
    // GESTION DES NOTIFICATIONS
    // ===============================
    
    public function storeNotification(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:reminder,deadline,approval',
            'send_date' => 'required|date|after_or_equal:today',
            'send_time' => 'required',
            'recipient_roles' => 'required|array',
        ]);

        ScheduledNotification::create([
            'title' => $request->title,
            'message' => $request->message,
            'type' => $request->type,
            'send_date' => $request->send_date,
            'send_time' => $request->send_time,
            'recipient_roles' => $request->recipient_roles,
        ]);

        return back()->with('success', 'Notification programmée avec succès.');
    }

    // ===============================
    // TABLEAU DE BORD D'EXÉCUTION
    // ===============================
    
    public function executionDashboard()
    {
        $stats = [
            'total_employees' => User::role('agent')->count(),
            'active_leave_types' => LeaveType::active()->count(),
            'pending_requests' => 0, // À adapter selon votre modèle de demandes
            'approved_this_month' => 0, // À adapter
            'current_period' => LeavePeriod::active()->current()->first(),
            'pending_notifications' => ScheduledNotification::pending()->count(),
        ];

        $leaveUsage = UserLeaveQuota::with(['user', 'leaveType'])
            ->where('year', date('Y'))
            ->get()
            ->groupBy('leave_type.name')
            ->map(function ($quotas) {
                return [
                    'allocated' => $quotas->sum('allocated_days'),
                    'used' => $quotas->sum('used_days'),
                    'pending' => $quotas->sum('pending_days'),
                ];
            });

        return view('admin.settings.execution-dashboard', compact('stats', 'leaveUsage'));
    }
}