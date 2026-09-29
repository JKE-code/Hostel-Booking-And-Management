<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Visitor extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'hostel_id',
        'visitor_name',
        'relation',
        'visitor_phone',
        'visitor_id_type',
        'visitor_id_number',
        'visitor_count',
        'visit_date',
        'expected_arrival',
        'expected_departure',
        'purpose',
        'status',
        'checked_in_at',
        'checked_out_at',
        'approved_by',
        'remarks',
    ];

    protected function casts(): array
    {
        return [
            'visit_date'      => 'date',
            'checked_in_at'   => 'datetime',
            'checked_out_at'  => 'datetime',
            'visitor_count'   => 'integer',
        ];
    }

    /* ── Scopes ────────────────────────────────────────────────── */

    public function scopeToday(Builder $query): Builder
    {
        return $query->whereDate('visit_date', today());
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pre_registered');
    }

    /* ── Relationships ─────────────────────────────────────────── */

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function hostel(): BelongsTo
    {
        return $this->belongsTo(Hostel::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
