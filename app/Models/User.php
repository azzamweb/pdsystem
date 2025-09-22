<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

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
        'position_desc',
        'rank_id',
        'travel_grade_id',
        'npwp',
        'bank_name',
        'bank_account_no',
        'bank_account_name',
        'birth_date',
        'gender',
        'signature_path',
        'photo_path',
        'is_signer',
        'is_non_staff',
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
            'is_non_staff' => 'boolean',
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
     * Set birth_date attribute
     */
    public function setBirthDateAttribute($value)
    {
        $this->attributes['birth_date'] = ($value === '' || $value === null) ? null : $value;
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

    /**
     * Relationship with Travel Grade
     */
    public function travelGrade(): BelongsTo
    {
        return $this->belongsTo(TravelGrade::class);
    }

    /**
     * Get the sub kegiatan records that this user manages as PPTK.
     */
    public function subKegiatan(): HasMany
    {
        return $this->hasMany(SubKeg::class, 'pptk_user_id');
    }

    /**
     * Get nota dinas where this user is the "to" user
     */
    public function notaDinasTo(): HasMany
    {
        return $this->hasMany(NotaDinas::class, 'to_user_id');
    }

    /**
     * Get nota dinas where this user is the "from" user
     */
    public function notaDinasFrom(): HasMany
    {
        return $this->hasMany(NotaDinas::class, 'from_user_id');
    }

    /**
     * Get nota dinas where this user is the creator
     */
    public function notaDinasCreated(): HasMany
    {
        return $this->hasMany(NotaDinas::class, 'created_by');
    }

    /**
     * Get nota dinas where this user is the approver
     */
    public function notaDinasApproved(): HasMany
    {
        return $this->hasMany(NotaDinas::class, 'approved_by');
    }

    /**
     * Get nota dinas participants where this user is a participant
     */
    public function notaDinasParticipants(): HasMany
    {
        return $this->hasMany(NotaDinasParticipant::class, 'user_id');
    }

    /**
     * Check if user is used in any nota dinas documents
     */
    public function isUsedInNotaDinas(): bool
    {
        return $this->notaDinasTo()->exists() ||
               $this->notaDinasFrom()->exists() ||
               $this->notaDinasCreated()->exists() ||
               $this->notaDinasApproved()->exists() ||
               $this->notaDinasParticipants()->exists();
    }

    /**
     * Check if user is used in any sub kegiatan as PPTK
     */
    public function isUsedInSubKegiatan(): bool
    {
        return $this->subKegiatan()->exists();
    }

    /**
     * Check if user is used in any documents (nota dinas or sub kegiatan)
     */
    public function isUsedInDocuments(): bool
    {
        return $this->isUsedInNotaDinas() || $this->isUsedInSubKegiatan();
    }

    /**
     * Get all nota dinas documents where this user is involved
     */
    public function getAllNotaDinasInvolvement(): array
    {
        $involvements = [];

        // Check as "to" user
        $toNotaDinas = $this->notaDinasTo()->get();
        if ($toNotaDinas->count() > 0) {
            $involvements[] = [
                'type' => 'Kepada',
                'count' => $toNotaDinas->count(),
                'documents' => $toNotaDinas->pluck('doc_no')->toArray()
            ];
        }

        // Check as "from" user
        $fromNotaDinas = $this->notaDinasFrom()->get();
        if ($fromNotaDinas->count() > 0) {
            $involvements[] = [
                'type' => 'Dari',
                'count' => $fromNotaDinas->count(),
                'documents' => $fromNotaDinas->pluck('doc_no')->toArray()
            ];
        }

        // Check as creator
        $createdNotaDinas = $this->notaDinasCreated()->get();
        if ($createdNotaDinas->count() > 0) {
            $involvements[] = [
                'type' => 'Pembuat',
                'count' => $createdNotaDinas->count(),
                'documents' => $createdNotaDinas->pluck('doc_no')->toArray()
            ];
        }

        // Check as approver
        $approvedNotaDinas = $this->notaDinasApproved()->get();
        if ($approvedNotaDinas->count() > 0) {
            $involvements[] = [
                'type' => 'Penyetuju',
                'count' => $approvedNotaDinas->count(),
                'documents' => $approvedNotaDinas->pluck('doc_no')->toArray()
            ];
        }

        // Check as participant
        $participantNotaDinas = $this->notaDinasParticipants()->with('notaDinas')->get();
        if ($participantNotaDinas->count() > 0) {
            $involvements[] = [
                'type' => 'Peserta',
                'count' => $participantNotaDinas->count(),
                'documents' => $participantNotaDinas->pluck('notaDinas.doc_no')->toArray()
            ];
        }

        return $involvements;
    }

    /**
     * Get all sub kegiatan documents where this user is involved as PPTK
     */
    public function getAllSubKegiatanInvolvement(): array
    {
        $involvements = [];

        // Check as PPTK
        $subKegiatan = $this->subKegiatan()->get();
        if ($subKegiatan->count() > 0) {
            $involvements[] = [
                'type' => 'PPTK',
                'count' => $subKegiatan->count(),
                'documents' => $subKegiatan->pluck('display_name')->toArray()
            ];
        }

        return $involvements;
    }

    /**
     * Get all document involvements (nota dinas and sub kegiatan)
     */
    public function getAllDocumentInvolvements(): array
    {
        $involvements = [];

        // Get nota dinas involvements
        $notaDinasInvolvements = $this->getAllNotaDinasInvolvement();
        if (!empty($notaDinasInvolvements)) {
            $involvements['nota_dinas'] = $notaDinasInvolvements;
        }

        // Get sub kegiatan involvements
        $subKegiatanInvolvements = $this->getAllSubKegiatanInvolvement();
        if (!empty($subKegiatanInvolvements)) {
            $involvements['sub_kegiatan'] = $subKegiatanInvolvements;
        }

        return $involvements;
    }
}
