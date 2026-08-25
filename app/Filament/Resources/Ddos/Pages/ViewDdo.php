<?php

namespace App\Filament\Resources\Ddos\Pages;

use App\Filament\Resources\Ddos\DdoResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewDdo extends ViewRecord
{
    protected static string $resource = DdoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
