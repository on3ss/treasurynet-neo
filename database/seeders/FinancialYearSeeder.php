<?php

namespace Database\Seeders;

use App\Models\FinancialYear;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FinancialYearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $financialYears = $this->generateFinancialYears();

        DB::transaction(function () use ($financialYears) {
            foreach ($financialYears as $financialYear) {
                FinancialYear::updateOrCreate(
                    ['name' => $financialYear['name']],
                    $financialYear
                );
            }
        });

        // Ensure only one current financial year
        $this->ensureSingleCurrentYear();

        $this->command->info('Financial years seeded successfully!');
    }

    /**
     * Generate financial years dynamically.
     *
     * @param int $yearsBefore Number of years before current to generate
     * @param int $yearsAfter Number of years after current to generate
     * @return array
     */
    private function generateFinancialYears(int $yearsBefore = 5, int $yearsAfter = 5): array
    {
        $now = Carbon::now();
        $currentFinancialYearStart = $now->month >= 4 ? $now->year : $now->year - 1;

        $financialYears = [];

        for ($year = $currentFinancialYearStart - $yearsBefore; $year <= $currentFinancialYearStart + $yearsAfter; $year++) {
            $isCurrent = $year === $currentFinancialYearStart;
            $isActive = $year >= $currentFinancialYearStart - 1 && $year <= $currentFinancialYearStart + 1;

            $financialYears[] = [
                'name' => $year . '-' . ($year + 1),
                'start_date' => Carbon::create($year, 4, 1)->format('Y-m-d'),
                'end_date' => Carbon::create($year + 1, 3, 31)->format('Y-m-d'),
                'is_active' => $isActive,
                'is_current' => $isCurrent,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        return $financialYears;
    }

    /**
     * Ensure only one current financial year exists.
     */
    private function ensureSingleCurrentYear(): void
    {
        $now = Carbon::now();
        $currentFYStart = $now->month >= 4 ? $now->year : $now->year - 1;
        $currentName = $currentFYStart . '-' . ($currentFYStart + 1);

        // Deactivate all current years
        FinancialYear::where('is_current', true)
            ->where('name', '!=', $currentName)
            ->update(['is_current' => false]);

        // Activate the correct current year
        FinancialYear::where('name', $currentName)
            ->update(['is_current' => true, 'is_active' => true]);
    }
}