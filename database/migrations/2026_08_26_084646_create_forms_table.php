<?php

use App\Models\BillType;
use App\Models\DetailHead;
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
        Schema::create('forms', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(DetailHead::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(BillType::class)->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();

            // Add unique constraint to prevent duplicate forms for same detail head and bill type
            $table->unique(['detail_head_id', 'bill_type_id', 'name']);

            // Add indexes
            $table->index('name');
            $table->index('deleted_at');
            $table->index(['detail_head_id', 'bill_type_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forms');
    }
};
