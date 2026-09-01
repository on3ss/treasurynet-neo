<?php

namespace App\Filament\Resources\Bills\Schemas;

use App\Enums\BillCategory;
use App\Enums\BillSource;
use App\Enums\PaymentType;
use App\Models\BillType;
use App\Models\Ddo;
use App\Models\DetailHead;
use App\Models\PaymentScheme;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BillForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                self::billInformation(),

                self::administrativeInformation(),

                self::financialInformation(),
            ]);
    }

    private static function billInformation(): Section
    {
        return Section::make('Bill Information')
            ->icon('heroicon-o-document-text')
            ->columns([
                'default' => 1,
                'md' => 2,
                'xl' => 3,
            ])
            ->schema([
                Select::make('financial_year_id')
                    ->label('Financial year')
                    ->relationship('financialYear', 'name')
                    ->native(false)
                    ->required(),

                DateTimePicker::make('bill_date')
                    ->label('Bill date')
                    ->required(),

                Select::make('form_id')
                    ->label('Form')
                    ->relationship('form', 'name')
                    ->native(false)
                    ->required(),

                Select::make('bill_type_id')
                    ->label('Bill type')
                    ->relationship('billType', 'description')
                    ->getOptionLabelFromRecordUsing(
                        fn(BillType $record): string => $record->display_name
                    )
                    ->searchable()
                    ->native(false)
                    ->required()
                    ->columnSpan(2),

                TextInput::make('source_bill_id')
                    ->label('Source bill ID')
                    ->placeholder('-'),

                Select::make('source')
                    ->label('Source')
                    ->options(BillSource::class)
                    ->native(false)
                    ->required(),

                Select::make('category')
                    ->label('Category')
                    ->options(BillCategory::class)
                    ->native(false)
                    ->required(),
            ]);
    }

    private static function administrativeInformation(): Section
    {
        return Section::make('Administrative & Accounting')
            ->icon('heroicon-o-building-office-2')
            ->columns([
                'default' => 1,
                'md' => 2,
                'xl' => 2,
            ])
            ->schema([
                Select::make('treasury_id')
                    ->label('Treasury')
                    ->relationship('treasury', 'name')
                    ->searchable()
                    ->native(false)
                    ->required(),

                Select::make('ddo_id')
                    ->label('DDO')
                    ->relationship('ddo', 'id')
                    ->getOptionLabelFromRecordUsing(
                        fn(Ddo $record): string => $record->display_name
                    )
                    ->searchable()
                    ->native(false)
                    ->required(),

                Select::make('detail_head_id')
                    ->label('Detail head')
                    ->relationship('detailHead', 'id')
                    ->getOptionLabelFromRecordUsing(
                        fn(DetailHead $record): string => $record->display_name
                    )
                    ->searchable()
                    ->native(false)
                    ->required(),

                Select::make('payment_scheme_id')
                    ->label('Payment scheme')
                    ->relationship('paymentScheme', 'id')
                    ->getOptionLabelFromRecordUsing(
                        fn(PaymentScheme $record): string => $record->display_name
                    )
                    ->searchable()
                    ->native(false)
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    private static function financialInformation(): Section
    {
        return Section::make('Financial Information')
            ->icon('heroicon-o-banknotes')
            ->columns([
                'default' => 1,
                'md' => 2,
                'xl' => 4,
            ])
            ->schema([
                TextInput::make('gross_amount')
                    ->label('Gross amount')
                    ->required()
                    ->numeric(),

                TextInput::make('net_amount')
                    ->label('Net amount')
                    ->required()
                    ->numeric(),

                Select::make('payment_type')
                    ->label('Payment type')
                    ->options(PaymentType::class)
                    ->native(false)
                    ->required(),

                Toggle::make('is_urgent')
                    ->label('Urgency')
                    ->onColor('danger')
                    ->offColor('gray'),
            ]);
    }
}