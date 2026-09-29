<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'roll_number',
        'department',
        'year_of_study',
        'phone',
        'parent_name',
        'parent_phone',
        'emergency_contact',
        'blood_group',
        'gender',
        'date_of_birth',
        'permanent_address',
        'photo_url',
        'id_proof_url',
        'admission_letter_url',
        'medical_cert_url',
        'document_verified_at',
        'document_verified_by',
        'verification_notes',
        'onboarding_status',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth'        => 'date',
            'document_verified_at' => 'datetime',
            'year_of_study'        => 'integer',
        ];
    }

    /* ── Relationships ─────────────────────────────────────────── */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function documentVerifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'document_verified_by');
    }

    /** Active room allocation for the current academic year */
    public function activeAllocation(): HasOne
    {
        return $this->hasOne(RoomAllocation::class)->where('status', 'active')->latest();
    }

    public function allocations(): HasMany
    {
        return $this->hasMany(RoomAllocation::class);
    }

    public function outpasses(): HasMany
    {
        return $this->hasMany(Outpass::class);
    }

    public function complaints(): HasMany
    {
        return $this->hasMany(Complaint::class);
    }

    public function feeTransactions(): HasMany
    {
        return $this->hasMany(FeeTransaction::class);
    }

    /** Pre-registered gate visitors for this student */
    public function visitors(): HasMany
    {
        return $this->hasMany(Visitor::class);
    }
}
