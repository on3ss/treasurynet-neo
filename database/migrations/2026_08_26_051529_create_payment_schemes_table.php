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
        Schema::create('payment_schemes', function (Blueprint $table) {
            $table->id();
            $table->char('demand_number', 2);
            $table->char('major_head', 4);
            $table->char('submajor_head', 2);
            $table->char('minor_head', 3);
            $table->char('subminor_head', 2);
            $table->char('sub_head', 2);
            $table->enum('charged_or_voted', ['V', 'C']);
            $table->tinyInteger('flag')->default(0);
            $table->char('code', 17);
            $table->string('description');
            $table->char('sector_code', 2)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique('code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_schemes');
    }
};