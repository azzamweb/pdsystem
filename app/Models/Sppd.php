<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sppd extends Model
{
    use HasFactory, SoftDeletes;

    // Explicitly map to singular table name
    protected $table = 'sppd';

    protected $fillable = [
        'doc_no', 'number_is_manual', 'number_manual_reason', 'number_format_id', 'number_sequence_id',
        'number_scope_unit_id', 'sppd_date', 'spt_id', 'user_id', 'origin_place_id', 'destination_city_id',
        'transport_mode_id', 'trip_type', 'start_date', 'end_date', 'days_count', 'funding_source', 'status',
    ];

    public function spt() { return $this->belongsTo(Spt::class); }
    public function user() { return $this->belongsTo(User::class); }
    public function originPlace() { return $this->belongsTo(OrgPlace::class, 'origin_place_id'); }
    public function destinationCity() { return $this->belongsTo(City::class, 'destination_city_id'); }
    public function transportMode() { return $this->belongsTo(TransportMode::class, 'transport_mode_id'); }
    public function itineraries() { return $this->hasMany(SppdItinerary::class); }
    public function divisumSignoffs() { return $this->hasMany(SppdDivisumSignoff::class); }
    public function receipt() { return $this->hasOne(Receipt::class); }
}
