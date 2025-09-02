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
        
        Schema::create('po_test_lines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('po_id');
            $table->integer('sample_type_id');
            $table->string('test')->nullable();
            $table->boolean('status')->default(1);
            $table->timestamps();
            $table->foreign('po_id')->references('id')->on('po_masters')->onDelete('cascade');
        });

        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('po_test_lines');

        
    }
};
