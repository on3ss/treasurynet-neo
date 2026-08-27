<?php

use App\Enums\BillCategory;
use App\Enums\BillSource;
use App\Enums\PaymentType;
use App\Models\BillType;
use App\Models\Ddo;
use App\Models\DetailHead;
use App\Models\FinancialYear;
use App\Models\Form;
use App\Models\PaymentScheme;
use App\Models\Treasury;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bills', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(FinancialYear::class)->constrained()->restrictOnDelete();
            $table->foreignIdFor(Treasury::class)->constrained()->restrictOnDelete();
            $table->foreignIdFor(Ddo::class)->constrained()->restrictOnDelete();
            $table->foreignIdFor(PaymentScheme::class)->constrained()->restrictOnDelete();
            $table->foreignIdFor(DetailHead::class)->constrained()->restrictOnDelete();
            $table->foreignIdFor(BillType::class)->constrained()->restrictOnDelete();
            $table->foreignIdFor(Form::class)->constrained()->restrictOnDelete();

            $table->unsignedBigInteger('bill_number');

            $table->string('source_bill_id')->nullable();

            $table->timestamp('bill_date')->useCurrent();

            $table->enum('source', BillSource::values());
            $table->enum('category', BillCategory::values());

            $table->decimal('net_amount', 15, 2);
            $table->decimal('gross_amount', 15, 2);

            $table->boolean('is_urgent')->default(false);
            $table->enum('payment_type', PaymentType::values());

            $table->unique(
                ['financial_year_id', 'treasury_id', 'bill_number'],
                'uk_bill_number_fy_treasury'
            );

            $table->index(['financial_year_id', 'treasury_id'], 'idx_bills_fy_treasury');
            $table->index('bill_date', 'idx_bills_date');
            $table->index('source', 'idx_bills_source');
            $table->index('category', 'idx_bills_category');
            $table->index('payment_type', 'idx_bills_payment_type');

            $table->timestamps();

            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bills');
    }
};