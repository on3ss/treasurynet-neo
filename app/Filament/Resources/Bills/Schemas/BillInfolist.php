<?php

namespace App\Filament\Resources\Bills\Schemas;

use App\Models\Bill;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class BillInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('financialYear.name')
                    ->label('Financial year'),
                TextEntry::make('treasury.name')
                    ->label('Treasury'),
                TextEntry::make('ddo.id')
                    ->label('Ddo'),
                TextEntry::make('paymentScheme.id')
                    ->label('Payment scheme'),
                TextEntry::make('detailHead.id')
                    ->label('Detail head'),
                TextEntry::make('billType.id')
                    ->label('Bill type'),
                TextEntry::make('form.name')
                    ->label('Form'),
                TextEntry::make('bill_number')
                    ->numeric(),
                TextEntry::make('source_bill_id')
                    ->placeholder('-'),
                TextEntry::make('bill_date')
                    ->dateTime(),
                TextEntry::make('source')
                    ->badge(),
                TextEntry::make('category')
                    ->badge(),
                TextEntry::make('net_amount')
                    ->numeric(),
                TextEntry::make('gross_amount')
                    ->numeric(),
                IconEntry::make('is_urgent')
                    ->boolean(),
                TextEntry::make('payment_type')
                    ->badge(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Bill $record): bool => $record->trashed()),
            ]);
    }
}
