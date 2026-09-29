<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Notice extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'category',
        'target_audience',
        'is_pinned',
        'is_published',
        'published_by',
        'attachment_url',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'is_pinned'     => 'boolean',
            'is_published'  => 'boolean',
            'expires_at'    => 'datetime',
        ];
    }

    /* ── Scopes ────────────────────────────────────────────────── */

    /** Only active, non-expired published notices */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_published', true)
                     ->where(function ($q) {
                         $q->whereNull('expires_at')
                           ->orWhere('expires_at', '>', now());
                     });
    }

    public function scopePinned(Builder $query): Builder
    {
        return $query->where('is_pinned', true);
    }

    /* ── Relationships ─────────────────────────────────────────── */

    public function publishedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'published_by');
    }
}
