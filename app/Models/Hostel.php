<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Hostel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'gender_type',
        'total_capacity',
        'warden_in_charge_id',
        'address',
        'contact_phone',
    ];

    protected function casts(): array
    {
        return [
            'total_capacity' => 'integer',
        ];
    }

    /* ── Relationships ─────────────────────────────────────────── */

    public function warden(): BelongsTo
    {
        return $this->belongsTo(User::class, 'warden_in_charge_id');
    }

    public function blocks(): HasMany
    {
        return $this->hasMany(Block::class);
    }
    public function floors(): HasManyThrough
    {
        return $this->hasManyThrough(Floor::class, Block::class);
    }

    /** Outpasses directly linked to this hostel */
    public function outpasses(): HasMany
    {
        return $this->hasMany(Outpass::class);
    }

    /** Visitor log entries for this hostel's gate */
    public function visitors(): HasMany
    {
        return $this->hasMany(Visitor::class);
    }

    /** Mess menus for this hostel */
    public function messMenus(): HasMany
    {
        return $this->hasMany(MessMenu::class);
    }

    /** Fee transactions recorded against this hostel */
    public function feeTransactions(): HasMany
    {
        return $this->hasMany(FeeTransaction::class);
    }
}
