<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Complaint extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'room_id',
        'category',
        'title',
        'description',
        'photo_url',
        'priority',
        'status',
        'assigned_to',
        'assigned_at',
        'warden_remarks',
        'resolved_at',
        'resolution_rating',
    ];

    protected function casts(): array
    {
        return [
            'assigned_at'       => 'datetime',
            'resolved_at'       => 'datetime',
            'resolution_rating' => 'integer',
        ];
    }

    /* ── Relationships ─────────────────────────────────────────── */

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
