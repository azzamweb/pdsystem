<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Province extends Model
{
    protected $fillable = [
        'kemendagri_code',
        'name',
    ];

    /**
     * Get cities in this province
     */
    public function cities(): HasMany
    {
        return $this->hasMany(City::class);
    }

    /**
     * Get full name with code
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->name} ({$this->kemendagri_code})";
    }
}
