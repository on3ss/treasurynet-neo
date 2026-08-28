<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubDetailHead extends Model
{
    /** @use HasFactory<\Database\Factories\SubDetailHeadFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'detail_head_id',
        'code',
        'description',
    ];

    /**
     * Get the detail head that owns this sub detail head.
     */
    public function detailHead(): BelongsTo
    {
        return $this->belongsTo(DetailHead::class);
    }

    /**
     * Get the full code by combining detail head code with sub detail head code.
     */
    public function getFullCodeAttribute(): string
    {
        return $this->detailHead->code . $this->code;
    }

    /**
     * Scope a query to only include sub detail heads for a specific detail head.
     */
    public function scopeForDetailHead($query, $detailHeadId)
    {
        return $query->where('detail_head_id', $detailHeadId);
    }
}