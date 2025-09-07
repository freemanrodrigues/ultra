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
        Schema::create('sample_type_rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sample_type_id')->constrained('sample_types')->onDelete('cascade');
            $table->foreignId('test_id')->constrained('test_masters')->onDelete('cascade');
            $table->decimal('rate', 10, 2)->default(0.00);
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Ensure unique combination of sample_type_id and test_id
            $table->unique(['sample_type_id', 'test_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sample_type_rates');
    }
};