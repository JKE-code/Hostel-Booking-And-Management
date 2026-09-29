<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoomAllocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'bed_id',
        'academic_year',
        'allocated_from',
        'allocated_to',
        'status',
        'remarks',
        'allocated_by',
    ];

    protected function casts(): array
    {
        return [
            'allocated_from' => 'date',
            'allocated_to'   => 'date',
        ];
    }

    /* ── Relationships ─────────────────────────────────────────── */

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function bed(): BelongsTo
    {
        return $this->belongsTo(Bed::class);
    }

    public function allocatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'allocated_by');
    }
}
