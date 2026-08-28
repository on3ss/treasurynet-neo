<?php

namespace Database\Factories;

use App\Models\Bill;
use App\Models\Gross;
use App\Models\SubDetailHead;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Gross>
 */
class GrossFactory extends Factory
{
    protected $model = Gross::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'bill_id' => Bill::factory(),
            'sub_detail_head_id' => SubDetailHead::factory(),
            'amount' => $this->faker->randomFloat(2, 100, 100000),
        ];
    }
}