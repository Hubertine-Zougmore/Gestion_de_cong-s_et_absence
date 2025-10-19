<?php
// app/Models/Parametre.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Parametre extends Model
{
    protected $fillable = [
        'code',         // Identifiant unique (ex: 'mois_conges_autorises')
        'valeur',       // Valeur sérialisée (ex: "juillet,aout,decembre")
        //'type',         // boolean/integer/string/date/array
        'description'   // Description lisible
    ];

    protected $casts = [
        'valeur' => 'array' // Conversion automatique si JSON
    ];

    // Méthode d'accès universelle
    public static function get($code, $default = null)
    {
        $param = self::where('code', $code)->first();
        return $param ? $this->castValue($param->valeur, $param->type) : $default;
    }

    protected function castValue($value, $type)
    {
        return match($type) {
            'boolean' => (bool) $value,
            'integer' => (int) $value,
            'array' => json_decode($value, true),
            default => $value
        };
        
    }
    // Dans le modèle Parametre
public static function getCached($code, $default = null)
{
    return Cache::rememberForever("param_{$code}", function() use ($code, $default) {
        return self::get($code, $default);
    });
}
}