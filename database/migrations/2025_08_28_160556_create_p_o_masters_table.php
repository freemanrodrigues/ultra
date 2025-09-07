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
        Schema::create('po_masters', function (Blueprint $table) {
            $table->id();
            $table->string('po_number');
            $table->datetime('po_date')->nullable();
            $table->integer('company_id');
            $table->integer('site_id')->nullable();
            $table->datetime('valid_from');
            $table->datetime('valid_to');
            $table->string('currency',3)->default('INR');
            $table->string('test_rate')->nullable();
            $table->integer('test_limit')->default(0);
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->integer('status')->default(1);
            
            
            // Add foreign key for customer_id
            // $table->foreign('company_id')->references('id')->on('company_masters')->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('po_masters');
    }
};
