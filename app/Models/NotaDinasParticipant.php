<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotaDinasParticipant extends Model
{
    use HasFactory;

    protected $fillable = [
        'nota_dinas_id', 'user_id', 'role_in_trip',
    ];

    public function notaDinas() { return $this->belongsTo(NotaDinas::class); }
    public function user() { return $this->belongsTo(User::class); }
}
