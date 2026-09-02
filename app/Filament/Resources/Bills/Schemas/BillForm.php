<?php

namespace App\Filament\Resources\Bills\Schemas;

use App\Enums\BillCategory;
use App\Enums\BillSource;
use App\Enums\PaymentType;
use App\Filament\Forms\Components\RelationshipSelect;
use App\Models\BillType;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use UnitEnum;

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
                    ->options(self::getEnumOptions(BillSource::class))
                    ->native(false)
                    ->required(),

                Select::make('category')
                    ->label('Category')
                    ->options(self::getEnumOptions(BillCategory::class))
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
                RelationshipSelect::makeRelationship(
                    name: 'treasury_id',
                    label: 'Treasury',
                    relationship: 'treasury',
                ),

                RelationshipSelect::makeRelationship(
                    name: 'ddo_id',
                    label: 'DDO',
                    relationship: 'ddo',
                ),

                RelationshipSelect::makeRelationship(
                    name: 'detail_head_id',
                    label: 'Detail head',
                    relationship: 'detailHead',
                ),

                RelationshipSelect::makeRelationship(
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
                    ->options(self::getEnumOptions(PaymentType::class))
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
     * Get enum options as key-value pairs for select fields.
     *
     * @param  class-string<UnitEnum>  $enumClass
     * @return array<string, string>
     */
    private static function getEnumOptions(string $enumClass): array
    {
        return collect($enumClass::cases())
            ->mapWithKeys(fn (UnitEnum $enum) => [
                $enum->value => $enum->label(),
            ])
            ->toArray();
    }
}
