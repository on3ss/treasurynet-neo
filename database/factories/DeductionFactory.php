<?php

namespace Database\Factories;

use App\Models\Bill;
use App\Models\Deduction;
use App\Models\ReceiptScheme;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Deduction>
 */
class DeductionFactory extends Factory
{
    protected $model = Deduction::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'bill_id' => Bill::factory(),
            'receipt_scheme_id' => ReceiptScheme::factory(),
            'amount' => $this->faker->randomFloat(2, 100, 50000),
        ];
    }

    /**
     * Indicate that the deduction is for a specific bill.
     */
    public function forBill(Bill $bill): static
    {
        return $this->state(fn (array $attributes) => [
            'bill_id' => $bill->id,
        ]);
    }

    /**
     * Indicate that the deduction is for a specific receipt scheme.
     */
    public function forReceiptScheme(ReceiptScheme $receiptScheme): static
    {
        return $this->state(fn (array $attributes) => [
            'receipt_scheme_id' => $receiptScheme->id,
        ]);
    }
}
