<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubKeg extends Model
{
    protected $table = 'sub_keg';
    
    protected $fillable = [
        'kode_subkeg',
        'nama_subkeg',
        'pagu',
        'id_unit',
        'pptk_user_id',
    ];

    protected $casts = [
        'id_unit' => 'integer',
        'pagu' => 'decimal:2',
        'pptk_user_id' => 'integer',
    ];

    /**
     * Get the unit that owns the sub kegiatan.
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'id_unit');
    }

    /**
     * Get the PPTK user that manages this sub kegiatan.
     */
    public function pptkUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pptk_user_id');
    }

    /**
     * Get the SPPD records that belong to this sub kegiatan.
     */
    public function sppd(): HasMany
    {
        return $this->hasMany(Sppd::class, 'sub_keg_id');
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
