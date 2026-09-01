<?php

namespace App\Models;

use Database\Factories\GrossFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gross extends Model
{
    /** @use HasFactory<GrossFactory> */
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'bill_id',
        'sub_detail_head_id',
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

    public function subDetailHead(): BelongsTo
    {
        return $this->belongsTo(SubDetailHead::class);
    }

    /**
     * Scopes
     */
    public function scopeForBill($query, $billId)
    {
        return $query->where('bill_id', $billId);
    }

    public function scopeForSubDetailHead($query, $subDetailHeadId)
    {
        return $query->where('sub_detail_head_id', $subDetailHeadId);
    }

    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }
}
