<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RepresentativeRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'province_id',
        'travel_grade_id',
        'rate_amount',
        'description',
        'valid_from',
        'valid_to',
        'source_ref',
    ];

    protected $casts = [
        'rate_amount' => 'decimal:2',
        'valid_from' => 'date',
        'valid_to' => 'date',
    ];

    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }

    public function travelGrade(): BelongsTo
    {
        return $this->belongsTo(TravelGrade::class);
    }

    public function getDisplayNameAttribute(): string
    {
        $province = $this->province->name ?? 'Provinsi Tidak Ditemukan';
        $grade = $this->travelGrade->name ?? 'Tingkatan Tidak Ditemukan';
        return "Representasi {$grade} - {$province}";
    }

    public function getRouteDisplayAttribute(): string
    {
        $province = $this->province->name ?? 'Provinsi Tidak Ditemukan';
        $grade = $this->travelGrade->name ?? 'Tingkatan Tidak Ditemukan';
        return "Representasi {$grade} - {$province}";
    }

    public function getStatusAttribute(): string
    {
        $today = now()->toDateString();
        
        if ($this->valid_from > $today) {
            return 'FUTURE';
        }
        
        if ($this->valid_to && $this->valid_to < $today) {
            return 'EXPIRED';
        }
        
        return 'ACTIVE';
    }

    public function getStatusTextAttribute(): string
    {
        return match($this->status) {
            'ACTIVE' => 'Berlaku',
            'EXPIRED' => 'Kadaluarsa',
            'FUTURE' => 'Akan Berlaku',
            default => 'Tidak Diketahui'
        };
    }
}
