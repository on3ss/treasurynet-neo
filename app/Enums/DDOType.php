<?php

namespace App\Enums;

enum DDOType: string
{
    case GOVERNMENT = 'GO';
    case NON_GOVERNMENT = 'NGO';

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
            self::GOVERNMENT => 'Government Organization',
            self::NON_GOVERNMENT => 'Non-Government Organization',
        };
    }

    /**
     * Get enum from value
     */
    public static function fromValue(string $value): self
    {
        return match ($value) {
            'GO' => self::GOVERNMENT,
            'NGO' => self::NON_GOVERNMENT,
            default => throw new \InvalidArgumentException("Invalid DDO type: {$value}")
        };
    }

    /**
     * Check if type is government
     */
    public function isGovernment(): bool
    {
        return $this === self::GOVERNMENT;
    }

    /**
     * Check if type is non-government
     */
    public function isNonGovernment(): bool
    {
        return $this === self::NON_GOVERNMENT;
    }

    /**
     * Get color class for UI (optional)
     */
    public function colorClass(): string
    {
        return match ($this) {
            self::GOVERNMENT => 'bg-blue-100 text-blue-800',
            self::NON_GOVERNMENT => 'bg-green-100 text-green-800',
        };
    }
}