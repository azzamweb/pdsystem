<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TripReport extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'doc_no', 'number_is_manual', 'number_manual_reason', 'number_format_id', 'number_sequence_id',
        'number_scope_unit_id', 'spt_id', 'report_no', 'report_date', 'place_from', 'place_to',
        'depart_date', 'return_date', 'activities', 'created_by_user_id', 'status',
    ];

    public function spt() { return $this->belongsTo(Spt::class); }
    public function createdByUser() { return $this->belongsTo(User::class, 'created_by_user_id'); }
    public function signers() { return $this->hasMany(TripReportSigner::class); }
}
