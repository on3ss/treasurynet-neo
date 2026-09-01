<?php

namespace App\Models;

use App\Observers\PaymentSchemeObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ObservedBy([PaymentSchemeObserver::class])]
class PaymentScheme extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'demand_number',
        'major_head',
        'submajor_head',
        'minor_head',
        'subminor_head',
        'sub_head',
        'charged_or_voted',
        'flag',
        'description',
        'sector_code',
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
     * Accessors & Mutators
     */
    protected function displayName(): Attribute
    {
        return Attribute::make(
            get: fn(mixed $value, array $attributes) => $attributes['code'] . ' - ' . $attributes['description']
        );
    }
}