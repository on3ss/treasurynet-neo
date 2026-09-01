<?php

namespace App\Filament\Resources\Bills\Schemas;

use App\Filament\Entry\MoneyEntry;
use App\Models\Bill;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BillInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make()
                    ->columnSpanFull()
                    ->columns([
                        'default' => 1,
                        'xl' => 2,
                    ])
                    ->schema([

                        /*
                         * Bill Information
                         */
                        Section::make('Bill Information')
                            ->icon('heroicon-o-document-text')
                            ->columns([
                                'default' => 1,
                                'md' => 2,
                                'xl' => 4,
                            ])
                            ->schema([
                                // Row 1
                                TextEntry::make('financialYear.name')
                                    ->label('Financial year')
                                    ->columnSpan(1),

                                TextEntry::make('bill_date')
                                    ->label('Bill date')
                                    ->date()
                                    ->columnSpan(1),

                                TextEntry::make('form.name')
                                    ->label('Form')
                                    ->columnSpan(2),

                                // Row 2
                                TextEntry::make('billType.display_name')
                                    ->label('Bill type')
                                    ->placeholder('-')
                                    ->columnSpan(2),

                                TextEntry::make('source_bill_id')
                                    ->label('Source bill ID')
                                    ->placeholder('-')
                                    ->columnSpan(2),

                                // Row 3
                                TextEntry::make('source')
                                    ->label('Source')
                                    ->badge()
                                    ->formatStateUsing(
                                        fn(Bill $bill) => $bill->source?->label() ?? '-'
                                    )
                                    ->color(
                                        fn(Bill $bill) => $bill->source?->color() ?? 'gray'
                                    )
                                    ->columnSpan(2),

                                TextEntry::make('category')
                                    ->label('Category')
                                    ->badge()
                                    ->formatStateUsing(
                                        fn(Bill $bill) => $bill->category?->label() ?? '-'
                                    )
                                    ->color(
                                        fn(Bill $bill) => $bill->category?->color() ?? 'gray'
                                    )
                                    ->columnSpan(2),
                            ]),

                        /*
                         * Administrative & Accounting
                         */
                        Section::make('Administrative & Accounting')
                            ->icon('heroicon-o-building-office-2')
                            ->columns([
                                'default' => 1,
                                'md' => 2,
                                'xl' => 4,
                            ])
                            ->schema([
                                // Row 1
                                TextEntry::make('treasury.name')
                                    ->label('Treasury')
                                    ->placeholder('-')
                                    ->columnSpan(2),

                                TextEntry::make('ddo.display_name')
                                    ->label('DDO')
                                    ->placeholder('-')
                                    ->columnSpan(2),

                                // Row 2
                                TextEntry::make('detailHead.display_name')
                                    ->label('Detail head')
                                    ->placeholder('-')
                                    ->columnSpanFull(),

                                // Row 3
                                TextEntry::make('paymentScheme.display_name')
                                    ->label('Payment scheme')
                                    ->placeholder('-')
                                    ->columnSpanFull(),
                            ]),

                        /*
                         * Financial Information
                         */
                        Section::make('Financial Information')
                            ->icon('heroicon-o-banknotes')
                            ->columnSpanFull()
                            ->columns([
                                'default' => 2,
                                'md' => 4,
                            ])
                            ->schema([
                                MoneyEntry::make('gross_amount')
                                    ->label('Gross amount'),

                                MoneyEntry::make('net_amount')
                                    ->label('Net amount'),

                                TextEntry::make('payment_type')
                                    ->label('Payment type')
                                    ->badge()
                                    ->formatStateUsing(
                                        fn(Bill $bill) => $bill->payment_type?->label() ?? '-'
                                    )
                                    ->color(
                                        fn(Bill $bill) => $bill->payment_type?->color() ?? 'gray'
                                    ),

                                TextEntry::make('is_urgent')
                                    ->label('Urgency')
                                    ->badge()
                                    ->formatStateUsing(
                                        fn(bool $state): string => $state ? 'Urgent' : 'Normal'
                                    )
                                    ->color(
                                        fn(bool $state): string => $state ? 'danger' : 'gray'
                                    ),
                            ]),
                    ]),
            ]);
    }
}
