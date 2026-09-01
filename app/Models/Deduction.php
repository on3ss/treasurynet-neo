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

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'bill_id',
        'receipt_scheme_id',
        'amount',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
    ];

    /**
     * Relationships
     */
    public function bill(): BelongsTo
    {
        return $this->belongsTo(Bill::class);
    }

    public function receiptScheme(): BelongsTo
    {
        return $this->belongsTo(ReceiptScheme::class);
    }

    /**
     * Scopes
     */
    public function scopeForBill($query, $billId)
    {
        return $query->where('bill_id', $billId);
    }

    public function scopeForReceiptScheme($query, $receiptSchemeId)
    {
        return $query->where('receipt_scheme_id', $receiptSchemeId);
    }
}
