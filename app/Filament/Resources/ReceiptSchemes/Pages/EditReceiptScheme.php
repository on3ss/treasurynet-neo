<?php

namespace App\Filament\Resources\ReceiptSchemes\Pages;

use App\Filament\Resources\ReceiptSchemes\ReceiptSchemeResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditReceiptScheme extends EditRecord
{
    protected static string $resource = ReceiptSchemeResource::class;

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
