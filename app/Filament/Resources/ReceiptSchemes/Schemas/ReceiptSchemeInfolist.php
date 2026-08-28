<?php

namespace App\Filament\Resources\ReceiptSchemes\Schemas;

use App\Models\ReceiptScheme;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ReceiptSchemeInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('major_head'),
                TextEntry::make('submajor_head'),
                TextEntry::make('minor_head'),
                TextEntry::make('subminor_head'),
                TextEntry::make('sub_head'),
                TextEntry::make('code'),
                TextEntry::make('description'),
                TextEntry::make('sector_code')
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (ReceiptScheme $record): bool => $record->trashed()),
            ]);
    }
}
