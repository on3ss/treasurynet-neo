<?php

namespace App\Filament\Resources\PaymentSchemes\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PaymentSchemeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('demand_number')
                    ->required(),
                TextInput::make('major_head')
                    ->required(),
                TextInput::make('submajor_head')
                    ->required(),
                TextInput::make('minor_head')
                    ->required(),
                TextInput::make('subminor_head')
                    ->required(),
                TextInput::make('sub_head')
                    ->required(),
                TextInput::make('charged_or_voted')
                    ->required(),
                TextInput::make('flag')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('code')
                    ->required(),
                TextInput::make('description')
                    ->required(),
                TextInput::make('sector_code'),
            ]);
    }
}
