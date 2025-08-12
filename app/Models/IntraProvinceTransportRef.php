<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IntraProvinceTransportRef extends Model
{
    use HasFactory;

    protected $fillable = [
        'origin_place_id',
        'destination_city_id',
        'pp_amount',
        'valid_from',
        'valid_to',
        'source_ref',
    ];

    protected $casts = [
        'origin_place_id' => 'integer',
        'destination_city_id' => 'integer',
        'pp_amount' => 'decimal:2',
        'valid_from' => 'date',
        'valid_to' => 'date',
        'source_ref' => 'string',
    ];

    public function originPlace(): BelongsTo
    {
        return $this->belongsTo(OrgPlace::class, 'origin_place_id');
    }

    public function destinationCity(): BelongsTo
    {
        return $this->belongsTo(City::class, 'destination_city_id');
    }

    public function getDisplayNameAttribute(): string
    {
        $origin = $this->originPlace->name ?? 'Tempat Kerja Tidak Ditemukan';
        $destination = $this->destinationCity->name ?? 'Kota Tujuan Tidak Ditemukan';
        return "{$origin} → {$destination}";
    }

    public function getFormattedPpAmountAttribute(): string
    {
        return 'Rp ' . number_format($this->pp_amount, 0, ',', '.');
    }

    public function getValidityStatusAttribute(): string
    {
        $today = now()->toDateString();
        
        if ($this->valid_from > $today) {
            return 'FUTURE';
        } elseif ($this->valid_to && $this->valid_to < $today) {
            return 'EXPIRED';
        } else {
            return 'ACTIVE';
        }
    }

    public function getValidityStatusTextAttribute(): string
    {
        return match($this->validity_status) {
            'FUTURE' => 'Akan Berlaku',
            'EXPIRED' => 'Kadaluarsa',
            'ACTIVE' => 'Berlaku',
            default => 'Tidak Diketahui'
        };
    }

    public function getValidityStatusColorAttribute(): string
    {
        return match($this->validity_status) {
            'FUTURE' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
            'EXPIRED' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
            'ACTIVE' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
            default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'
        };
    }

    public function getValidityPeriodAttribute(): string
    {
        $from = $this->valid_from->format('d/m/Y');
        
        if ($this->valid_to) {
            $to = $this->valid_to->format('d/m/Y');
            return "{$from} - {$to}";
        } else {
            return "{$from} - Sekarang";
        }
    }
}
