<?php

namespace App\Filament\Resources\BillTypes\Pages;

use App\Filament\Resources\BillTypes\BillTypeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBillTypes extends ListRecords
{
    protected static string $resource = BillTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
