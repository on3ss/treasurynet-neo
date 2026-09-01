<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BillNoSequence extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'treasury_id',
        'financial_year',
        'last_sequence_no',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'last_sequence_no' => 'integer',
        'treasury_id' => 'integer',
    ];

    /**
     * Relationships
     */
    public function treasury(): BelongsTo
    {
        return $this->belongsTo(Treasury::class);
    }

    /**
     * Scopes
     */
    public function scopeForTreasuryAndYear($query, int $treasuryId, string $financialYear)
    {
        return $query->where('treasury_id', $treasuryId)
            ->where('financial_year', $financialYear);
    }
}
