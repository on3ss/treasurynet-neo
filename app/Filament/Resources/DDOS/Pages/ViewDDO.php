<?php

namespace App\Filament\Resources\DDOS\Pages;

use App\Filament\Resources\DDOS\DDOResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewDDO extends ViewRecord
{
    protected static string $resource = DDOResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
