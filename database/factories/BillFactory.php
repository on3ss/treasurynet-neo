<?php

namespace Database\Factories;

use App\Enums\BillCategory;
use App\Enums\BillSource;
use App\Enums\PaymentType;
use App\Models\Bill;
use App\Models\BillType;
use App\Models\Ddo;
use App\Models\DetailHead;
use App\Models\FinancialYear;
use App\Models\Form;
use App\Models\PaymentScheme;
use App\Models\Treasury;
use App\Services\BillNoSequenceService;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Bill>
 */
class BillFactory extends Factory
{
    protected $model = Bill::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Get current financial year
        $financialYear = $this->getCurrentFinancialYear();

        // Get financial year string (e.g., '2026-2027')
        $financialYearString = $this->getFinancialYearString($financialYear);

        // Get random treasury
        $treasury = Treasury::inRandomOrder()->first() ?? Treasury::factory();

        // Get random DDO
        $ddo = Ddo::inRandomOrder()->first() ?? Ddo::factory();

        // Generate bill number using sequence service
        $billNoSequenceService = app(BillNoSequenceService::class);
        $billNumber = $billNoSequenceService->getNextBillNo(
            $treasury->id,
            $financialYearString
        );

        // Generate amounts
        $netAmount = $this->faker->numberBetween(1000, 1000000);
        $grossAmount = $netAmount + $this->faker->numberBetween(100, 10000);

        return [
            'financial_year_id' => $financialYear->id,
            'treasury_id' => $treasury->id,
            'ddo_id' => $ddo->id,
            'payment_scheme_id' => PaymentScheme::inRandomOrder()->first()?->id ?? PaymentScheme::factory(),
            'detail_head_id' => DetailHead::inRandomOrder()->first()?->id ?? DetailHead::factory(),
            'bill_type_id' => BillType::inRandomOrder()->first()?->id ?? BillType::factory(),
            'form_id' => Form::inRandomOrder()->first()?->id ?? Form::factory(),
            'bill_number' => (int) $billNumber,
            'source_bill_id' => $this->faker->unique()->bothify('SRC-####-????'),
            'bill_date' => $this->faker->dateTimeBetween(
                $financialYear->start_date ?? now()->startOfYear(),
                min($financialYear->end_date ?? now(), now())
            ),
            'source' => $this->faker->randomElement(BillSource::cases())->value,
            'category' => $this->faker->randomElement(BillCategory::cases())->value,
            'net_amount' => $netAmount,
            'gross_amount' => $grossAmount,
            'is_urgent' => $this->faker->boolean(20),
            'payment_type' => $this->faker->randomElement(PaymentType::cases())->value,
        ];
    }

    /**
     * Get current financial year.
     */
    private function getCurrentFinancialYear(): FinancialYear
    {
        $now = now();

        // Try to find the financial year that contains the current date
        $currentFinancialYear = FinancialYear::where('start_date', '<=', $now)
            ->where('end_date', '>=', $now)
            ->first();

        // If not found, try to find the most recent one
        if (! $currentFinancialYear) {
            $currentFinancialYear = FinancialYear::where('end_date', '>=', $now)
                ->orderBy('start_date')
                ->first();
        }

        // If still not found, get the latest one
        if (! $currentFinancialYear) {
            $currentFinancialYear = FinancialYear::latest('start_date')->first();
        }

        // If no financial years exist, create one
        if (! $currentFinancialYear) {
            $currentFinancialYear = FinancialYear::factory()->create();
        }

        return $currentFinancialYear;
    }

    /**
     * Get financial year string (e.g., '2026-2027')
     */
    private function getFinancialYearString(FinancialYear $financialYear): string
    {
        // Check if model has 'name' field
        if (isset($financialYear->name)) {
            return $financialYear->name;
        }

        // Check if model has 'year' field
        if (isset($financialYear->year)) {
            return $financialYear->year;
        }

        // Generate from start_date and end_date
        $startYear = $financialYear->start_date?->format('Y') ?? now()->format('Y');
        $endYear = $financialYear->end_date?->format('Y') ?? (now()->addYear())->format('Y');

        return $startYear.'-'.$endYear;
    }

    /**
     * Set specific financial year.
     */
    public function forFinancialYear(FinancialYear $financialYear): static
    {
        return $this->state(fn (array $attributes) => [
            'financial_year_id' => $financialYear->id,
        ]);
    }

    /**
     * Set specific treasury.
     */
    public function forTreasury(Treasury $treasury): static
    {
        return $this->state(fn (array $attributes) => [
            'treasury_id' => $treasury->id,
        ]);
    }

    /**
     * Set specific DDO.
     */
    public function forDdo(Ddo $ddo): static
    {
        return $this->state(fn (array $attributes) => [
            'ddo_id' => $ddo->id,
        ]);
    }

    /**
     * Set bill as urgent.
     */
    public function urgent(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_urgent' => true,
        ]);
    }

    /**
     * Set payment type as electronic.
     */
    public function electronicPayment(): static
    {
        return $this->state(fn (array $attributes) => [
            'payment_type' => PaymentType::ELECTRONIC->value,
        ]);
    }

    /**
     * Set payment type as cash.
     */
    public function cashPayment(): static
    {
        return $this->state(fn (array $attributes) => [
            'payment_type' => PaymentType::CASH->value,
        ]);
    }

    /**
     * Set bill category as hilly.
     */
    public function hillyCategory(): static
    {
        return $this->state(fn (array $attributes) => [
            'category' => BillCategory::HILLY->value,
        ]);
    }

    /**
     * Set bill source as EBilling.
     */
    public function ebillingSource(): static
    {
        return $this->state(fn (array $attributes) => [
            'source' => BillSource::EBILLING->value,
        ]);
    }

    /**
     * Set bill source as EIS.
     */
    public function eisSource(): static
    {
        return $this->state(fn (array $attributes) => [
            'source' => BillSource::EIS->value,
        ]);
    }

    /**
     * Set bill source as Pension.
     */
    public function pensionSource(): static
    {
        return $this->state(fn (array $attributes) => [
            'source' => BillSource::PENSION->value,
        ]);
    }
}
