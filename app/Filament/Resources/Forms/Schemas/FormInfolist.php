<?php

namespace App\Filament\Resources\Forms\Schemas;

use App\Models\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class FormInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('detail_head_id')
                    ->numeric(),
                TextEntry::make('bill_type_id')
                    ->numeric(),
                TextEntry::make('name'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Form $record): bool => $record->trashed()),
            ]);
    }
}
