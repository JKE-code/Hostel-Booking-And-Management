<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'avatar_url',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_active'         => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        // Protect primary Master Super Administrator from accidental deletion
        static::deleting(function (User $user) {
            if ($user->email === 'admin@hitam.org') {
                throw new \InvalidArgumentException('The Primary Master Super Administrator (admin@hitam.org) cannot be deleted.');
            }
        });

        // Protect primary Master Super Administrator from accidental deactivation
        static::updating(function (User $user) {
            if ($user->getOriginal('email') === 'admin@hitam.org' && ! $user->is_active) {
                throw new \InvalidArgumentException('The Primary Master Super Administrator cannot be deactivated.');
            }
        });
    }

    /* ── Role Helpers ──────────────────────────────────────────── */

    public function isAdmin(): bool        { return $this->role === 'admin'; }
    public function isSuperAdmin(): bool   { return $this->email === 'admin@hitam.org' && $this->role === 'admin'; }
    public function isWarden(): bool       { return $this->role === 'warden'; }
    public function isSecurity(): bool     { return $this->role === 'security'; }
    public function isStudent(): bool      { return $this->role === 'student'; }

    /* ── Relationships ─────────────────────────────────────────── */

    /** One-to-one student profile (only for student role users) */
    public function student(): HasOne
    {
        return $this->hasOne(Student::class);
    }

    /** Hostels this user is warden-in-charge of */
    public function wardenOf(): HasMany
    {
        return $this->hasMany(Hostel::class, 'warden_in_charge_id');
    }

    /** Outpasses approved by this warden */
    public function approvedOutpasses(): HasMany
    {
        return $this->hasMany(Outpass::class, 'approved_by');
    }

    /** Visitors approved by this warden/security */
    public function approvedVisitors(): HasMany
    {
        return $this->hasMany(Visitor::class, 'approved_by');
    }

    /** Complaints assigned to this technician/staff */
    public function assignedComplaints(): HasMany
    {
        return $this->hasMany(Complaint::class, 'assigned_to');
    }

    /** Notices published by this user */
    public function publishedNotices(): HasMany
    {
        return $this->hasMany(Notice::class, 'published_by');
    }

    /** FCM device tokens registered by this user (for push notifications) */
    public function deviceTokens(): HasMany
    {
        return $this->hasMany(DeviceToken::class);
    }

    /** Active FCM tokens only */
    public function activeDeviceTokens(): HasMany
    {
        return $this->hasMany(DeviceToken::class)->where('is_active', true);
    }
}
