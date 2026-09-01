<?php

namespace App\Enums;

enum PaymentType: string
{
    case ELECTRONIC = 'E';
    case CASH = 'K';

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
            self::ELECTRONIC => 'Electronic Payment',
            self::CASH => 'Cash Payment',
        };
    }

    /**
     * Get enum from value
     */
    public static function fromValue(string $value): self
    {
        return self::tryFrom($value) ?? throw new \InvalidArgumentException(
            "Invalid PaymentType value: {$value}. Valid values are: " . implode(', ', self::values())
        );
    }

    /**
     * Get color class for UI
     */
    public function color(): string
    {
        return match ($this) {
            self::ELECTRONIC => 'primary',
            self::CASH => 'warning',
        };
    }

    /**
     * Check if payment is electronic
     */
    public function isElectronic(): bool
    {
        return $this === self::ELECTRONIC;
    }

    /**
     * Get icon for UI
     */
    public function icon(): string
    {
        return match ($this) {
            self::ELECTRONIC => 'heroicon-o-banknotes',
            self::CASH => 'heroicon-o-currency-rupee',
        };
    }
}
