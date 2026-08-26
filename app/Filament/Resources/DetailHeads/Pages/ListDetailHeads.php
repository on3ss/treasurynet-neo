<?php

namespace App\Filament\Resources\DetailHeads\Pages;

use App\Filament\Resources\DetailHeads\DetailHeadResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDetailHeads extends ListRecords
{
    protected static string $resource = DetailHeadResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
