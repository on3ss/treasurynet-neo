<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Treasury extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'name',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the bill number sequences for the treasury.
     */
    public function billNoSequences(): HasMany
    {
        return $this->hasMany(BillNoSequence::class);
    }

    /**
     * Get the bills for the treasury.
     */
    public function bills(): HasMany
    {
        return $this->hasMany(Bill::class);
    }

    /**
     * Scope a query to only include active treasuries.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include inactive treasuries.
     */
    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    /**
     * Check if the treasury is active.
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    /**
     * Activate the treasury.
     */
    public function activate(): bool
    {
        return $this->update(['is_active' => true]);
    }

    /**
     * Deactivate the treasury.
     */
    public function deactivate(): bool
    {
        return $this->update(['is_active' => false]);
    }

    /**
     * Get formatted treasury name with code.
     */
    public function getFormattedNameAttribute(): string
    {
        return "{$this->code} - {$this->name}";
    }

    protected function displayName(): Attribute
    {
        return Attribute::make(get: fn(mixed $value, array $attributes) => $attributes['code'] . ' - ' . $attributes['name']);
    }
}
