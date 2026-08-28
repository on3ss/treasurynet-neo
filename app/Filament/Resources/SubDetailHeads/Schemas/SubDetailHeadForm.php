<?php

namespace App\Filament\Resources\SubDetailHeads\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SubDetailHeadForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('detail_head_id')
                    ->relationship('detailHead', 'id')
                    ->required(),
                TextInput::make('code')
                    ->required(),
                TextInput::make('description')
                    ->required(),
            ]);
    }
}
