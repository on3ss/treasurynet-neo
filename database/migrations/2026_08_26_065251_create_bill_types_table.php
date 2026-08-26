<?php

use App\Models\DetailHead;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bill_types', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(DetailHead::class)->constrained()->cascadeOnDelete();
            $table->char('code', 2);
            $table->string('description');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['detail_head_id', 'code']);

            // Add indexes
            $table->index('description');
            $table->index('deleted_at');
            $table->index('detail_head_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bill_types');
    }
};