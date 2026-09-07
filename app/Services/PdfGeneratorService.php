<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;

class PdfGeneratorService
{
    /**
     * Generate a PDF and return the temporary file path.
     */
    public function generatePdf(string $view, array $data, string $filename): string
    {
        $pdf = Pdf::loadView($view, $data);

        // Ensure temp directory exists
        $tempDir = storage_path('app/temp');
        if (! file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $tempPath = $tempDir.'/'.$filename;
        $pdf->save($tempPath);

        return $tempPath;
    }

    /**
     * Clean up temporary file.
     */
    public function cleanupTempFile(string $filePath): void
    {
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }
}
