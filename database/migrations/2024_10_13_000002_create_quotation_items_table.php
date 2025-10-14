<?php

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
        Schema::create('quotation_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('quotation_id');
            
            // Sample Information
            $table->unsignedBigInteger('sample_type_id');
            
            // Item Details
            $table->string('description')->nullable();
            $table->integer('sample_count')->default(1);
            $table->decimal('sample_rate', 15, 2)->default(0);
            $table->decimal('sample_total', 15, 2)->default(0);
            $table->decimal('test_total', 15, 2)->default(0); // Sum of all test rates
            $table->decimal('discount_percent', 5, 2)->default(0);
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->decimal('line_total', 15, 2);
            
            // TAT (Turn Around Time)
            $table->integer('tat_days')->nullable();
            
            $table->timestamps();
            
            // Foreign Keys
            $table->foreign('quotation_id')->references('id')->on('quotations')->onDelete('cascade');
            $table->foreign('sample_type_id')->references('id')->on('sample_types')->onDelete('cascade');
            
            // Indexes
            $table->index('quotation_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotation_items');
    }
};

