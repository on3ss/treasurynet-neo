<?php

use App\Enums\DDOType;
use App\Models\Department;
use App\Models\Treasury;
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
        Schema::create('ddos', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Treasury::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Department::class)->constrained()->cascadeOnDelete();
            $table->char('sequence', 5);
            $table->char('code', 7)->unique();
            $table->string('designation');
            $table->enum('type', DDOType::values())->index();
            $table->text('address')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
            $table->softDeletes();

            // Composite unique constraint
            $table->unique(['treasury_id', 'department_id', 'sequence'], 'ddos_treasury_dept_seq_unique');

            // Composite index for common queries
            $table->index(['treasury_id', 'department_id'], 'ddos_treasury_dept_index');
            $table->index(['is_active', 'type'], 'ddos_active_type_index');
            $table->index(['code', 'is_active'], 'ddos_code_active_index');
            $table->index(['deleted_at', 'is_active'], 'ddos_deleted_active_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ddos');
    }
};
