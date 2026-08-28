<?php

use App\Models\Bill;
use App\Models\ReceiptScheme;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('deductions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Bill::class)->constrained()->restrictOnDelete();
            $table->foreignIdFor(ReceiptScheme::class)->constrained()->restrictOnDelete();
            $table->decimal('amount', 15, 2);
            $table->timestamps();
            $table->softDeletes();

            // Add composite index for better performance
            $table->index(['bill_id', 'receipt_scheme_id']);

            // Add unique constraint to prevent duplicate deductions for same receipt scheme in a bill
            $table->unique(['bill_id', 'receipt_scheme_id'], 'unique_bill_receiptscheme');
        });

        // Add check constraint to ensure amount is positive
        DB::statement('ALTER TABLE deductions ADD CONSTRAINT check_deduction_amount_positive CHECK (amount > 0)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deductions');
    }
};