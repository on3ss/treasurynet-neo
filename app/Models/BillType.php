<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class BillType extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'detail_head_id',
        'code',
        'description',
    ];

    /**
     * Relationships
     */
    public function detailHead(): BelongsTo
    {
        return $this->belongsTo(DetailHead::class);
    }

    /**
     * Scopes
     */
    public function scopeForAcHeads($query)
    {
        return $query->whereHas('detailHead', function ($q) {
            $q->where('head_type', 'AC');
        });
    }

    public function scopeForDcHeads($query)
    {
        return $query->whereHas('detailHead', function ($q) {
            $q->where('head_type', 'DC');
        });
    }

    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('description', 'like', "%{$search}%")
                ->orWhere('code', 'like', "%{$search}%");
        });
    }

    /**
     * Accessors & Mutators
     */
    public function getCodeWithDescriptionAttribute(): string
    {
        return "{$this->code} - {$this->description}";
    }

    protected function displayName(): Attribute
    {
        return Attribute::make(
            get: fn(mixed $value, array $attributes) => $attributes['code'] . ' - ' . $attributes['description']
        );
    }
}