<?php

namespace App\Console\Commands;

use App\Models\FinancialYear;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;

class AutoGenerateFinancialYears extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'financial-years:generate 
                            {--years-before=5 : Number of years before current to generate}
                            {--years-after=5 : Number of years after current to generate}
                            {--force : Force update current financial year}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically generate financial years based on current date';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $yearsBefore = (int) $this->option('years-before');
        $yearsAfter = (int) $this->option('years-after');
        $force = (bool) $this->option('force');

        $now = Carbon::now();
        $currentFYStart = $now->month >= 4 ? $now->year : $now->year - 1;

        $this->info("Current Financial Year: {$currentFYStart}-".($currentFYStart + 1));

        $createdCount = 0;
        $updatedCount = 0;

        // Generate or update financial years
        for ($year = $currentFYStart - $yearsBefore; $year <= $currentFYStart + $yearsAfter; $year++) {
            $name = $year.'-'.($year + 1);

            /** @var Carbon $startDate */
            $startDate = Carbon::create($year, 4, 1);

            /** @var Carbon $endDate */
            $endDate = Carbon::create($year + 1, 3, 31);

            $isCurrent = $year === $currentFYStart;
            $isActive = $year >= $currentFYStart - 1 && $year <= $currentFYStart + 1;

            /** @var FinancialYear|null $financialYear */
            $financialYear = FinancialYear::where('name', $name)->first();

            if (! $financialYear) {
                FinancialYear::create([
                    'name' => $name,
                    'start_date' => $startDate->format('Y-m-d'),
                    'end_date' => $endDate->format('Y-m-d'),
                    'is_active' => $isActive,
                    'is_current' => $isCurrent,
                ]);
                $createdCount++;
                $this->info("Created: {$name}");
            } else {
                $needsUpdate = false;

                /** @var Carbon $financialYearStartDate */
                $financialYearStartDate = $financialYear->start_date;

                /** @var Carbon $financialYearEndDate */
                $financialYearEndDate = $financialYear->end_date;

                // Update if dates are incorrect
                if (
                    $financialYearStartDate->format('Y-m-d') !== $startDate->format('Y-m-d') ||
                    $financialYearEndDate->format('Y-m-d') !== $endDate->format('Y-m-d')
                ) {
                    $financialYear->start_date = $startDate->format('Y-m-d');
                    $financialYear->end_date = $endDate->format('Y-m-d');
                    $needsUpdate = true;
                }

                // Update current flag if needed
                if ($force || $financialYear->is_current !== $isCurrent) {
                    $financialYear->is_current = $isCurrent;
                    $needsUpdate = true;
                }

                // Update active flag if needed
                if ($force || $financialYear->is_active !== $isActive) {
                    $financialYear->is_active = $isActive;
                    $needsUpdate = true;
                }

                if ($needsUpdate) {
                    $financialYear->save();
                    $updatedCount++;
                    $this->info("Updated: {$name}");
                } else {
                    $this->line("No changes: {$name}");
                }
            }
        }

        // Ensure only one current financial year
        $this->ensureSingleCurrentYear($currentFYStart);

        $this->newLine();
        $this->info('Financial years generated successfully!');
        $this->info("Created: {$createdCount}, Updated: {$updatedCount}");

        return parent::SUCCESS;
    }

    /**
     * Ensure only one current financial year exists.
     */
    private function ensureSingleCurrentYear(int $currentFYStart): void
    {
        $currentName = $currentFYStart.'-'.($currentFYStart + 1);

        /** @var Collection<int, FinancialYear> $currentYears */
        $currentYears = FinancialYear::where('is_current', true)->get();

        foreach ($currentYears as $financialYear) {
            /** @var FinancialYear $financialYear */
            if ($financialYear->name !== $currentName) {
                $financialYear->is_current = false;
                $financialYear->save();
                $this->warn("Deactivated current flag for: {$financialYear->name}");
            }
        }

        /** @var FinancialYear|null $correctCurrent */
        $correctCurrent = FinancialYear::where('name', $currentName)->first();

        if ($correctCurrent && ! $correctCurrent->is_current) {
            $correctCurrent->is_current = true;
            $correctCurrent->is_active = true;
            $correctCurrent->save();
            $this->info("Marked {$currentName} as current");
        }
    }
}
