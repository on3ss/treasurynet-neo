<?php

namespace App\Filament\Resources\SubDetailHeads\Pages;

use App\Filament\Resources\SubDetailHeads\SubDetailHeadResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSubDetailHeads extends ListRecords
{
    protected static string $resource = SubDetailHeadResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
