<?php

namespace App\Filament\Resources\PaymentSchemes\Pages;

use App\Filament\Resources\PaymentSchemes\PaymentSchemeResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePaymentScheme extends CreateRecord
{
    protected static string $resource = PaymentSchemeResource::class;
}
