<?php

use App\Models\Bill;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payees', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Bill::class)->constrained()->restrictOnDelete();
            $table->string('reg_code');
            $table->string('name');
            $table->decimal('amount', 15, 2);
            $table->string('ifsc');
            $table->string('account_no');
            $table->string('rbi_endtoend_id')->nullable();
            $table->string('rbi_utr_id')->nullable();
            $table->date('rbi_utr_date')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['bill_id', 'reg_code'], 'unique_bill_reg_code');

            $table->index('deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payees');
    }
};
