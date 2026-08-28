<?php

namespace App\Filament\Resources\SubDetailHeads\Pages;

use App\Filament\Resources\SubDetailHeads\SubDetailHeadResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditSubDetailHead extends EditRecord
{
    protected static string $resource = SubDetailHeadResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
