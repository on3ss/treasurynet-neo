<?php

namespace App\Filament\Forms\Components;

use Filament\Forms\Components\TextInput;

class MoneyInput extends TextInput
{
    public static function make(?string $name = null): static
    {
        return parent::make($name)
            ->numeric()
            ->prefix(config('app.currency', '₹'))
            ->inputMode('decimal')
            ->step('0.01')
            ->minValue(0)
            ->placeholder('0.00');
    }
}
