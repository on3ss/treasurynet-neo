<?php

use App\Models\Bill;
use App\Models\SubDetailHead;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('grosses', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Bill::class)->constrained()->restrictOnDelete();
            $table->foreignIdFor(SubDetailHead::class)->constrained()->restrictOnDelete();
            $table->decimal('amount', 15, 2);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['bill_id', 'sub_detail_head_id']);

            $table->unique(['bill_id', 'sub_detail_head_id'], 'unique_bill_subdetailhead');
        });

        // Add check constraint to ensure amount is not negative
        DB::statement('ALTER TABLE grosses ADD CONSTRAINT check_amount_positive CHECK (amount >= 0)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grosses');
    }
};
