<?php

namespace App\Filament\Resources\Treasuries\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class TreasuryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code')
                    ->label(__('Code'))
                    ->required(),
                TextInput::make('name')
                    ->label(__('Name'))
                    ->required(),
                Toggle::make('is_active')
                    ->label(__('Active'))
                    ->required(),
            ]);
    }
}
