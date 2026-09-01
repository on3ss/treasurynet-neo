<?php

namespace Database\Seeders;

use App\Models\DetailHead;
use App\Models\SubDetailHead;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class SubDetailHeadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $csvPath = database_path('seeders/data/subdetail_heads.csv');

        if (! File::exists($csvPath)) {
            $this->command->error("CSV file not found at: {$csvPath}");

            return;
        }

        $this->command->info('Starting sub detail heads import...');

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
                // Extract data from CSV
                $detailHeadCode = trim($row[$headerMap['detail_head']]);
                $subDetailHeadCode = trim($row[$headerMap['subdetail_head']]);
                $description = trim($row[$headerMap['subdetail_desc']]);

                // Skip empty rows
                if (empty($detailHeadCode) || empty($subDetailHeadCode)) {
                    $this->command->warn('Skipping row '.($index + 1).': Missing detail head or sub detail head code');
                    $skippedCount++;

                    continue;
                }

                // Find the detail head by code
                $detailHead = DetailHead::where('code', $detailHeadCode)->first();

                if (! $detailHead) {
                    $this->command->warn('Skipping row '.($index + 1).": Detail head code '{$detailHeadCode}' not found");
                    $skippedCount++;

                    continue;
                }

                // Check if record already exists
                $existingRecord = SubDetailHead::where('detail_head_id', $detailHead->id)
                    ->where('code', $subDetailHeadCode)
                    ->first();

                if ($existingRecord) {
                    // Update existing record
                    $existingRecord->update([
                        'description' => $description,
                    ]);
                    $this->command->line("Updated: Detail Head {$detailHeadCode} - Sub Detail Head {$subDetailHeadCode} - {$description}");
                } else {
                    // Create new record
                    SubDetailHead::create([
                        'detail_head_id' => $detailHead->id,
                        'code' => $subDetailHeadCode,
                        'description' => $description,
                    ]);
                    $this->command->line("Created: Detail Head {$detailHeadCode} - Sub Detail Head {$subDetailHeadCode} - {$description}");
                }

                $importedCount++;

            } catch (\Exception $e) {
                $skippedCount++;
                $errors[] = [
                    'row' => $index + 1,
                    'error' => $e->getMessage(),
                    'data' => $row,
                ];
                Log::error('Failed to import sub detail head at row '.($index + 1).': '.$e->getMessage());
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
