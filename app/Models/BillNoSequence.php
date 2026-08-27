<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BillNoSequence extends Model
{
    protected $fillable = [
        'treasury_id',
        'financial_year',
        'last_sequence_no',
    ];

    protected $casts = [
        'last_sequence_no' => 'integer',
        'treasury_id' => 'integer',
    ];

    /**
     * Get the treasury that owns the bill number sequence.
     */
    public function treasury(): BelongsTo
    {
        return $this->belongsTo(Treasury::class);
    }

    /**
     * Scope query to find sequence by treasury and financial year.
     */
    public function scopeForTreasuryAndYear($query, int $treasuryId, string $financialYear)
    {
        return $query->where('treasury_id', $treasuryId)
            ->where('financial_year', $financialYear);
    }
}
