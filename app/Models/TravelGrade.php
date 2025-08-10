<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class TravelGrade extends Model
{
    protected $fillable = [
        'code',
        'name',
    ];

    protected $casts = [
        'code' => 'string',
        'name' => 'string',
    ];

    public function userMaps(): HasMany
    {
        return $this->hasMany(UserTravelGradeMap::class);
    }

    public function users(): HasManyThrough
    {
        return $this->hasManyThrough(User::class, UserTravelGradeMap::class);
    }

    public function getDisplayNameAttribute(): string
    {
        return "{$this->name} ({$this->code})";
    }
}
