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
            "Invalid BillSource value: {$value}. Valid values are: ".implode(', ', self::values())
        );
    }

    /**
     * Get color class for UI
     */
    public function colorClass(): string
    {
        return match ($this) {
            self::EBILLING => 'bg-blue-100 text-blue-800',
            self::EIS => 'bg-green-100 text-green-800',
            self::PENSION => 'bg-purple-100 text-purple-800',
        };
    }

    /**
     * Get icon for UI
     */
    public function icon(): string
    {
        return match ($this) {
            self::EBILLING => 'heroicon-o-document-text',
            self::EIS => 'heroicon-o-cpu-chip',
            self::PENSION => 'heroicon-o-user-group',
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
