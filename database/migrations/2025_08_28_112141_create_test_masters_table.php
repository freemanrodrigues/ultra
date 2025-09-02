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
        Schema::create('test_masters', function (Blueprint $table) {
            $table->id();
            $table->string('test_name')->unique()->nullable();
            $table->string('default_unit')->nullable();
            $table->string('tat_hours_default')->nullable();
            $table->boolean('status')->default(1);
            $table->timestamps();
            
            
            
        
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test_masters');
    }
};
