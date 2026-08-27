<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bill_no_sequences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('treasury_id')->constrained()->onDelete('restrict');
            $table->string('financial_year', 9);
            $table->unsignedBigInteger('last_sequence_no')->default(0);
            $table->timestamps();

            // Unique constraint for treasury + financial year
            $table->unique(['treasury_id', 'financial_year'], 'uk_treasury_financial_year');

            // Index for faster lookups
            $table->index(['treasury_id', 'financial_year'], 'idx_treasury_year');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bill_no_sequences');
    }
};