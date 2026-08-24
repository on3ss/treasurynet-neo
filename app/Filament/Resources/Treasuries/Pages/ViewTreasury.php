<?php

namespace App\Filament\Resources\Treasuries\Pages;

use App\Filament\Resources\Treasuries\TreasuryResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewTreasury extends ViewRecord
{
    protected static string $resource = TreasuryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
