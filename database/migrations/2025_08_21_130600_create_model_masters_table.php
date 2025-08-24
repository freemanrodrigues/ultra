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
        Schema::create('model_masters', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('make_id');
            $table->string('model');
            $table->boolean('status')->default(1);
            $table->foreign('make_id')->references('id')->on('make_masters'); 
            $table->timestamps();
            $table->unique(['make_id', 'model'], 'unique_make_model');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('model_masters');
    }
};
