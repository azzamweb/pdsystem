<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Receipt extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'doc_no', 'number_is_manual', 'number_manual_reason', 'number_format_id', 'number_sequence_id',
        'number_scope_unit_id', 'sppd_id', 'travel_grade_id', 'receipt_no', 'receipt_date', 'payee_user_id',
        'total_amount', 'notes', 'status',
    ];

    public function sppd() { return $this->belongsTo(Sppd::class); }
    public function travelGrade() { return $this->belongsTo(TravelGrade::class, 'travel_grade_id'); }
    public function payeeUser() { return $this->belongsTo(User::class, 'payee_user_id'); }
    public function lines() { return $this->hasMany(ReceiptLine::class); }
}
