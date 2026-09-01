<?php

namespace Database\Seeders;

use App\Models\ReceiptScheme;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class ReceiptSchemeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $csvPath = database_path('seeders/data/receipt_schemes.csv');

        if (! File::exists($csvPath)) {
            $this->command->error("CSV file not found at: {$csvPath}");

            return;
        }

        $this->command->info('Starting receipt schemes import...');

        // Read CSV file
        $rows = array_map('str_getcsv', file($csvPath));

        // Remove header row
        $header = array_shift($rows);

        // Map header to column indexes for easier access
        $headerMap = array_flip($header);

        $importedCount = 0;
        $skippedCount = 0;
        $errors = [];

        foreach ($rows as $index => $row) {
            try {
                // Calculate the code (this will also be done by observer, but we include it for validation)
                $expectedCode = $row[$headerMap['major_head']].
                    $row[$headerMap['submajor_head']].
                    $row[$headerMap['minor_head']].
                    $row[$headerMap['subminor_head']].
                    $row[$headerMap['sub_head']];

                // Extract data from CSV
                $data = [
                    'major_head' => trim($row[$headerMap['major_head']]),
                    'submajor_head' => trim($row[$headerMap['submajor_head']]),
                    'minor_head' => trim($row[$headerMap['minor_head']]),
                    'subminor_head' => trim($row[$headerMap['subminor_head']]),
                    'sub_head' => trim($row[$headerMap['sub_head']]),
                    'code' => $expectedCode,
                    'description' => trim($row[$headerMap['scheme_name']]),
                    'sector_code' => trim($row[$headerMap['sector_code']]) ?: null,
                ];

                // Check if record already exists (by code)
                $existingRecord = ReceiptScheme::where('code', $expectedCode)->first();

                if ($existingRecord) {
                    // Update existing record
                    $existingRecord->update($data);
                    $this->command->line("Updated: {$expectedCode} - {$data['description']}");
                } else {
                    // Create new record
                    $receiptScheme = ReceiptScheme::create($data);
                    $this->command->line("Created: {$expectedCode} - {$data['description']}");
                }

                $importedCount++;

                // Log progress every 50 records
                if ($importedCount % 50 === 0) {
                    $this->command->info("Processed {$importedCount} records...");
                }

            } catch (\Exception $e) {
                $skippedCount++;
                $errors[] = [
                    'row' => $index + 1,
                    'error' => $e->getMessage(),
                ];
                Log::error('Failed to import receipt scheme at row '.($index + 1).': '.$e->getMessage());
            }
        }

        // Summary
        $this->command->info("\nImport completed:");
        $this->command->info("✓ Imported/Updated: {$importedCount} records");
        $this->command->info("✗ Skipped: {$skippedCount} records");

        if (! empty($errors)) {
            $this->command->warn("\nErrors encountered:");
            foreach ($errors as $error) {
                $this->command->error("Row {$error['row']}: {$error['error']}");
            }
        }
    }
}
