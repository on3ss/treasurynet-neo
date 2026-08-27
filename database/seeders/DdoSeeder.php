<?php

namespace Database\Seeders;

use App\Enums\DDOType;
use App\Models\Ddo;
use App\Models\Department;
use App\Models\Treasury;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class DdoSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = database_path('seeders/data/ddos.csv');

        if (! file_exists($path)) {
            throw new RuntimeException("DDO CSV not found: {$path}");
        }

        $handle = fopen($path, 'r');

        if (! $handle) {
            throw new RuntimeException('Unable to open DDO CSV.');
        }

        $headers = fgetcsv($handle);

        if (! $headers) {
            throw new RuntimeException('DDO CSV is empty.');
        }

        $headers = array_map(
            fn ($header) => trim($header),
            $headers
        );

        $count = 0;
        $skipped = 0;

        DB::transaction(function () use ($handle, $headers, &$count, &$skipped) {
            while (($row = fgetcsv($handle)) !== false) {
                if (count($row) !== count($headers)) {
                    $skipped++;

                    continue;
                }

                $data = array_combine($headers, $row);

                if (! $data) {
                    $skipped++;

                    continue;
                }

                $treasuryCode = trim($data['trea_code'] ?? '');
                $departmentCode = trim($data['dept_code'] ?? '');
                $sequence = trim($data['ddo_seq'] ?? '');
                $code = trim($data['ddo_code'] ?? '');
                $designation = trim($data['designation'] ?? '');

                // Ignore invalid / "NO ENTRY" records.
                if (
                    $treasuryCode === '' ||
                    $departmentCode === '' ||
                    $sequence === '' ||
                    $code === '' ||
                    $code === '0000000' ||
                    strtoupper($designation) === 'NO ENTRY'
                ) {
                    $skipped++;

                    continue;
                }

                $treasury = Treasury::query()
                    ->where('code', $treasuryCode)
                    ->first();

                if (! $treasury) {
                    $this->command->warn(
                        "Treasury not found: {$treasuryCode} for DDO {$code}"
                    );

                    $skipped++;

                    continue;
                }

                $department = Department::query()
                    ->where('code', $departmentCode)
                    ->first();

                if (! $department) {
                    $this->command->warn(
                        "Department not found: {$departmentCode} for DDO {$code}"
                    );

                    $skipped++;

                    continue;
                }

                Ddo::updateOrCreate(
                    [
                        'code' => $code,
                    ],
                    [
                        'treasury_id' => $treasury->id,
                        'department_id' => $department->id,
                        'sequence' => $sequence,
                        'designation' => $designation,
                        'type' => $this->resolveType($data),
                        'address' => $this->nullableString(
                            $data['office_address'] ?? null
                        ),
                        'is_active' => $this->isActive(
                            $data['active'] ?? null
                        ),
                    ]
                );

                $count++;
            }
        });

        fclose($handle);

        $this->command->info("DDOs imported: {$count}");
        $this->command->warn("DDOs skipped: {$skipped}");
    }

    private function nullableString(?string $value): ?string
    {
        $value = trim((string) $value);

        return $value === '' ? null : $value;
    }

    private function isActive(?string $value): bool
    {
        return strtoupper(trim((string) $value)) === 'Y';
    }

    private function resolveType(array $data): string
    {
        /*
         * Adjust this mapping according to your DDOType enum.
         *
         * The legacy data uses:
         *   Y = Government
         *   N = Non-Government
         *
         * If your enum uses different values, change this method.
         */

        return DDOType::GOVERNMENT->value;
    }
}
