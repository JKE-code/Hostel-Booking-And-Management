<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Outpass extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'hostel_id',
        'leave_type',
        'destination',
        'reason',
        'out_datetime',
        'in_datetime',
        'actual_return_datetime',
        'parent_consent',
        'parent_consent_via',
        'status',
        'approved_by',
        'approved_at',
        'rejection_reason',
        'qr_verification_code',
    ];

    protected function casts(): array
    {
        return [
            'out_datetime'           => 'datetime',
            'in_datetime'            => 'datetime',
            'actual_return_datetime' => 'datetime',
            'approved_at'            => 'datetime',
            'parent_consent'         => 'boolean',
        ];
    }

    /** Auto-generate a unique QR code on creation */
    protected static function booted(): void
    {
        static::creating(function (Outpass $outpass) {
            if (empty($outpass->qr_verification_code)) {
                $outpass->qr_verification_code = strtoupper(Str::random(12));
            }
        });
    }

    /* ── Relationships ─────────────────────────────────────────── */

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /** Direct hostel FK — avoids 5-table join for "who is outside from Boys Hostel" */
    public function hostel(): BelongsTo
    {
        return $this->belongsTo(Hostel::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /* ── Helpers ───────────────────────────────────────────────── */

    public function isOverdue(): bool
    {
        return $this->status === 'approved'
            && now()->isAfter($this->in_datetime)
            && is_null($this->actual_return_datetime);
    }
}
