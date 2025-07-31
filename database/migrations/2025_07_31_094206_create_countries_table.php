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
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('countryname');
            $table->string('shortname')->nullable();
            $table->string('isocode',2)->nullable();
            $table->string('isocode3',3)->nullable();
            $table->string('tel_countrycode',12)->nullable();
            $table->string('currencycode',4)->nullable();
            $table->string('currencyrate',12)->nullable();
            $table->string('currencysign',6)->nullable();
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};
