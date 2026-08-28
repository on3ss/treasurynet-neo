<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gross extends Model
{
    /** @use HasFactory<\Database\Factories\GrossFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'bill_id',
        'sub_detail_head_id',
        'amount',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    /**
     * Get the bill that owns this gross.
     */
    public function bill(): BelongsTo
    {
        return $this->belongsTo(Bill::class);
    }

    /**
     * Get the sub detail head that owns this gross.
     */
    public function subDetailHead(): BelongsTo
    {
        return $this->belongsTo(SubDetailHead::class);
    }

    /**
     * Scope a query to only include grosses for a specific bill.
     */
    public function scopeForBill($query, $billId)
    {
        return $query->where('bill_id', $billId);
    }

    /**
     * Scope a query to only include grosses for a specific sub detail head.
     */
    public function scopeForSubDetailHead($query, $subDetailHeadId)
    {
        return $query->where('sub_detail_head_id', $subDetailHeadId);
    }

    /**
     * Scope a query to only include grosses within a date range.
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }
}