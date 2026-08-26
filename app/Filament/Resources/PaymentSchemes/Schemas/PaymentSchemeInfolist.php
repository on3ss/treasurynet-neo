<?php

namespace App\Filament\Resources\PaymentSchemes\Schemas;

use App\Models\PaymentScheme;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PaymentSchemeInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('demand_number'),
                TextEntry::make('major_head'),
                TextEntry::make('submajor_head'),
                TextEntry::make('minor_head'),
                TextEntry::make('subminor_head'),
                TextEntry::make('sub_head'),
                TextEntry::make('charged_or_voted'),
                TextEntry::make('flag')
                    ->numeric(),
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
                    ->visible(fn (PaymentScheme $record): bool => $record->trashed()),
            ]);
    }
}
