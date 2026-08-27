<?php

namespace App\Observers;

use App\Models\PaymentScheme;

class PaymentSchemeObserver
{
    /**
     * Handle the PaymentScheme "creating" event.
     */
    public function creating(PaymentScheme $paymentScheme): void
    {
        $paymentScheme->code = $this->generateCode($paymentScheme);
    }

    /**
     * Handle the PaymentScheme "updating" event.
     */
    public function updating(PaymentScheme $paymentScheme): void
    {
        $paymentScheme->code = $this->generateCode($paymentScheme);
    }

    /**
     * Handle the PaymentScheme "saving" event.
     */
    public function saving(PaymentScheme $paymentScheme): void
    {
        // Only regenerate if relevant fields have changed
        if ($this->shouldRegenerateCode($paymentScheme)) {
            $paymentScheme->code = $this->generateCode($paymentScheme);
        }
    }

    /**
     * Handle the PaymentScheme "created" event.
     */
    public function created(PaymentScheme $paymentScheme): void
    {
        // Log or perform actions after creation
        // Log::info('Payment scheme created', ['code' => $paymentScheme->code]);
    }

    /**
     * Handle the PaymentScheme "updated" event.
     */
    public function updated(PaymentScheme $paymentScheme): void
    {
        // Log or perform actions after update
        // Log::info('Payment scheme updated', ['code' => $paymentScheme->code]);
    }

    /**
     * Handle the PaymentScheme "deleted" event.
     */
    public function deleted(PaymentScheme $paymentScheme): void
    {
        // Cleanup or logging after deletion
    }

    /**
     * Handle the PaymentScheme "restored" event.
     */
    public function restored(PaymentScheme $paymentScheme): void
    {
        // Handle restoration
    }

    /**
     * Handle the PaymentScheme "forceDeleted" event.
     */
    public function forceDeleted(PaymentScheme $paymentScheme): void
    {
        // Handle permanent deletion
    }

    /**
     * Generate the code by concatenating all fields.
     */
    private function generateCode(PaymentScheme $paymentScheme): string
    {
        return $paymentScheme->demand_number.
            $paymentScheme->major_head.
            $paymentScheme->submajor_head.
            $paymentScheme->minor_head.
            $paymentScheme->subminor_head.
            $paymentScheme->sub_head.
            $paymentScheme->charged_or_voted.
            $paymentScheme->flag;
    }

    /**
     * Check if the code should be regenerated.
     */
    private function shouldRegenerateCode(PaymentScheme $paymentScheme): bool
    {
        // Check if any of the relevant fields are dirty
        $relevantFields = [
            'demand_number',
            'major_head',
            'submajor_head',
            'minor_head',
            'subminor_head',
            'sub_head',
            'charged_or_voted',
            'flag',
        ];

        foreach ($relevantFields as $field) {
            if ($paymentScheme->isDirty($field)) {
                return true;
            }
        }

        return false;
    }
}
