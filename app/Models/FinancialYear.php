<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FinancialYear extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'is_active',
        'is_current',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
        'is_current' => 'boolean',
    ];

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeCurrent($query)
    {
        return $query->where('is_current', true);
    }

    /**
     * Accessors & Mutators
     */
    public function getDateRangeAttribute(): string
    {
        /** @var Carbon $startDate */
        $startDate = $this->start_date;

        /** @var Carbon $endDate */
        $endDate = $this->end_date;

        return $startDate->format('d-m-Y') . ' to ' . $endDate->format('d-m-Y');
    }

    /**
     * Helper Methods
     */
    public static function getCurrent(): ?self
    {
        return static::current()->first();
    }

    public static function getCurrentByDate(): ?self
    {
        $now = Carbon::now();
        $currentFYStart = $now->month >= 4 ? $now->year : $now->year - 1;
        $name = $currentFYStart . '-' . ($currentFYStart + 1);

        return static::where('name', $name)->first();
    }

    public function isCurrent(): bool
    {
        return $this->is_current;
    }

    public function isActive(): bool
    {
        return $this->is_active;
    }

    public function setAsCurrent(): bool
    {
        static::query()->update(['is_current' => false]);

        return $this->update([
            'is_current' => true,
            'is_active' => true,
        ]);
    }
}