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
        'notes',
    ];

    public function notaDinas() { return $this->belongsTo(NotaDinas::class); }
    public function signedByUser() { return $this->belongsTo(User::class, 'signed_by_user_id'); }
    public function originPlace() { return $this->belongsTo(OrgPlace::class, 'origin_place_id'); }
    public function destinationCity() { return $this->belongsTo(City::class, 'destination_city_id'); }
    // public function members() { return $this->hasMany(SptMember::class); }
    public function tripReport() { return $this->hasOne(TripReport::class); }
}
