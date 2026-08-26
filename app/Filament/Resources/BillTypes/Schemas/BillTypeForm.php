<?php

namespace App\Filament\Resources\BillTypes\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class BillTypeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('detail_head_id')
                    ->required()
                    ->numeric(),
                TextInput::make('code')
                    ->required(),
                TextInput::make('description')
                    ->required(),
            ]);
    }
}
