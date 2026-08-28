<?php

namespace App\Filament\Resources\ReceiptSchemes\Pages;

use App\Filament\Resources\ReceiptSchemes\ReceiptSchemeResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewReceiptScheme extends ViewRecord
{
    protected static string $resource = ReceiptSchemeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
