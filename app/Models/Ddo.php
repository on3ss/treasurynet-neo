<?php

namespace App\Models;

use App\Enums\DDOType;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ddo extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'treasury_id',
        'department_id',
        'sequence',
        'code',
        'designation',
        'type',
        'address',
        'is_active',
    ];

    protected $casts = [
        'type' => DDOType::class,
        'is_active' => 'boolean',
    ];

    public function treasury(): BelongsTo
    {
        return $this->belongsTo(Treasury::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    protected function displayName(): Attribute
    {
        return Attribute::make(get: fn (mixed $value, array $attributes) => $attributes['code'].' - '.$attributes['designation']);
    }
}
