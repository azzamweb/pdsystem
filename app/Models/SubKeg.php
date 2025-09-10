<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubKeg extends Model
{
    protected $table = 'sub_keg';
    
    protected $fillable = [
        'kode_subkeg',
        'nama_subkeg',
        'pagu',
        'id_unit',
    ];

    protected $casts = [
        'id_unit' => 'integer',
        'pagu' => 'decimal:2',
    ];

    /**
     * Get the unit that owns the sub kegiatan.
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'id_unit');
    }

    /**
     * Get the display name for the sub kegiatan.
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->kode_subkeg . ' - ' . $this->nama_subkeg;
    }

    /**
     * Get formatted pagu amount.
     */
    public function getFormattedPaguAttribute(): string
    {
        if ($this->pagu === null) {
            return 'Belum ditentukan';
        }
        
        return 'Rp ' . number_format($this->pagu, 0, ',', '.');
    }
}
