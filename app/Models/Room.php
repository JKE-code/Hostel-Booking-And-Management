<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'floor_id',
        'room_number',
        'capacity',
        'room_type',
        'base_fee',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'capacity' => 'integer',
            'base_fee' => 'decimal:2',
        ];
    }

    /* ── Relationships ─────────────────────────────────────────── */

    public function floor(): BelongsTo
    {
        return $this->belongsTo(Floor::class);
    }

    public function beds(): HasMany
    {
        return $this->hasMany(Bed::class);
    }

    public function complaints(): HasMany
    {
        return $this->hasMany(Complaint::class);
    }

    /** Convenience: available beds count */
    public function availableBedsCount(): int
    {
        return $this->beds()->where('status', 'available')->count();
    }
}
