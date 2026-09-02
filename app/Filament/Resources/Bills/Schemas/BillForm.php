<?php

namespace App\Filament\Resources\Bills\Schemas;

use App\Enums\BillCategory;
use App\Enums\BillSource;
use App\Enums\PaymentType;
use App\Models\BillType;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BillForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Grid::make([
                'default' => 1,
                'lg' => 2,
            ])
                ->columnSpanFull()
                ->schema([
                    self::billInformation(),
                    self::administrativeInformation(),

                    self::financialInformation()
                        ->columnSpanFull(),
                ]),
        ]);
    }

    private static function billInformation(): Section
    {
        return Section::make('Bill Information')
            ->icon('heroicon-o-document-text')
            ->columns([
                'default' => 1,
                'sm' => 2,
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
                    ->relationship('billType', 'id')
                    ->getOptionLabelFromRecordUsing(
                        fn (BillType $record): string => $record->display_name
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
                'sm' => 2,
            ])
            ->schema([
                self::relationshipSelect(
                    name: 'treasury_id',
                    label: 'Treasury',
                    relationship: 'treasury',
                ),

                self::relationshipSelect(
                    name: 'ddo_id',
                    label: 'DDO',
                    relationship: 'ddo',
                ),

                self::relationshipSelect(
                    name: 'detail_head_id',
                    label: 'Detail head',
                    relationship: 'detailHead',
                ),

                self::relationshipSelect(
                    name: 'payment_scheme_id',
                    label: 'Payment scheme',
                    relationship: 'paymentScheme',
                )->columnSpanFull(),
            ]);
    }

    private static function financialInformation(): Section
    {
        return Section::make('Financial Information')
            ->icon('heroicon-o-banknotes')
            ->columns([
                'default' => 1,
                'sm' => 2,
                'xl' => 4,
            ])
            ->schema([
                TextInput::make('gross_amount')
                    ->label('Gross amount')
                    ->numeric()
                    ->required(),

                TextInput::make('net_amount')
                    ->label('Net amount')
                    ->numeric()
                    ->required(),

                Select::make('payment_type')
                    ->label('Payment type')
                    ->options(PaymentType::class)
                    ->native(false)
                    ->required(),

                Toggle::make('is_urgent')
                    ->label('Urgency')
                    ->inline(false)
                    ->onColor('danger')
                    ->offColor('gray'),
            ]);
    }

    /**
     * Create a searchable relationship select using the model's
     * display_name attribute.
     */
    private static function relationshipSelect(
        string $name,
        string $label,
        string $relationship
    ): Select {
        return Select::make($name)
            ->label($label)
            ->relationship($relationship, 'id')
            ->getOptionLabelFromRecordUsing(
                fn ($record): string => $record->display_name
            )
            ->searchable()
            ->native(false)
            ->required();
    }
}
