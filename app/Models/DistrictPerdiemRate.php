<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DistrictPerdiemRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'org_place_name',
        'district_id',
        'unit',
        'daily_rate',
        'is_active',
    ];

    protected $casts = [
        'daily_rate' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get the district that owns the perdiem rate.
     */
    public function district()
    {
        return $this->belongsTo(District::class);
    }

    /**
     * Get the display name attribute.
     */
    public function getDisplayNameAttribute()
    {
        return "{$this->org_place_name} - {$this->district->name}";
    }

    /**
     * Scope a query to only include active rates.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
