<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Spt extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'spt';

    protected $fillable = [
        'doc_no', 'number_is_manual', 'number_manual_reason', 'number_format_id', 'number_sequence_id', 'number_scope_unit_id',
        'nota_dinas_id', 'spt_date', 'signed_by_user_id', 'assignment_title',
        'origin_place_id', 'destination_city_id', 'start_date', 'end_date', 'days_count', 'funding_source', 'status',
        'notes',
    ];

    public function notaDinas() { return $this->belongsTo(NotaDinas::class); }
    public function signedByUser() { return $this->belongsTo(User::class, 'signed_by_user_id'); }
    public function originPlace() { return $this->belongsTo(OrgPlace::class, 'origin_place_id'); }
    public function destinationCity() { return $this->belongsTo(City::class, 'destination_city_id'); }
    public function members() { return $this->hasMany(SptMember::class); }
    public function tripReport() { return $this->hasOne(TripReport::class); }
    public function sppds() { return $this->hasMany(Sppd::class); }
    
    // Helper method untuk mendapatkan peserta dari Nota Dinas
    public function getParticipants()
    {
        return $this->notaDinas?->participants ?? collect();
    }

    // Accessor untuk place_from (menggunakan origin_place)
    public function getPlaceFromAttribute()
    {
        return $this->originPlace?->name ?? '';
    }

    // Accessor untuk place_to (menggunakan destination_city)
    public function getPlaceToAttribute()
    {
        return $this->destinationCity?->name ?? '';
    }

    // Accessor untuk depart_date (menggunakan start_date)
    public function getDepartDateAttribute()
    {
        return $this->start_date;
    }

    // Accessor untuk return_date (menggunakan end_date)
    public function getReturnDateAttribute()
    {
        return $this->end_date;
    }
}
