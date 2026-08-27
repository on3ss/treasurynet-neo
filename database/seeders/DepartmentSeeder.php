<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            ['code' => '01', 'name' => 'Animal Husbandry & Veterinary'],
            ['code' => '02', 'name' => 'Agriculture Department'],
            ['code' => '03', 'name' => 'Arts & Culture Department'],
            ['code' => '04', 'name' => 'Border Areas Development Department'],
            ['code' => '05', 'name' => 'Co-operation Department'],
            ['code' => '06', 'name' => 'Community & Rural Development Department'],
            ['code' => '07', 'name' => 'Education & Human Resources'],
            ['code' => '08', 'name' => 'Election Department'],
            ['code' => '09', 'name' => 'Excise, Registration, Taxation'],
            ['code' => '10', 'name' => 'Finance Department'],
            ['code' => '11', 'name' => 'Fisheries Department'],
            ['code' => '12', 'name' => 'Food & Civil Supplies & Consumer Affairs'],
            ['code' => '13', 'name' => 'Forest & Environment Department'],
            ['code' => '14', 'name' => 'General Administration Department'],
            ['code' => '15', 'name' => 'Health & Family Welfare Department'],
            ['code' => '16', 'name' => 'Home (Civil Defence) Department'],
            ['code' => '17', 'name' => 'Department of Prisons and Correctional Services'],
            ['code' => '18', 'name' => 'Home (Police) Department'],
            ['code' => '19', 'name' => 'Housing Department'],
            ['code' => '20', 'name' => 'Industries Department'],
            ['code' => '21', 'name' => 'Information & Public Relations'],
            ['code' => '22', 'name' => 'Labour Department'],
            ['code' => '23', 'name' => 'Law Department'],
            ['code' => '24', 'name' => 'Legislative Assembly'],
            ['code' => '25', 'name' => 'Mining & Geology Department'],
            ['code' => '26', 'name' => 'Parliamentary Affairs Department'],
            ['code' => '27', 'name' => 'Personnel Department'],
            ['code' => '28', 'name' => 'Department of Planning, Investment Promotion and Sustainable Development'],
            ['code' => '29', 'name' => 'Political Department'],
            ['code' => '30', 'name' => 'Power Department'],
            ['code' => '31', 'name' => 'Printing & Stationery Department'],
            ['code' => '32', 'name' => 'Programme Implementation Department'],
            ['code' => '33', 'name' => 'Public Health Engineering Department'],
            ['code' => '34', 'name' => 'Public Works Department'],
            ['code' => '35', 'name' => 'Relief & Rehabilitation'],
            ['code' => '36', 'name' => 'Revenue Department'],
            ['code' => '37', 'name' => 'Secretariat Administration Department'],
            ['code' => '38', 'name' => 'Sericulture & Weaving'],
            ['code' => '39', 'name' => 'Social Welfare Department'],
            ['code' => '40', 'name' => 'Soil Conservation Department'],
            ['code' => '41', 'name' => 'Sports & Youth Affairs Department'],
            ['code' => '42', 'name' => 'Tourism Department'],
            ['code' => '43', 'name' => 'Transport Department'],
            ['code' => '44', 'name' => 'Urban Affairs Department'],
            ['code' => '45', 'name' => 'Legal Metrology Department'],
            ['code' => '46', 'name' => 'CM Secretariat Department'],
            ['code' => '47', 'name' => "Governor's Secretariat"],
            ['code' => '48', 'name' => 'Public Service Commission'],
            ['code' => '49', 'name' => 'District Council Affairs'],
            ['code' => '50', 'name' => 'Water Resource Department'],
            ['code' => '91', 'name' => 'Information Technology'],
        ];

        // Use a transaction for better performance and atomicity
        DB::transaction(function () use ($departments) {
            foreach ($departments as $department) {
                Department::updateOrCreate(
                    ['code' => $department['code']],
                    ['name' => $department['name']]
                );
            }
        });

        $this->command->info('Departments seeded successfully: '.count($departments).' records.');
    }
}
