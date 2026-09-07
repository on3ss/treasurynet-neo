<?php

namespace App\Filament\Resources\Bills\Schemas;

use App\Filament\Entry\MoneyEntry;
use App\Filament\Resources\Bills\RelationManagers\DeductionsRelationManager;
use App\Filament\Resources\Bills\RelationManagers\GrossesRelationManager;
use App\Filament\Resources\Bills\RelationManagers\PayeesRelationManager;
use App\Models\Bill;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Njxqlus\Filament\Components\Forms\RelationManager;

class BillInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Tabs::make()
                ->tabs([
                    Tab::make('Primary Details')
                        ->schema([
                            self::billInformationSection(),
                            self::administrativeSection(),
                            self::financialSection(),
                        ]),
                    Tab::make('Payees')
                        ->schema([
                            RelationManager::make()->manager(PayeesRelationManager::class)->lazy(false),
                        ]),
                    Tab::make('Grosses and Deductions')
                        ->schema([
                            RelationManager::make()->manager(GrossesRelationManager::class)->lazy(false),
                            RelationManager::make()->manager(DeductionsRelationManager::class)->lazy(false),
                        ]),
                ]),
        ]);
    }

    private static function billInformationSection(): Section
    {
        return Section::make('Bill Information')
            ->icon('heroicon-o-document-text')
            ->columns([
                'default' => 1,
                'sm' => 2,
            ])
            ->schema([
                TextEntry::make('financialYear.name')
                    ->label('Financial year'),

                TextEntry::make('bill_date')
                    ->label('Bill date')
                    ->date(),

                TextEntry::make('form.name')
                    ->label('Form')
                    ->columnSpanFull(),

                TextEntry::make('billType.display_name')
                    ->label('Bill type')
                    ->placeholder('-'),

                TextEntry::make('source_bill_id')
                    ->label('Source bill ID')
                    ->placeholder('-'),

                self::enumEntry(
                    name: 'source',
                    label: 'Source',
                    resolver: fn (Bill $bill) => $bill->source,
                ),

                self::enumEntry(
                    name: 'category',
                    label: 'Category',
                    resolver: fn (Bill $bill) => $bill->category,
                ),
            ]);
    }

    private static function administrativeSection(): Section
    {
        return Section::make('Administrative & Accounting')
            ->icon('heroicon-o-building-office-2')
            ->columns([
                'default' => 1,
                'sm' => 2,
            ])
            ->schema([
                TextEntry::make('treasury.name')
                    ->label('Treasury')
                    ->placeholder('-'),

                TextEntry::make('ddo.display_name')
                    ->label('DDO')
                    ->placeholder('-'),

                TextEntry::make('detailHead.display_name')
                    ->label('Detail head')
                    ->placeholder('-')
                    ->columnSpanFull(),

                TextEntry::make('paymentScheme.display_name')
                    ->label('Payment scheme')
                    ->placeholder('-')
                    ->columnSpanFull(),
            ]);
    }

    private static function financialSection(): Section
    {
        return Section::make('Financial Information')
            ->icon('heroicon-o-banknotes')
            ->columnSpanFull()
            ->columns([
                'default' => 1,
                'sm' => 2,
                'lg' => 4,
            ])
            ->schema([
                MoneyEntry::make('gross_amount')
                    ->label('Gross amount'),

                MoneyEntry::make('net_amount')
                    ->label('Net amount'),

                self::enumEntry(
                    name: 'payment_type',
                    label: 'Payment type',
                    resolver: fn (Bill $bill) => $bill->payment_type,
                ),

                TextEntry::make('is_urgent')
                    ->label('Urgency')
                    ->badge()
                    ->formatStateUsing(
                        fn (bool $state): string => $state
                        ? 'Urgent'
                        : 'Normal'
                    )
                    ->color(
                        fn (bool $state): string => $state
                        ? 'danger'
                        : 'gray'
                    ),
            ]);
    }

    private static function enumEntry(
        string $name,
        string $label,
        callable $resolver,
    ): TextEntry {
        return TextEntry::make($name)
            ->label($label)
            ->placeholder('-')
            ->badge()
            ->formatStateUsing(
                fn (Bill $bill) => $resolver($bill)?->label() ?? '-'
            )
            ->color(
                fn (Bill $bill) => $resolver($bill)?->color() ?? 'gray'
            );
    }
}
