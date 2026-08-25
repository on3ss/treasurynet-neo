<?php

namespace App\Filament\Resources\Ddos\Schemas;

use App\Enums\DDOType;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class DdoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('treasury_id')
                    ->relationship('treasury', 'name')
                    ->required(),
                Select::make('department_id')
                    ->relationship('department', 'name')
                    ->required(),
                TextInput::make('sequence')
                    ->required(),
                TextInput::make('code')
                    ->required(),
                TextInput::make('designation')
                    ->required(),
                Select::make('type')
                    ->options(DDOType::class)
                    ->required(),
                Textarea::make('address')
                    ->columnSpanFull(),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
