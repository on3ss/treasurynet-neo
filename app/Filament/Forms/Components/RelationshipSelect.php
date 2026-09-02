<?php

namespace App\Filament\Forms\Components;

use Filament\Forms\Components\Select;

class RelationshipSelect extends Select
{
    /**
     * Create a new instance of the relationship select component.
     */
    public static function makeRelationship(
        string $name,
        string $label,
        string $relationship
    ): static {
        return static::make($name)
            ->label($label)
            ->relationship($relationship, 'id')
            ->getOptionLabelFromRecordUsing(
                fn ($record): string => $record->display_name
            )
            ->searchable()
            ->native(false)
            ->required();
    }
}
