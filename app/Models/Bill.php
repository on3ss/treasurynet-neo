<?php

namespace App\Models;

use App\Enums\BillCategory;
use App\Enums\BillSource;
use App\Enums\PaymentType;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bill extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'financial_year_id',
        'treasury_id',
        'ddo_id',
        'payment_scheme_id',
        'detail_head_id',
        'bill_type_id',
        'form_id',
        'bill_number',
        'source_bill_id',
        'bill_date',
        'source',
        'category',
        'net_amount',
        'gross_amount',
        'is_urgent',
        'payment_type',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'bill_date' => 'datetime',
        'net_amount' => 'decimal:2',
        'gross_amount' => 'decimal:2',
        'is_urgent' => 'boolean',
        'source' => BillSource::class,
        'category' => BillCategory::class,
        'payment_type' => PaymentType::class,
    ];

    /**
     * Relationships
     */
    public function financialYear(): BelongsTo
    {
        return $this->belongsTo(FinancialYear::class);
    }

    public function treasury(): BelongsTo
    {
        return $this->belongsTo(Treasury::class);
    }

    public function ddo(): BelongsTo
    {
        return $this->belongsTo(Ddo::class);
    }

    public function paymentScheme(): BelongsTo
    {
        return $this->belongsTo(PaymentScheme::class);
    }

    public function detailHead(): BelongsTo
    {
        return $this->belongsTo(DetailHead::class);
    }

    public function billType(): BelongsTo
    {
        return $this->belongsTo(BillType::class);
    }

    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class);
    }

    public function payees(): HasMany
    {
        return $this->hasMany(Payee::class);
    }

    public function grosses(): HasMany
    {
        return $this->hasMany(Gross::class);
    }

    public function deductions(): HasMany
    {
        return $this->hasMany(Deduction::class);
    }

    /**
     * Scopes
     */
    public function scopeUrgent($query)
    {
        return $query->where('is_urgent', true);
    }

    public function scopeElectronicPayment($query)
    {
        return $query->where('payment_type', PaymentType::ELECTRONIC);
    }

    /**
     * Accessors & Mutators
     */
    public function getFormattedBillNumberAttribute(): string
    {
        return str_pad($this->bill_number, 6, '0', STR_PAD_LEFT);
    }

    public function getTotalGrossAmountAttribute(): float
    {
        return $this->grosses()->sum('amount');
    }

    protected function displayName(): Attribute
    {
        return Attribute::make(
            get: fn (): string => sprintf(
                '%s/%s/%s',
                $this->financialYear?->name ?? '-',
                $this->treasury?->code ?? '-',
                $this->bill_number ?? '-',
            ),
        );
    }
}
