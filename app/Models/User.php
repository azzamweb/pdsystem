<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'nip',
        'nik',
        'gelar_depan',
        'gelar_belakang',
        'phone',
        'whatsapp',
        'address',
        'unit_id',
        'position_id',
        'rank_id',
        'npwp',
        'bank_name',
        'bank_account_no',
        'bank_account_name',
        'birth_date',
        'gender',
        'signature_path',
        'photo_path',
        'is_signer',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'birth_date' => 'date',
            'is_signer' => 'boolean',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    /**
     * Get the full name with titles
     */
    public function fullNameWithTitles(): string
    {
        $name = '';
        if ($this->gelar_depan) {
            $name .= $this->gelar_depan . ' ';
        }
        $name .= $this->name;
        if ($this->gelar_belakang) {
            $name .= ', ' . $this->gelar_belakang;
        }
        return $name;
    }

    /**
     * Relationship with Unit
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    /**
     * Relationship with Position
     */
    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    /**
     * Relationship with Rank
     */
    public function rank(): BelongsTo
    {
        return $this->belongsTo(Rank::class);
    }

    /**
     * Get echelon through position relationship
     */
    public function echelon(): ?Echelon
    {
        return $this->position?->echelon;
    }
}
