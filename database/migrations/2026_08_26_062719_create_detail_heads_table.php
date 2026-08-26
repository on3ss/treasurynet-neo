<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('detail_heads', function (Blueprint $table) {
            $table->id();
            $table->char('code', 2)->unique();
            $table->string('description');
            $table->enum('head_type', ['AC', 'DC', 'NA'])->default('NA');
            $table->json('deduction_applicability')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Add indexes
            $table->index('head_type');
            $table->index('deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_heads');
    }
};