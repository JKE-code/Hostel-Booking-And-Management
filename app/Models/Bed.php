<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Bed extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'bed_identifier',
        'status',
    ];

    /* ── Accessors ─────────────────────────────────────────────── */

    public function getBedNumberAttribute(): string
    {
        return $this->bed_identifier ?? '';
    }

    /* ── Relationships ─────────────────────────────────────────── */

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function allocations(): HasMany
    {
        return $this->hasMany(RoomAllocation::class);
    }

    /** Currently active allocation for this bed */
    public function activeAllocation(): HasOne
    {
        return $this->hasOne(RoomAllocation::class)->where('status', 'active');
    }
}
