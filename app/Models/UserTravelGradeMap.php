<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserTravelGradeMap extends Model
{
    protected $fillable = [
        'user_id',
        'travel_grade_id',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'travel_grade_id' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function travelGrade(): BelongsTo
    {
        return $this->belongsTo(TravelGrade::class);
    }
}
