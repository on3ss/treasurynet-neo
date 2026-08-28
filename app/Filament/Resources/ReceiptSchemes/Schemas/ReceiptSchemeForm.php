<?php

namespace App\Filament\Resources\ReceiptSchemes\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ReceiptSchemeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
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
                TextInput::make('code')
                    ->required(),
                TextInput::make('description')
                    ->required(),
                TextInput::make('sector_code'),
            ]);
    }
}
