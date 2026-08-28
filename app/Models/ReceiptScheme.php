<?php

namespace App\Models;

use App\Observers\ReceiptSchemeObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ObservedBy([ReceiptSchemeObserver::class])]
class ReceiptScheme extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'major_head',
        'submajor_head',
        'minor_head',
        'subminor_head',
        'sub_head',
        'description',
        'sector_code',
    ];
}
