<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Direction extends Model
{
        protected $table = 'directions';

    protected $fillable = ['nom', 'code', 'description'];
    
     public function users(): HasMany
    {
        return $this->hasMany(User::class, 'direction_id');
    }
    /**
     * Scope pour une recherche rapide
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('nom', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
    }
        /**
     * Accesseur pour le code complet
     */
    public function getCodeCompletAttribute()
    {
        return "UTS-" . $this->code;
    }

}