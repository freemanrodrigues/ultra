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
        Schema::create('po_tests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('po_id');
            $table->unsignedBigInteger('po_sample_id');
            $table->unsignedBigInteger('test_id');
            $table->decimal('price', 10, 2);
            $table->integer('quantity')->default(1);
            $table->decimal('total', 10, 2);
            $table->timestamps();

            $table->foreign('po_sample_id')->references('id')->on('po_samples')->onDelete('cascade');
            $table->foreign('test_id')->references('id')->on('test_masters')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('po_tests');
    }
};

