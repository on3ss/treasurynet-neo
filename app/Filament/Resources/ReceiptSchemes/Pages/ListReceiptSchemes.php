<?php

namespace App\Filament\Resources\ReceiptSchemes\Pages;

use App\Filament\Resources\ReceiptSchemes\ReceiptSchemeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListReceiptSchemes extends ListRecords
{
    protected static string $resource = ReceiptSchemeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
