<?php
// app/Models/LeaveSetting.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveSetting extends Model
{
    protected $fillable = [
        'code',         // Identifiant unique (ex: 'annual_leave_days')
        'category',     // 'period', 'quota', 'notification'
        'type',         // 'integer', 'boolean', 'date', 'month_list'
        'value',        // Valeur sérialisée
        'description'   // Description claire
    ];

    protected $casts = [
        'value' => 'array' // Sérialisation automatique
    ];

    public static function getSetting($code, $default = null)
    {
        $setting = static::where('code', $code)->first();
        return $setting ? $setting->value : $default;
    }
}