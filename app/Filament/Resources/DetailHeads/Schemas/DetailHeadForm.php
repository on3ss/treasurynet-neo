<?php

namespace App\Filament\Resources\DetailHeads\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class DetailHeadForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('head')
                    ->required(),
                TextInput::make('description')
                    ->required(),
                TextInput::make('head_type')
                    ->required()
                    ->default('NA'),
                TextInput::make('deduction_applicability'),
            ]);
    }
}
