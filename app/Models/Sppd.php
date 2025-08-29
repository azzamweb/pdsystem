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
        'funding_source',
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

    // Accessor untuk trip_type (mengambil dari NotaDinas)
    public function getTripTypeAttribute()
    {
        return $this->spt?->notaDinas?->trip_type ?? 'LUAR_DAERAH';
    }
    public function transportModes() { return $this->belongsToMany(TransportMode::class, 'sppd_transport_modes'); }
    public function itineraries() { return $this->hasMany(SppdItinerary::class); }
    public function divisumSignoffs() { return $this->hasMany(SppdDivisumSignoff::class); }
    public function receipt() { return $this->hasOne(Receipt::class); }
    public function receipts() { return $this->hasMany(Receipt::class); }
    // TripReport terkait dengan SPT, bukan SPPD langsung
    // public function tripReport() { return $this->hasOne(TripReport::class); }

    /**
     * Get snapshot data for user from Nota Dinas
     */
    public function getUserSnapshot()
    {
        if (!$this->spt?->notaDinas) {
            return null;
        }

        // Find the participant in Nota Dinas that matches this SPPD user
        $participant = $this->spt->notaDinas->participants
            ->where('user_id', $this->user_id)
            ->first();

        if ($participant) {
            return [
                'name' => $participant->user_name_snapshot ?: $participant->user?->name,
                'gelar_depan' => $participant->user_gelar_depan_snapshot ?: $participant->user?->gelar_depan,
                'gelar_belakang' => $participant->user_gelar_belakang_snapshot ?: $participant->user?->gelar_belakang,
                'nip' => $participant->user_nip_snapshot ?: $participant->user?->nip,
                'unit_id' => $participant->user_unit_id_snapshot ?: $participant->user?->unit_id,
                'unit_name' => $participant->user_unit_name_snapshot ?: $participant->user?->unit?->name,
                'position_id' => $participant->user_position_id_snapshot ?: $participant->user?->position_id,
                'position_name' => $participant->user_position_name_snapshot ?: $participant->user?->position?->name,
                'position_desc' => $participant->user_position_desc_snapshot ?: $participant->user?->position_desc,
                'rank_id' => $participant->user_rank_id_snapshot ?: $participant->user?->rank_id,
                'rank_name' => $participant->user_rank_name_snapshot ?: $participant->user?->rank?->name,
                'rank_code' => $participant->user_rank_code_snapshot ?: $participant->user?->rank?->code,
            ];
        }

        return null;
    }

    /**
     * Get snapshot data for signed_by_user from Nota Dinas
     */
    public function getSignedByUserSnapshot()
    {
        if (!$this->spt?->notaDinas) {
            return null;
        }

        return [
            'name' => $this->spt->notaDinas->from_user_name_snapshot ?: $this->spt->notaDinas->fromUser?->name,
            'gelar_depan' => $this->spt->notaDinas->from_user_gelar_depan_snapshot ?: $this->spt->notaDinas->fromUser?->gelar_depan,
            'gelar_belakang' => $this->spt->notaDinas->from_user_gelar_belakang_snapshot ?: $this->spt->notaDinas->fromUser?->gelar_belakang,
            'nip' => $this->spt->notaDinas->from_user_nip_snapshot ?: $this->spt->notaDinas->fromUser?->nip,
            'unit_id' => $this->spt->notaDinas->from_user_unit_id_snapshot ?: $this->spt->notaDinas->fromUser?->unit_id,
            'unit_name' => $this->spt->notaDinas->from_user_unit_name_snapshot ?: $this->spt->notaDinas->fromUser?->unit?->name,
            'position_id' => $this->spt->notaDinas->from_user_position_id_snapshot ?: $this->spt->notaDinas->fromUser?->position_id,
            'position_name' => $this->spt->notaDinas->from_user_position_name_snapshot ?: $this->spt->notaDinas->fromUser?->position?->name,
            'position_desc' => $this->spt->notaDinas->from_user_position_desc_snapshot ?: $this->spt->notaDinas->fromUser?->position_desc,
            'rank_id' => $this->spt->notaDinas->from_user_rank_id_snapshot ?: $this->spt->notaDinas->fromUser?->rank_id,
            'rank_name' => $this->spt->notaDinas->from_user_rank_name_snapshot ?: $this->spt->notaDinas->fromUser?->rank?->name,
            'rank_code' => $this->spt->notaDinas->from_user_rank_code_snapshot ?: $this->spt->notaDinas->fromUser?->rank?->code,
        ];
    }
}
