<?php

namespace App\Models;

use Database\Factories\DeductionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Deduction extends Model
{
    /** @use HasFactory<DeductionFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'bill_id',
        'receipt_scheme_id',
        'amount',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    /**
     * Get the bill that owns this deduction.
     */
    public function bill(): BelongsTo
    {
        return $this->belongsTo(Bill::class);
    }

    /**
     * Get the receipt scheme that owns this deduction.
     */
    public function receiptScheme(): BelongsTo
    {
        return $this->belongsTo(ReceiptScheme::class);
    }

    /**
     * Scope a query to only include deductions for a specific bill.
     */
    public function scopeForBill($query, $billId)
    {
        return $query->where('bill_id', $billId);
    }

    /**
     * Scope a query to only include deductions for a specific receipt scheme.
     */
    public function scopeForReceiptScheme($query, $receiptSchemeId)
    {
        return $query->where('receipt_scheme_id', $receiptSchemeId);
    }
}
