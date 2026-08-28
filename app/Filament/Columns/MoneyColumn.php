<?php

namespace App\Filament\Columns;

use Filament\Tables\Columns\TextColumn;

class MoneyColumn extends TextColumn
{
    public static function make(?string $name = null): static
    {
        return parent::make($name)
            ->money(
                currency: config('app.currency', 'INR'),
                locale: config('app.locale', 'en_IN')
            )
            ->alignEnd()
            ->sortable();
    }
}