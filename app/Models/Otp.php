<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class Otp extends Model
{
    use HasFactory;

    protected $fillable = [
        'identifier',
        'otp_code',
        'purpose',
        'attempts',
        'is_locked',
        'expires_at',
        'verified_at',
    ];

    protected function casts(): array
    {
        return [
            'attempts'    => 'integer',
            'is_locked'   => 'boolean',
            'expires_at'  => 'datetime',
            'verified_at' => 'datetime',
        ];
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public function isVerified(): bool
    {
        return !is_null($this->verified_at);
    }

    public function isLocked(): bool
    {
        return $this->is_locked || $this->attempts >= 5;
    }

    public function recordFailedAttempt(): void
    {
        $this->increment('attempts');
        if ($this->attempts >= 5) {
            $this->update(['is_locked' => true]);
        }
    }

    public function verifyCode(string $enteredCode): bool
    {
        return Hash::check($enteredCode, $this->otp_code);
    }
}
