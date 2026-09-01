<?php

namespace Database\Seeders;

use App\Models\PaymentScheme;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class PaymentSchemeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $csvPath = database_path('seeders/data/payment_schemes.csv');

        if (! File::exists($csvPath)) {
            $this->command->error("CSV file not found at: {$csvPath}");

            return;
        }

        $csvData = $this->readCSV($csvPath);

        if (empty($csvData)) {
            $this->command->warn('No data found in CSV file');

            return;
        }

        // Clean and prepare data
        $cleanedData = $this->cleanData($csvData);

        // Insert data in chunks for better performance
        $chunkSize = 100;
        $totalInserted = 0;

        foreach (array_chunk($cleanedData, $chunkSize) as $chunk) {
            PaymentScheme::insert($chunk);
            $totalInserted += count($chunk);
        }

        $this->command->info("Successfully seeded {$totalInserted} payment schemes.");
    }

    /**
     * Read CSV file and return data as array
     */
    private function readCSV(string $filePath): array
    {
        $data = [];
        $header = null;

        if (($handle = fopen($filePath, 'r')) !== false) {
            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                if (! $header) {
                    $header = $row;

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
     * Clean and normalize the data - only extract fields from migration
     */
    private function cleanData(array $csvData): array
    {
        $cleanedData = [];

        foreach ($csvData as $row) {
            // Skip empty rows
            if (empty(array_filter($row))) {
                continue;
            }

            $cleanedRow = [
                'demand_number' => $this->cleanString($row['demand_no'] ?? '', 2),
                'major_head' => $this->cleanString($row['major_head'] ?? '', 4),
                'submajor_head' => $this->cleanString($row['submajor_head'] ?? '', 2),
                'minor_head' => $this->cleanString($row['minor_head'] ?? '', 3),
                'subminor_head' => $this->cleanString($row['subminor_head'] ?? '', 2),
                'sub_head' => $this->cleanString($row['sub_head'] ?? '', 2),
                'flag' => $this->cleanFlag($row['flag'] ?? 0),
                'charged_or_voted' => $this->cleanChargedVoted($row['charged_voted'] ?? $row['charged_or_voted'] ?? 'V'),
                'code' => $this->cleanString($row['scheme_code'] ?? $row['code'] ?? '', 17),
                'description' => $this->cleanDescription($row['scheme_name'] ?? $row['description'] ?? ''),
                'sector_code' => $this->cleanNullableString($row['sector_code'] ?? null, 2),
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ];

            // Generate code if missing
            if (empty($cleanedRow['code'])) {
                $cleanedRow['code'] = $this->generateCode($cleanedRow);
            }

            // Validate required fields
            if ($this->isValidRow($cleanedRow)) {
                $cleanedData[] = $cleanedRow;
            } else {
                $this->command->warn('Skipping invalid row: '.json_encode($row));
            }
        }

        return $cleanedData;
    }

    /**
     * Generate code from individual components
     */
    private function generateCode(array $row): string
    {
        return $row['demand_number'].
            $row['major_head'].
            $row['submajor_head'].
            $row['minor_head'].
            $row['subminor_head'].
            $row['sub_head'].
            $row['charged_or_voted'].
            $row['flag'];
    }

    /**
     * Clean string values
     */
    private function cleanString(?string $value, int $length): string
    {
        $value = trim($value ?? '');
        $value = preg_replace('/\s+/', ' ', $value); // Replace multiple spaces with single space

        return str_pad(substr($value, 0, $length), $length, '0', STR_PAD_LEFT);
    }

    /**
     * Clean nullable string values
     */
    private function cleanNullableString(?string $value, int $length): ?string
    {
        $value = trim($value ?? '');

        if (empty($value) || $value === 'null' || $value === 'NULL') {
            return null;
        }

        return str_pad(substr($value, 0, $length), $length, '0', STR_PAD_LEFT);
    }

    /**
     * Clean flag value
     */
    private function cleanFlag(?string $value): int
    {
        $value = trim($value ?? '0');

        return is_numeric($value) ? (int) $value : 0;
    }

    /**
     * Clean charged/voted value
     */
    private function cleanChargedVoted(?string $value): string
    {
        $value = strtoupper(trim($value ?? ''));

        return in_array($value, ['V', 'C']) ? $value : 'V';
    }

    /**
     * Clean description/scheme name
     */
    private function cleanDescription(?string $value): string
    {
        $value = trim($value ?? '');
        $value = preg_replace('/\s+/', ' ', $value); // Replace multiple spaces with single space
        $value = rtrim($value, '-'); // Remove trailing hyphens
        $value = trim($value); // Trim again after removing hyphens

        return $value ?: 'Unnamed Scheme';
    }

    /**
     * Validate a cleaned row
     */
    private function isValidRow(array $row): bool
    {
        // Check required fields are not empty
        $requiredFields = [
            'demand_number',
            'major_head',
            'minor_head',
            'code',
            'description',
        ];

        foreach ($requiredFields as $field) {
            if (empty($row[$field])) {
                return false;
            }
        }

        // Validate charged_or_voted
        if (! in_array($row['charged_or_voted'], ['V', 'C'])) {
            return false;
        }

        // Validate code format (17 characters)
        if (strlen($row['code']) !== 17) {
            return false;
        }

        return true;
    }
}
