<?php

namespace App\Filament\Resources\Bills\Schemas;

use App\Filament\Entry\MoneyEntry;
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
                    ->label('DDO')
                    ->formatStateUsing(fn(Bill $bill) => $bill->ddo->code . ' - ' . $bill->ddo->designation),
                TextEntry::make('paymentScheme.id')
                    ->label('Payment scheme')
                    ->formatStateUsing(fn(Bill $bill) => $bill->paymentScheme->code . ' - ' . $bill->paymentScheme->description),
                TextEntry::make('detailHead.id')
                    ->label('Detail head')
                    ->formatStateUsing(fn(Bill $bill) => $bill->detailHead->code . ' - ' . $bill->detailHead->description),
                TextEntry::make('billType.id')
                    ->label('Bill type')
                    ->formatStateUsing(fn(Bill $bill) => $bill->billType->code . ' - ' . $bill->billType->description),
                TextEntry::make('form.name')
                    ->label('Form'),
                TextEntry::make('bill_number')
                    ->numeric(),
                TextEntry::make('source_bill_id')
                    ->label('Source bill ID')
                    ->placeholder('-'),
                TextEntry::make('bill_date')
                    ->date(),
                TextEntry::make('source')
                    ->badge()
                    ->formatStateUsing(fn(Bill $bill) => $bill->source->label())
                    ->color(fn(Bill $bill) => $bill->source->color()),
                TextEntry::make('category')
                    ->badge()
                    ->formatStateUsing(fn(Bill $bill) => $bill->category->label())
                    ->color(fn(Bill $bill) => $bill->category->color()),
                MoneyEntry::make('net_amount'),
                MoneyEntry::make('gross_amount'),
                IconEntry::make('is_urgent')
                    ->boolean(),
                TextEntry::make('payment_type')
                    ->badge()
                    ->formatStateUsing(fn(Bill $bill) => $bill->payment_type->label())
                    ->color(fn(Bill $bill) => $bill->payment_type->color()),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn(Bill $record): bool => $record->trashed()),
            ]);
    }
}
