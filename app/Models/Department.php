<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use SoftDeletes;

    protected $fillable = ['code', 'name'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'code' => 'string',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($department) {
            // Ensure code is always 2 digits with leading zero if needed
            $department->code = str_pad($department->code, 2, '0', STR_PAD_LEFT);
        });
    }

    /**
     * Scope a query to only include active departments.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->whereNull('deleted_at');
    }

    /**
     * Scope a query to only include trashed departments.
     */
    public function scopeInactive(Builder $query): Builder
    {
        return $query->whereNotNull('deleted_at');
    }

    /**
     * Scope a query to search by name or code.
     */
    public function scopeSearch(Builder $query, string $search): Builder
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
                ->orWhere('code', 'like', "%{$search}%");
        });
    }

    /**
     * Get formatted code (always with leading zero).
     */
    public function getFormattedCodeAttribute(): string
    {
        return str_pad($this->code, 2, '0', STR_PAD_LEFT);
    }

    /**
     * Find department by code (accepts both '5' and '05').
     */
    public static function findByCode(string $code): ?self
    {
        return static::where('code', str_pad($code, 2, '0', STR_PAD_LEFT))->first();
    }
}
