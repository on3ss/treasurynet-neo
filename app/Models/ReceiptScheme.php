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

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'major_head',
        'submajor_head',
        'minor_head',
        'subminor_head',
        'sub_head',
        'code',
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
}