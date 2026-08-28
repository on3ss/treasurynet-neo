<?php

namespace Database\Seeders;

use App\Enums\BillCategory;
use App\Enums\BillSource;
use App\Enums\PaymentType;
use App\Models\Bill;
use App\Models\BillNoSequence;
use App\Models\BillType;
use App\Models\Ddo;
use App\Models\DetailHead;
use App\Models\FinancialYear;
use App\Models\Form;
use App\Models\Payee;
use App\Models\PaymentScheme;
use App\Models\Treasury;
use App\Services\BillNoSequenceService;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BillSeeder extends Seeder
{
    protected BillNoSequenceService $billNoSequenceService;

    public function __construct(BillNoSequenceService $billNoSequenceService)
    {
        $this->billNoSequenceService = $billNoSequenceService;
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get current financial year
        $currentFinancialYear = $this->getCurrentFinancialYear();

        if (!$currentFinancialYear) {
            $this->command->error('No financial year found. Please seed financial years first.');

            return;
        }

        // Get financial year string (e.g., '2026-2027')
        $financialYearString = $this->getFinancialYearString($currentFinancialYear);

        $this->command->info("Seeding bills for financial year: {$financialYearString}");

        // Get all treasuries
        $treasuries = Treasury::all();

        if ($treasuries->isEmpty()) {
            $this->command->error('No treasuries found. Please seed treasuries first.');

            return;
        }

        // Get all DDOs
        $ddos = Ddo::all();

        if ($ddos->isEmpty()) {
            $this->command->error('No DDOs found. Please seed DDOs first.');

            return;
        }

        // Initialize bill number sequences for all treasuries
        $this->initializeSequences($financialYearString, $treasuries);

        // Generate bills for each treasury
        foreach ($treasuries as $treasury) {
            $this->seedBillsForTreasury($currentFinancialYear, $financialYearString, $treasury, $ddos);
        }
    }

    /**
     * Get current financial year.
     */
    private function getCurrentFinancialYear(): ?FinancialYear
    {
        $now = now();

        // Try to find the financial year that contains the current date
        $currentFinancialYear = FinancialYear::where('start_date', '<=', $now)
            ->where('end_date', '>=', $now)
            ->first();

        // If not found, try to find the most recent one
        if (!$currentFinancialYear) {
            $currentFinancialYear = FinancialYear::where('end_date', '>=', $now)
                ->orderBy('start_date')
                ->first();
        }

        // If still not found, get the latest one
        if (!$currentFinancialYear) {
            $currentFinancialYear = FinancialYear::latest('start_date')->first();
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

        return $startYear . '-' . $endYear;
    }

    /**
     * Initialize bill number sequences for all treasuries.
     */
    private function initializeSequences(string $financialYear, $treasuries): void
    {
        $count = 0;

        foreach ($treasuries as $treasury) {
            $exists = BillNoSequence::where('treasury_id', $treasury->id)
                ->where('financial_year', $financialYear)
                ->exists();

            if (!$exists) {
                BillNoSequence::create([
                    'treasury_id' => $treasury->id,
                    'financial_year' => $financialYear,
                    'last_sequence_no' => 0,
                ]);
                $count++;
            }
        }

        $this->command->info("Initialized {$count} bill number sequences.");
    }

    /**
     * Seed bills for a specific treasury.
     */
    private function seedBillsForTreasury(
        FinancialYear $financialYear,
        string $financialYearString,
        Treasury $treasury,
        $ddos
    ): void {
        // Generate random number of bills (10-30) for each treasury
        $billCount = rand(10, 30);

        $this->command->info("Generating {$billCount} bills for treasury: {$treasury->name}");

        for ($i = 0; $i < $billCount; $i++) {
            $bill = $this->createBill($financialYear, $financialYearString, $treasury, $ddos->random());

            // Create payees for this bill
            $this->createPayeesForBill($bill);
        }
    }

    /**
     * Create a single bill.
     */
    private function createBill(
        FinancialYear $financialYear,
        string $financialYearString,
        Treasury $treasury,
        Ddo $ddo
    ): Bill {
        // Get next bill number
        $billNumber = $this->billNoSequenceService->getNextBillNo(
            $treasury->id,
            $financialYearString
        );

        // Generate amounts
        $netAmount = rand(5000, 1000000);
        $grossAmount = $netAmount + rand(100, 10000);

        // Determine bill date within financial year
        $billDate = $this->getRandomDateInFinancialYear($financialYear);

        return Bill::create([
            'financial_year_id' => $financialYear->id,
            'treasury_id' => $treasury->id,
            'ddo_id' => $ddo->id,
            'payment_scheme_id' => PaymentScheme::inRandomOrder()->first()?->id,
            'detail_head_id' => DetailHead::inRandomOrder()->first()?->id,
            'bill_type_id' => BillType::inRandomOrder()->first()?->id,
            'form_id' => Form::inRandomOrder()->first()?->id,
            'bill_number' => (int) $billNumber,
            'source_bill_id' => $this->generateSourceBillId(),
            'bill_date' => $billDate,
            'source' => $this->getRandomBillSource(),
            'category' => $this->getRandomBillCategory(),
            'net_amount' => $netAmount,
            'gross_amount' => $grossAmount,
            'is_urgent' => rand(0, 100) < 20,
            'payment_type' => $this->getRandomPaymentType(),
        ]);
    }

    /**
     * Create payees for a bill.
     */
    private function createPayeesForBill(Bill $bill): void
    {
        // Generate 1-5 payees per bill
        $payeeCount = rand(1, 5);

        // Calculate individual payee amounts that sum to bill's net amount
        $remainingAmount = $bill->net_amount;

        for ($i = 0; $i < $payeeCount; $i++) {
            // Last payee gets the remaining amount
            if ($i === $payeeCount - 1) {
                $amount = $remainingAmount;
            } else {
                // Random portion of remaining amount (40-70%)
                $amount = round($remainingAmount * rand(40, 70) / 100, 2);
                $remainingAmount -= $amount;
            }

            // Generate unique reg_code for this bill
            $regCode = $this->generatePayeeRegCode($i);

            // Create payee
            $payee = Payee::create([
                'bill_id' => $bill->id,
                'reg_code' => $regCode,
                'name' => fake()->name(),
                'amount' => $amount,
                'ifsc' => $this->generateIfscCode(),
                'account_no' => fake()->bankAccountNumber(),
                'rbi_endtoend_id' => null,
                'rbi_utr_id' => null,
                'rbi_utr_date' => null,
            ]);

            // Add RBI references if bill is old enough (processed)
            if ($bill->bill_date->lt(now()->subDays(3))) {
                $payee->update([
                    'rbi_endtoend_id' => $this->generateRbiEndToEndId(),
                    'rbi_utr_id' => $this->generateRbiUtrId(),
                    'rbi_utr_date' => $bill->bill_date->addDays(rand(1, 3)),
                ]);
            }
        }
    }

    /**
     * Generate a unique payee registration code.
     */
    private function generatePayeeRegCode(int $index): string
    {
        $prefixes = ['EMP', 'PEN', 'CON', 'VEN', 'SUP'];
        $prefix = $prefixes[array_rand($prefixes)];

        return $prefix . '-' . date('Y') . '-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Generate IFSC code.
     */
    private function generateIfscCode(): string
    {
        $bankCode = strtoupper(Str::random(4));
        $branchCode = str_pad((string) rand(1, 9999), 6, '0', STR_PAD_LEFT);

        return $bankCode . '0' . $branchCode;
    }

    /**
     * Generate RBI end-to-end ID.
     */
    private function generateRbiEndToEndId(): string
    {
        return 'E2E' . date('Ymd') . strtoupper(Str::random(10));
    }

    /**
     * Generate RBI UTR ID.
     */
    private function generateRbiUtrId(): string
    {
        return 'UTR' . date('Ymd') . strtoupper(Str::random(10));
    }

    /**
     * Get random date within financial year.
     */
    private function getRandomDateInFinancialYear(FinancialYear $financialYear): Carbon
    {
        $startDate = $financialYear->start_date ?? now()->startOfYear();
        $endDate = $financialYear->end_date ?? now();

        // Ensure we don't generate future dates
        $endDate = min($endDate, now());

        return Carbon::instance(
            Factory::create()->dateTimeBetween($startDate, $endDate)
        );
    }

    /**
     * Generate unique source bill ID.
     */
    private function generateSourceBillId(): string
    {
        return 'SRC-' . date('Y') . '-' . strtoupper(Str::random(8));
    }

    /**
     * Get random bill source.
     */
    private function getRandomBillSource(): string
    {
        $sources = BillSource::cases();

        return $sources[array_rand($sources)]->value;
    }

    /**
     * Get random bill category.
     */
    private function getRandomBillCategory(): string
    {
        $categories = BillCategory::cases();

        return $categories[array_rand($categories)]->value;
    }

    /**
     * Get random payment type.
     */
    private function getRandomPaymentType(): string
    {
        $paymentTypes = PaymentType::cases();

        return $paymentTypes[array_rand($paymentTypes)]->value;
    }
}