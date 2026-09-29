<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class AcademicYear extends Model
{
    use HasFactory;

    protected $fillable = [
        'label',
        'start_date',
        'end_date',
        'is_current',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date'   => 'date',
            'is_current' => 'boolean',
        ];
    }

    /* ── Scopes ────────────────────────────────────────────────── */

    public function scopeCurrent(Builder $query): Builder
    {
        return $query->where('is_current', true);
    }

    /* ── Helpers ───────────────────────────────────────────────── */

    /** Returns the single current academic year label, e.g. '2025-2026' */
    public static function currentLabel(): string
    {
        return static::where('is_current', true)->value('label') ?? date('Y') . '-' . (date('Y') + 1);
    }
}
