<?php

namespace App\Filament\Resources\PaymentSchemes\Pages;

use App\Filament\Resources\PaymentSchemes\PaymentSchemeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPaymentSchemes extends ListRecords
{
    protected static string $resource = PaymentSchemeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
