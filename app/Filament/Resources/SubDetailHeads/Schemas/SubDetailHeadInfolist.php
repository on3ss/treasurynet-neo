<?php

namespace App\Filament\Resources\SubDetailHeads\Schemas;

use App\Models\SubDetailHead;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class SubDetailHeadInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('detailHead.id')
                    ->label('Detail head'),
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
                    ->visible(fn (SubDetailHead $record): bool => $record->trashed()),
            ]);
    }
}
