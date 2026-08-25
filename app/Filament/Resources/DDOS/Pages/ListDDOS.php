<?php

namespace App\Filament\Resources\DDOS\Pages;

use App\Filament\Resources\DDOS\DDOResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDDOS extends ListRecords
{
    protected static string $resource = DDOResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
