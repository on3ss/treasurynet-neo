<?php

namespace App\Filament\Resources\Bills\Schemas;

use App\Enums\BillCategory;
use App\Enums\BillSource;
use App\Enums\PaymentType;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class BillForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('financial_year_id')
                    ->relationship('financialYear', 'name')
                    ->required(),
                Select::make('treasury_id')
                    ->relationship('treasury', 'name')
                    ->required(),
                Select::make('ddo_id')
                    ->relationship('ddo', 'id')
                    ->required(),
                Select::make('payment_scheme_id')
                    ->relationship('paymentScheme', 'id')
                    ->required(),
                Select::make('detail_head_id')
                    ->relationship('detailHead', 'id')
                    ->required(),
                Select::make('bill_type_id')
                    ->relationship('billType', 'id')
                    ->required(),
                Select::make('form_id')
                    ->relationship('form', 'name')
                    ->required(),
                TextInput::make('bill_number')
                    ->required()
                    ->numeric(),
                TextInput::make('source_bill_id'),
                DateTimePicker::make('bill_date')
                    ->required(),
                Select::make('source')
                    ->options(BillSource::class)
                    ->required(),
                Select::make('category')
                    ->options(BillCategory::class)
                    ->required(),
                TextInput::make('net_amount')
                    ->required()
                    ->numeric(),
                TextInput::make('gross_amount')
                    ->required()
                    ->numeric(),
                Toggle::make('is_urgent')
                    ->required(),
                Select::make('payment_type')
                    ->options(PaymentType::class)
                    ->required(),
            ]);
    }
}
