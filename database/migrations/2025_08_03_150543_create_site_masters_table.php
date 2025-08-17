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
        Schema::create('site_masters', function (Blueprint $table) {
            $table->id();
        //    $table->string('site_code')->nullable();
            $table->string('site_name')->unique();
            $table->string('address')->nullable();
        //    $table->string('address1')->nullable();
            $table->string('city')->nullable();
        //    $table->string('pincode')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('lat')->nullable();
            $table->string('long')->nullable();
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_masters');
    }
};
