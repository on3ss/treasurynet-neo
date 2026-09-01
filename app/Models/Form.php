<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Form extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'detail_head_id',
        'bill_type_id',
        'name',
    ];

    /**
     * Relationships
     */
    public function detailHead(): BelongsTo
    {
        return $this->belongsTo(DetailHead::class);
    }

    public function billType(): BelongsTo
    {
        return $this->belongsTo(BillType::class);
    }

    /**
     * Scopes
     */
    public function scopeForDetailHead($query, $detailHeadId)
    {
        return $query->where('detail_head_id', $detailHeadId);
    }

    public function scopeForBillType($query, $billTypeId)
    {
        return $query->where('bill_type_id', $billTypeId);
    }

    public function scopeSearch($query, string $search)
    {
        return $query->where('name', 'like', "%{$search}%");
    }

    /**
     * Accessors & Mutators
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->detailHead->head} - {$this->billType->code} - {$this->name}";
    }
}