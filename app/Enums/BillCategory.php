<?php

namespace App\Enums;

enum BillCategory: string
{
    case HILLY = 'H';
    case NONHILLY = 'N';

    /**
     * Get all values as array
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get label for the enum value
     */
    public function label(): string
    {
        return match ($this) {
            self::HILLY => 'Hilly Area',
            self::NONHILLY => 'Non-Hilly Area',
        };
    }

    /**
     * Get enum from value
     */
    public static function fromValue(string $value): self
    {
        return self::tryFrom($value) ?? throw new \InvalidArgumentException(
            "Invalid BillCategory value: {$value}. Valid values are: ".implode(', ', self::values())
        );
    }

    /**
     * Get color class for UI
     */
    public function color(): string
    {
        return match ($this) {
            self::HILLY => 'primary',
            self::NONHILLY => 'warning',
        };
    }

    /**
     * Check if category is hilly
     */
    public function isHilly(): bool
    {
        return $this === self::HILLY;
    }

    /**
     * Get description
     */
    public function description(): string
    {
        return match ($this) {
            self::HILLY => 'Bills from hilly or remote areas',
            self::NONHILLY => 'Bills from non-hilly areas',
        };
    }
}
