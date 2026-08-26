<?php

namespace App\Filament\Resources\DetailHeads\Pages;

use App\Filament\Resources\DetailHeads\DetailHeadResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewDetailHead extends ViewRecord
{
    protected static string $resource = DetailHeadResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
