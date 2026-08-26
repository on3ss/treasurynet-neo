<?php

namespace App\Filament\Resources\BillTypes\Pages;

use App\Filament\Resources\BillTypes\BillTypeResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewBillType extends ViewRecord
{
    protected static string $resource = BillTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
