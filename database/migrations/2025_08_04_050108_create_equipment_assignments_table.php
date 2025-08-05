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
        Schema::create('equipment_assignments', function (Blueprint $table) {
            $table->id();
            $table->integer('equipment_id');
            $table->integer('company_id')->nullable();
            $table->integer('customer_id')->nullable();
            $table->integer('customer_site_id')->nullable();
            $table->string('customer_site_equiment_name')->nullable();
            $table->string('assigned_fromdate')->nullable();
            $table->string('assigned_todate')->nullable();
            $table->boolean('status')->default(1);	
            $table->integer('createdby_id')->nullable();
            $table->integer('updatedby_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment_assignments');
    }
};
