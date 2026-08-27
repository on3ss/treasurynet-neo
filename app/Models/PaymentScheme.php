<?php

namespace App\Models;

use App\Observers\PaymentSchemeObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ObservedBy([PaymentSchemeObserver::class])]
class PaymentScheme extends Model
{
    use SoftDeletes;

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
}
