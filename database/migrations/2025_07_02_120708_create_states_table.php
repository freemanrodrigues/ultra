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
        Schema::create('states', function (Blueprint $table) {
            $table->id();
            $table->string('statename');
            $table->string('shortname')->nullable();
            $table->integer('country_id')->unsigned();
            $table->string('statecode',6)->nullable();
            $table->char('zone',1)->nullable();
            $table->boolean('status')->default(1);
            $table->foreign('country_id')->references('id')->on('countries'); 
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('states');
    }
};
