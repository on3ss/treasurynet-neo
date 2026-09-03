<?php

namespace App\Filament\Summarizers;

use Filament\Tables\Columns\Summarizers\Sum;

class MoneySum extends Sum
{
    public static function make(?string $name = null): static
    {
        return parent::make($name)
            ->money(
                currency: config('app.currency', 'INR'),
                locale: config('app.locale', 'en_IN')
            );
    }
}
