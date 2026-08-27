<?php

namespace Database\Seeders;

use App\Models\Treasury;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TreasurySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $treasuries = [
            ['code' => '01', 'name' => 'CT SPARSH'],
            ['code' => '02', 'name' => 'GST'],
            ['code' => '03', 'name' => 'Cyber'],
            ['code' => '10', 'name' => 'Shillong District'],
            ['code' => '11', 'name' => 'Sohra'],
            ['code' => '20', 'name' => 'Shillong South'],
            ['code' => '21', 'name' => 'South AR'],
            ['code' => '22', 'name' => 'South AS'],
            ['code' => '23', 'name' => 'South MZ'],
            ['code' => '24', 'name' => 'South NL'],
            ['code' => '30', 'name' => 'Jowai'],
            ['code' => '31', 'name' => 'Khliehriat'],
            ['code' => '32', 'name' => 'Amlarem'],
            ['code' => '40', 'name' => 'Nongstoin'],
            ['code' => '41', 'name' => 'Mawkyrwat'],
            ['code' => '50', 'name' => 'Mairang'],
            ['code' => '60', 'name' => 'Nongpoh'],
            ['code' => '70', 'name' => 'Tura District'],
            ['code' => '71', 'name' => 'Ampati'],
            ['code' => '72', 'name' => 'Dadengre'],
            ['code' => '80', 'name' => 'Williamnagar'],
            ['code' => '81', 'name' => 'Resubelpara'],
            ['code' => '90', 'name' => 'Baghmara'],
        ];

        DB::transaction(function () use ($treasuries) {
            foreach ($treasuries as $treasury) {
                Treasury::updateOrCreate(
                    ['code' => $treasury['code']],
                    array_merge($treasury, ['is_active' => true])
                );
            }
        });

        $this->command->info('Treasuries seeded successfully!');
    }
}
