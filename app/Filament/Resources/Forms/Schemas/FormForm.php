<?php

namespace App\Filament\Resources\Forms\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class FormForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('detail_head_id')
                    ->required()
                    ->numeric(),
                TextInput::make('bill_type_id')
                    ->required()
                    ->numeric(),
                TextInput::make('name')
                    ->required(),
            ]);
    }
}
