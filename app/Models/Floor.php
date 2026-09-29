<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Floor extends Model
{
    use HasFactory;

    protected $fillable = [
        'block_id',
        'floor_number',
        'floor_label',
    ];

    protected function casts(): array
    {
        return [
            'floor_number' => 'integer',
        ];
    }

    /* ── Relationships ─────────────────────────────────────────── */

    public function block(): BelongsTo
    {
        return $this->belongsTo(Block::class);
    }

    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }
}
