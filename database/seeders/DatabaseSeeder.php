<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            FinancialYearSeeder::class,
            TreasurySeeder::class,
            DepartmentSeeder::class,
            DdoSeeder::class,
            PaymentSchemeSeeder::class,
            DetailHeadSeeder::class,
            BillTypeSeeder::class,
            FormSeeder::class,
        ]);
    }
}
