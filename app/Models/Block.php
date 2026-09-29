<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Block extends Model
{
    use HasFactory;

    protected $fillable = [
        'hostel_id',
        'block_name',
        'block_code',
        'total_floors',
    ];

    protected function casts(): array
    {
        return [
            'total_floors' => 'integer',
        ];
    }

    /* ── Relationships ─────────────────────────────────────────── */

    public function hostel(): BelongsTo
    {
        return $this->belongsTo(Hostel::class);
    }

    public function floors(): HasMany
    {
        return $this->hasMany(Floor::class);
    }

    public function rooms(): HasManyThrough
    {
        return $this->hasManyThrough(Room::class, Floor::class);
    }
}
