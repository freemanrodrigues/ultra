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
        Schema::create('sample_oil_types', function (Blueprint $table) {
            $table->id();
            $table->string('sample_oil_type_code')->unique()->nullable();
            $table->string('sample_oil_type_name')->unique()->nullable();
            $table->string('remark')->nullable();
            $table->string('mis_group')->nullable();
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sample_oil_types');
    }
};
