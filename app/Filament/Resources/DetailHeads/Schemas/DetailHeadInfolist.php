<?php

namespace App\Filament\Resources\DetailHeads\Schemas;

use App\Models\DetailHead;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class DetailHeadInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('code'),
                TextEntry::make('description'),
                TextEntry::make('head_type'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (DetailHead $record): bool => $record->trashed()),
            ]);
    }
}
