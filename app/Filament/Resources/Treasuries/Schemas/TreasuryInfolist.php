<?php

namespace App\Filament\Resources\Treasuries\Schemas;

use App\Models\Treasury;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class TreasuryInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('code')
                    ->label(__('Code')),
                TextEntry::make('name')
                    ->label(__('Name')),
                IconEntry::make('is_active')
                    ->label(__('Active'))
                    ->boolean(),
                TextEntry::make('created_at')
                    ->label(__('Created At'))
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->label(__('Updated At'))
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->label(__('Deleted At'))
                    ->dateTime()
                    ->visible(fn(Treasury $record): bool => $record->trashed()),
            ]);
    }
}
