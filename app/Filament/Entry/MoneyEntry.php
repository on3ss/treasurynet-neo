<?php

namespace App\Filament\Entry;

use Filament\Infolists\Components\TextEntry;

class MoneyEntry extends TextEntry
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