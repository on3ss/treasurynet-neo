<?php

namespace App\Filament\Resources\PaymentSchemes\Pages;

use App\Filament\Resources\PaymentSchemes\PaymentSchemeResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPaymentScheme extends ViewRecord
{
    protected static string $resource = PaymentSchemeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
