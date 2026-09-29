<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class MessMenu extends Model
{
    use HasFactory;

    protected $table = 'mess_menus';

    protected $fillable = [
        'hostel_id',
        'day_of_week',
        'meal_type',
        'menu_title',
        'items',
        'special_note',
        'is_active',
        'effective_from',
        'effective_until',
    ];

    protected function casts(): array
    {
        return [
            'is_active'       => 'boolean',
            'effective_from'  => 'date',
            'effective_until' => 'date',
        ];
    }

    /* ── Scopes ────────────────────────────────────────────────── */

    /** Today's active menu for a given hostel (or all hostels if hostel_id = null) */
    public function scopeToday(Builder $query, ?int $hostelId = null): Builder
    {
        $day = strtolower(Carbon::today()->englishDayOfWeek);
        return $query->where('day_of_week', $day)
                     ->where('is_active', true)
                     ->where(function ($q) use ($hostelId) {
                         $q->whereNull('hostel_id')
                           ->when($hostelId, fn($q) => $q->orWhere('hostel_id', $hostelId));
                     });
    }

    /* ── Relationships ─────────────────────────────────────────── */

    public function hostel(): BelongsTo
    {
        return $this->belongsTo(Hostel::class);
    }
}
