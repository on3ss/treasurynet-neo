<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BillType extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'detail_head_id',
        'code',
        'description',
    ];

    /**
     * Get the detail head that owns the bill type.
     */
    public function detailHead()
    {
        return $this->belongsTo(DetailHead::class);
    }

    /**
     * Scope a query to only include bill types for AC heads.
     */
    public function scopeForAcHeads($query)
    {
        return $query->whereHas('detailHead', function ($q) {
            $q->where('head_type', 'AC');
        });
    }

    /**
     * Scope a query to only include bill types for DC heads.
     */
    public function scopeForDcHeads($query)
    {
        return $query->whereHas('detailHead', function ($q) {
            $q->where('head_type', 'DC');
        });
    }

    /**
     * Scope a query to search by description or code.
     */
    public function scopeSearch($query, string $search)
    {
        return $query->where('description', 'like', "%{$search}%")
            ->orWhere('code', 'like', "%{$search}%");
    }

    /**
     * Get the bill type with description.
     */
    public function getCodeWithDescriptionAttribute(): string
    {
        return "{$this->code} - {$this->description}";
    }
}
