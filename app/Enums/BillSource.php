<?php

namespace App\Enums;

enum BillSource: string
{
    case EBILLING = 'D';
    case EIS = 'E';
    case PENSION = 'T';

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
            self::EBILLING => 'EBilling',
            self::EIS => 'EIS',
            self::PENSION => 'Pension',
        };
    }

    /**
     * Get enum from value
     */
    public static function fromValue(string $value): self
    {
        return self::tryFrom($value) ?? throw new \InvalidArgumentException(
            "Invalid BillSource value: {$value}. Valid values are: " . implode(', ', self::values())
        );
    }

    /**
     * Get color class for UI
     */
    public function color(): string
    {
        return match ($this) {
            self::EBILLING => 'primary',
            self::EIS => 'warning',
            self::PENSION => 'success',
        };
    }

    /**
     * Check if source is electronic
     */
    public function isElectronic(): bool
    {
        return match ($this) {
            self::EBILLING, self::EIS => true,
            self::PENSION => false,
        };
    }
}
