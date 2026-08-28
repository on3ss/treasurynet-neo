<?php

namespace Database\Factories;

use App\Models\Bill;
use App\Models\Payee;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payee>
 */
class PayeeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Payee::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'bill_id' => Bill::factory(),
            'reg_code' => $this->generateRegCode(),
            'name' => $this->faker->name(),
            'amount' => $this->faker->randomFloat(2, 1000, 500000),
            'ifsc' => $this->generateIfsc(),
            'account_no' => $this->faker->bankAccountNumber(),
            'rbi_endtoend_id' => null,
            'rbi_utr_id' => null,
            'rbi_utr_date' => null,
        ];
    }

    /**
     * Indicate that the payee has been processed with RBI references.
     */
    public function processed(): static
    {
        return $this->state(fn(array $attributes) => [
            'rbi_endtoend_id' => 'E2E' . now()->format('Ymd') . Str::upper(Str::random(10)),
            'rbi_utr_id' => 'UTR' . now()->format('Ymd') . Str::upper(Str::random(10)),
            'rbi_utr_date' => now()->subDays(rand(0, 5)),
        ]);
    }

    /**
     * Generate a registration code.
     */
    private function generateRegCode(): string
    {
        $prefixes = ['EMP', 'PEN', 'CON', 'VEN', 'SUP'];
        $prefix = $this->faker->randomElement($prefixes);

        return $prefix . '-' . now()->format('Y') . '-' . str_pad($this->faker->unique()->numberBetween(1, 99999), 5, '0', STR_PAD_LEFT);
    }

    /**
     * Generate a valid IFSC code.
     */
    private function generateIfsc(): string
    {
        $bankCode = strtoupper(Str::random(4));
        $branchCode = str_pad((string) $this->faker->numberBetween(1, 9999), 6, '0', STR_PAD_LEFT);

        return $bankCode . '0' . $branchCode;
    }
}