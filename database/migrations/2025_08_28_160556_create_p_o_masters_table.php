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
            $table->integer('party_id');
            $table->integer('site_id')->nullable();
            $table->datetime('valid_from');
            $table->datetime('valid_to');
            $table->string('currency',3)->default('INR');
            $table->integer('sample_type_id')->nullable();
            $table->string('test_rate');
            $table->integer('test_limit')->default(0);
            $table->integer('status')->default(1);
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
