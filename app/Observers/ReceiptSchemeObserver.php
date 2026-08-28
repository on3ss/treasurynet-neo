<?php

namespace App\Observers;

use App\Models\ReceiptScheme;

class ReceiptSchemeObserver
{
    /**
     * Handle the ReceiptScheme "creating" event.
     */
    public function creating(ReceiptScheme $receiptScheme): void
    {
        $receiptScheme->code = $this->generateCode($receiptScheme);
    }

    /**
     * Handle the ReceiptScheme "updating" event.
     */
    public function updating(ReceiptScheme $receiptScheme): void
    {
        if ($this->shouldRegenerateCode($receiptScheme)) {
            $receiptScheme->code = $this->generateCode($receiptScheme);
        }
    }

    public function saving(ReceiptScheme $receiptScheme): void
    {
        // Only regenerate if relevant fields have changed
        if ($this->shouldRegenerateCode($receiptScheme)) {
            $receiptScheme->code = $this->generateCode($receiptScheme);
        }
    }

    /**
     * Handle the ReceiptScheme "created" event.
     */
    public function created(ReceiptScheme $receiptScheme): void
    {
        // Log or perform actions after creation
        // Log::info('Receipt scheme created', ['code' => $receiptScheme->code]);
    }

    /**
     * Handle the ReceiptScheme "updated" event.
     */
    public function updated(ReceiptScheme $receiptScheme): void
    {
        // Log or perform actions after update
        // Log::info('Receipt scheme updated', ['code' => $receiptScheme->code]);
    }

    /**
     * Handle the ReceiptScheme "deleted" event.
     */
    public function deleted(ReceiptScheme $receiptScheme): void
    {
        // Cleanup or logging after deletion
    }

    /**
     * Handle the ReceiptScheme "restored" event.
     */
    public function restored(ReceiptScheme $receiptScheme): void
    {
        // Handle restoration
    }

    /**
     * Handle the ReceiptScheme "forceDeleted" event.
     */
    public function forceDeleted(ReceiptScheme $receiptScheme): void
    {
        // Handle permanent deletion
    }

    /**
     * Generate the code by concatenating all fields.
     */
    private function generateCode(ReceiptScheme $receiptScheme): string
    {
        return $receiptScheme->major_head .
            $receiptScheme->submajor_head .
            $receiptScheme->minor_head .
            $receiptScheme->subminor_head .
            $receiptScheme->sub_head;
    }

    /**
     * Check if the code should be regenerated.
     */
    private function shouldRegenerateCode(ReceiptScheme $receiptScheme): bool
    {
        // Check if any of the relevant fields are dirty
        $relevantFields = [
            'major_head',
            'submajor_head',
            'minor_head',
            'subminor_head',
            'sub_head',
        ];

        foreach ($relevantFields as $field) {
            if ($receiptScheme->isDirty($field)) {
                return true;
            }
        }

        return false;
    }
}