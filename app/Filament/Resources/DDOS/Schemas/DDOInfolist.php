<?php

namespace App\Filament\Resources\DDOS\Schemas;

use App\Models\DDO;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class DDOInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('treasury.name')
                    ->label('Treasury'),
                TextEntry::make('department.name')
                    ->label('Department'),
                TextEntry::make('sequence'),
                TextEntry::make('code'),
                TextEntry::make('designation'),
                TextEntry::make('type')
                    ->badge(),
                TextEntry::make('address')
                    ->placeholder('-')
                    ->columnSpanFull(),
                IconEntry::make('is_active')
                    ->boolean(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (DDO $record): bool => $record->trashed()),
            ]);
    }
}
