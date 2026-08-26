<?php

namespace App\Filament\Resources\BillTypes\Schemas;

use App\Models\BillType;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class BillTypeInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('detail_head_id')
                    ->numeric(),
                TextEntry::make('code'),
                TextEntry::make('description'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (BillType $record): bool => $record->trashed()),
            ]);
    }
}
