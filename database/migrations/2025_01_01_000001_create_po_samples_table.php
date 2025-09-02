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
        Schema::create('po_samples', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('po_id');
            $table->unsignedBigInteger('sample_type_id');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('po_id')->references('id')->on('po_masters')->onDelete('cascade');
            $table->foreign('sample_type_id')->references('id')->on('sample_types')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('po_samples');
    }
};

