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
        'number_scope_unit_id', 'sppd_date', 'spt_id', 'user_id', 'signed_by_user_id', 'assignment_title',
        'trip_type', 'funding_source',
    ];

    public function spt() { return $this->belongsTo(Spt::class); }
    public function user() { return $this->belongsTo(User::class); }
    public function signedByUser() { return $this->belongsTo(User::class, 'signed_by_user_id'); }
    // Accessor methods untuk origin place dan destination city
    public function getOriginPlaceAttribute()
    {
        return $this->spt?->notaDinas?->originPlace;
    }
    
    public function getDestinationCityAttribute()
    {
        return $this->spt?->notaDinas?->destinationCity;
    }
    public function transportModes() { return $this->belongsToMany(TransportMode::class, 'sppd_transport_modes'); }
    public function itineraries() { return $this->hasMany(SppdItinerary::class); }
    public function divisumSignoffs() { return $this->hasMany(SppdDivisumSignoff::class); }
    public function receipt() { return $this->hasOne(Receipt::class); }
    public function receipts() { return $this->hasMany(Receipt::class); }
    // TripReport terkait dengan SPT, bukan SPPD langsung
    // public function tripReport() { return $this->hasOne(TripReport::class); }
}
