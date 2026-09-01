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

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'detail_head_id',
        'code',
        'description',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        //
    ];

    /**
     * Relationships
     */
    public function detailHead(): BelongsTo
    {
        return $this->belongsTo(DetailHead::class);
    }

    /**
     * Scopes
     */
    public function scopeForDetailHead($query, $detailHeadId)
    {
        return $query->where('detail_head_id', $detailHeadId);
    }

    /**
     * Accessors & Mutators
     */
    public function getFullCodeAttribute(): string
    {
        return $this->detailHead->code . $this->code;
    }
}