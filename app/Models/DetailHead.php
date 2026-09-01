<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetailHead extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'code',
        'description',
        'head_type',
        'deduction_applicability',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'deduction_applicability' => 'array',
    ];

    /**
     * Relationships
     */
    public function subDetailHeads(): HasMany
    {
        return $this->hasMany(SubDetailHead::class);
    }

    /**
     * Scopes
     */
    public function scopeAcHeads($query)
    {
        return $query->where('head_type', 'AC');
    }

    public function scopeDcHeads($query)
    {
        return $query->where('head_type', 'DC');
    }

    public function scopeAcDcApplicable($query)
    {
        return $query->whereIn('head_type', ['AC', 'DC']);
    }

    public function scopeWithAgDeduction($query)
    {
        return $query->whereJsonContains('deduction_applicability', 'AG');
    }

    public function scopeWithSalaryDeduction($query)
    {
        return $query->whereJsonContains('deduction_applicability', 'Salary');
    }

    public function scopeWithTdsDeduction($query)
    {
        return $query->whereJsonContains('deduction_applicability', 'TDS');
    }

    /**
     * Helper Methods
     */
    public function isAc(): bool
    {
        return $this->head_type === 'AC';
    }

    public function isDc(): bool
    {
        return $this->head_type === 'DC';
    }

    public function isAcDcApplicable(): bool
    {
        return in_array($this->head_type, ['AC', 'DC']);
    }

    public function hasAgDeduction(): bool
    {
        return in_array('AG', $this->deduction_applicability ?? []);
    }

    public function hasSalaryDeduction(): bool
    {
        return in_array('Salary', $this->deduction_applicability ?? []);
    }

    public function hasTdsDeduction(): bool
    {
        return in_array('TDS', $this->deduction_applicability ?? []);
    }

    public function getApplicableDeductions(): array
    {
        return $this->deduction_applicability ?? [];
    }

    /**
     * Accessors & Mutators
     */
    protected function displayName(): Attribute
    {
        return Attribute::make(
            get: fn(mixed $value, array $attributes) => $attributes['code'] . ' - ' . $attributes['description']
        );
    }
}