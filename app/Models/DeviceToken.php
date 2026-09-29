<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeviceToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'fcm_token',
        'device_type',
        'device_name',
        'app_version',
        'is_active',
        'last_used_at',
    ];

    protected function casts(): array
    {
        return [
            'is_active'    => 'boolean',
            'last_used_at' => 'datetime',
        ];
    }

    /* ── Relationships ─────────────────────────────────────────── */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
