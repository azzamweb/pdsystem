<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NotaDinas extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'doc_no', 'number_is_manual', 'number_manual_reason', 'number_format_id', 'number_sequence_id',
        'number_scope_unit_id', 'to_user_id', 'from_user_id', 'tembusan', 'nd_date',
        'sifat', 'lampiran_count', 'hal', 'dasar', 'maksud', 'destination_city_id', 'start_date',
        'end_date', 'days_count', 'requesting_unit_id', 'status', 'created_by',
        'approved_by', 'approved_at', 'notes',
    ];

    // Relasi
    public function participants() { return $this->hasMany(NotaDinasParticipant::class); }
    public function toUser() { return $this->belongsTo(User::class, 'to_user_id'); }
    public function fromUser() { return $this->belongsTo(User::class, 'from_user_id'); }
    public function destinationCity() { return $this->belongsTo(City::class, 'destination_city_id'); }
    public function requestingUnit() { return $this->belongsTo(Unit::class, 'requesting_unit_id'); }
    public function createdBy() { return $this->belongsTo(User::class, 'created_by'); }
    public function approvedBy() { return $this->belongsTo(User::class, 'approved_by'); }
    public function numberFormat() { return $this->belongsTo(DocNumberFormat::class, 'number_format_id'); }
    public function numberSequence() { return $this->belongsTo(NumberSequence::class, 'number_sequence_id'); }
    public function numberScopeUnit() { return $this->belongsTo(Unit::class, 'number_scope_unit_id'); }
}
