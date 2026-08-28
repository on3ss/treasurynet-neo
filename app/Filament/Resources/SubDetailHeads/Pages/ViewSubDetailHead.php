<?php

namespace App\Filament\Resources\SubDetailHeads\Pages;

use App\Filament\Resources\SubDetailHeads\SubDetailHeadResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewSubDetailHead extends ViewRecord
{
    protected static string $resource = SubDetailHeadResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
