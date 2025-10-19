<?php

// Model SystemSetting
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    protected $fillable = ['key', 'value', 'type', 'category', 'description'];

    public static function getValue($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        
        if (!$setting) {
            return $default;
        }

        return match($setting->type) {
            'boolean' => (bool) $setting->value,
            'number' => (int) $setting->value,
            'json' => json_decode($setting->value, true),
            default => $setting->value,
        };
    }

    public static function setValue($key, $value, $type = 'text', $category = 'general')
    {
        $value = is_array($value) ? json_encode($value) : $value;
        
        return self::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type,
                'category' => $category
            ]
        );
    }
}

// Model LeaveType
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveType extends Model
{
    protected $fillable = [
        'name', 'code', 'description', 'max_days_per_year',
        'min_days_request', 'max_days_request', 'requires_approval',
        'is_paid', 'is_active', 'allowed_months', 'advance_notice_days'
    ];

    protected $casts = [
        'requires_approval' => 'boolean',
        'is_paid' => 'boolean',
        'is_active' => 'boolean',
        'allowed_months' => 'array',
    ];

    public function userQuotas()
    {
        return $this->hasMany(UserLeaveQuota::class);
    }

    public function isAllowedInMonth($month)
    {
        if (!$this->allowed_months) {
            return true; // Si aucune restriction, tous les mois sont autorisés
        }

        return in_array($month, $this->allowed_months);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}

// Model UserLeaveQuota
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserLeaveQuota extends Model
{
    protected $fillable = [
        'user_id', 'leave_type_id', 'allocated_days',
        'used_days', 'pending_days', 'year'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class);
    }

    public function getRemainingDaysAttribute()
    {
        return $this->allocated_days - $this->used_days - $this->pending_days;
    }

    public static function getOrCreateQuota($userId, $leaveTypeId, $year = null)
    {
        $year = $year ?? date('Y');
        
        return self::firstOrCreate([
            'user_id' => $userId,
            'leave_type_id' => $leaveTypeId,
            'year' => $year
        ], [
            'allocated_days' => LeaveType::find($leaveTypeId)->max_days_per_year
        ]);
    }
}

// Model LeavePeriod
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class LeavePeriod extends Model
{
    protected $fillable = [
        'name', 'start_date', 'end_date', 'is_active', 'description'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeCurrent($query)
    {
        $today = Carbon::today();
        return $query->where('start_date', '<=', $today)
                    ->where('end_date', '>=', $today);
    }

    public function isCurrentPeriod()
    {
        $today = Carbon::today();
        return $this->start_date <= $today && $this->end_date >= $today;
    }
}

// Model ScheduledNotification
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ScheduledNotification extends Model
{
    protected $fillable = [
        'title', 'message', 'type', 'send_date',
        'send_time', 'is_sent', 'recipient_roles'
    ];

    protected $casts = [
        'send_date' => 'date',
        'send_time' => 'datetime:H:i',
        'is_sent' => 'boolean',
        'recipient_roles' => 'array',
    ];

    public function scopePending($query)
    {
        return $query->where('is_sent', false);
    }

    public function scopeReadyToSend($query)
    {
        $now = Carbon::now();
        return $query->where('is_sent', false)
                    ->where('send_date', '<=', $now->format('Y-m-d'))
                    ->where('send_time', '<=', $now->format('H:i:s'));
    }

    public function markAsSent()
    {
        $this->update(['is_sent' => true]);
    }
}