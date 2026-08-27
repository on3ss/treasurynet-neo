<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Form extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'detail_head_id',
        'bill_type_id',
        'name',
    ];

    /**
     * Get the detail head that owns the form.
     */
    public function detailHead()
    {
        return $this->belongsTo(DetailHead::class);
    }

    /**
     * Get the bill type that owns the form.
     */
    public function billType()
    {
        return $this->belongsTo(BillType::class);
    }

    /**
     * Scope a query to only include forms for a specific detail head.
     */
    public function scopeForDetailHead($query, $detailHeadId)
    {
        return $query->where('detail_head_id', $detailHeadId);
    }

    /**
     * Scope a query to only include forms for a specific bill type.
     */
    public function scopeForBillType($query, $billTypeId)
    {
        return $query->where('bill_type_id', $billTypeId);
    }

    /**
     * Scope a query to search by name.
     */
    public function scopeSearch($query, string $search)
    {
        return $query->where('name', 'like', "%{$search}%");
    }

    /**
     * Get the form with full details.
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->detailHead->head} - {$this->billType->code} - {$this->name}";
    }
}
