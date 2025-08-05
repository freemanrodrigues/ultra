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
        Schema::create('equipment_components', function (Blueprint $table) {
            $table->id();
            $table->integer('equipment_id');
            $table->string('component_name')->nullable();
            $table->string('component_serial_number')->nullable();
            $table->string('component_type')->nullable();
            $table->string('assigned_fromdate')->nullable();
            $table->string('assigned_todate')->nullable();         
            $table->integer('createdby_id')->nullable();
            $table->integer('updatedby_id')->nullable();
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment_components');
    }
};
