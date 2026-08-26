<?php

namespace Database\Seeders;

use App\Models\BillType;
use App\Models\DetailHead;
use App\Models\Form;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class FormSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $csvPath = database_path('seeders/data/forms.csv');

        if (!File::exists($csvPath)) {
            $this->command->error("CSV file not found at: {$csvPath}");
            return;
        }

        $csvData = $this->readCSV($csvPath);

        if (empty($csvData)) {
            $this->command->warn('No data found in CSV file');
            return;
        }

        $inserted = 0;
        $skipped = 0;
        $duplicates = 0;

        foreach ($csvData as $index => $row) {
            try {
                $detailHeadCode = $this->cleanCode($row['detail_head'] ?? '', 2);
                $billTypeCode = $this->cleanCode($row['bill_type'] ?? '', 2);
                $formName = $this->cleanFormName($row['form_id'] ?? '');

                if (empty($formName)) {
                    $this->command->warn("Skipping row #{$index}: Empty form name");
                    $skipped++;
                    continue;
                }

                $detailHead = DetailHead::where('code', $detailHeadCode)->first();

                if (!$detailHead) {
                    $this->command->warn("Skipping row #{$index}: Detail head not found: {$detailHeadCode}");
                    $skipped++;
                    continue;
                }

                $billType = BillType::where('detail_head_id', $detailHead->id)
                    ->where('code', $billTypeCode)
                    ->first();

                if (!$billType) {
                    $this->command->warn("Skipping row #{$index}: Bill type not found for detail head {$detailHeadCode}, code {$billTypeCode}");
                    $skipped++;
                    continue;
                }

                $form = Form::updateOrCreate(
                    [
                        'detail_head_id' => $detailHead->id,
                        'bill_type_id' => $billType->id,
                        'name' => $formName,
                    ],
                    []
                );

                if ($form->wasRecentlyCreated) {
                    $inserted++;
                } else {
                    $duplicates++;
                }
            } catch (\Exception $e) {
                $this->command->error("Error processing row #{$index}: {$e->getMessage()}");
                $skipped++;
            }
        }

        $this->command->info("Successfully seeded {$inserted} forms.");

        if ($duplicates > 0) {
            $this->command->warn("Skipped {$duplicates} duplicate forms.");
        }

        if ($skipped > 0) {
            $this->command->warn("Skipped {$skipped} forms due to errors.");
        }
    }

    /**
     * Read CSV file and return data as array.
     */
    private function readCSV(string $filePath): array
    {
        $data = [];
        $header = null;

        if (($handle = fopen($filePath, 'r')) !== false) {
            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                if (!$header) {
                    $header = array_map('trim', $row);
                    continue;
                }

                if (count($row) === count($header)) {
                    $data[] = array_combine($header, $row);
                }
            }
            fclose($handle);
        }

        return $data;
    }

    /**
     * Clean code values.
     */
    private function cleanCode(?string $value, int $length): string
    {
        $value = trim($value ?? '');
        $value = preg_replace('/\s+/', '', $value);

        return str_pad(substr($value, 0, $length), $length, '0', STR_PAD_LEFT);
    }

    /**
     * Clean form name.
     */
    private function cleanFormName(?string $value): string
    {
        $value = trim($value ?? '');
        $value = preg_replace('/\s+/', ' ', $value);
        $value = trim($value, '"');

        return $value;
    }
}