<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payee extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'bill_id',
        'reg_code',
        'name',
        'amount',
        'ifsc',
        'account_no',
        'rbi_endtoend_id',
        'rbi_utr_id',
        'rbi_utr_date',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'rbi_utr_date' => 'date',
    ];

    /**
     * Relationships
     */
    public function bill(): BelongsTo
    {
        return $this->belongsTo(Bill::class);
    }
}