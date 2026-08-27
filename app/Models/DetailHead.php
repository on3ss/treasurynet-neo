<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetailHead extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code',
        'description',
        'head_type',
        'deduction_applicability',
    ];

    protected $casts = [
        'deduction_applicability' => 'array',
    ];

    /**
     * Scope for AC heads
     */
    public function scopeAcHeads($query)
    {
        return $query->where('head_type', 'AC');
    }

    /**
     * Scope for DC heads
     */
    public function scopeDcHeads($query)
    {
        return $query->where('head_type', 'DC');
    }

    /**
     * Scope for AC/DC applicable heads
     */
    public function scopeAcDcApplicable($query)
    {
        return $query->whereIn('head_type', ['AC', 'DC']);
    }

    /**
     * Scope for heads with AG deduction
     */
    public function scopeWithAgDeduction($query)
    {
        return $query->whereJsonContains('deduction_applicability', 'AG');
    }

    /**
     * Scope for heads with salary deduction
     */
    public function scopeWithSalaryDeduction($query)
    {
        return $query->whereJsonContains('deduction_applicability', 'Salary');
    }

    /**
     * Scope for heads with TDS deduction
     */
    public function scopeWithTdsDeduction($query)
    {
        return $query->whereJsonContains('deduction_applicability', 'TDS');
    }

    /**
     * Check if head is AC type
     */
    public function isAc(): bool
    {
        return $this->head_type === 'AC';
    }

    /**
     * Check if head is DC type
     */
    public function isDc(): bool
    {
        return $this->head_type === 'DC';
    }

    /**
     * Check if AC/DC is applicable
     */
    public function isAcDcApplicable(): bool
    {
        return in_array($this->head_type, ['AC', 'DC']);
    }

    /**
     * Check if AG deduction is applicable
     */
    public function hasAgDeduction(): bool
    {
        return in_array('AG', $this->deduction_applicability ?? []);
    }

    /**
     * Check if salary deduction is applicable
     */
    public function hasSalaryDeduction(): bool
    {
        return in_array('Salary', $this->deduction_applicability ?? []);
    }

    /**
     * Check if TDS deduction is applicable
     */
    public function hasTdsDeduction(): bool
    {
        return in_array('TDS', $this->deduction_applicability ?? []);
    }

    /**
     * Get all applicable deductions as array
     */
    public function getApplicableDeductions(): array
    {
        return $this->deduction_applicability ?? [];
    }
}
