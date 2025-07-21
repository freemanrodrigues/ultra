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
        Schema::create('gst_masters', function (Blueprint $table) {
            $table->id();
            $table->string('customer');
            $table->string('site');
            $table->string('gst_no');
            $table->boolean('composite');
            $table->string('address');
            $table->string('pincode');
            $table->string('state');
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gst_masters');
    }
};
