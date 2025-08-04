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
        Schema::create('customer_site_masters', function (Blueprint $table) {
            $table->id();
           
            $table->integer('company_id')->nullable();
            $table->integer('customer_id')->nullable();
            $table->integer('site_master_id')->nullable();
            $table->string('site_customer_code')->nullable();
            $table->string('site_customer_name')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('pincode')->nullable();
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
        Schema::dropIfExists('customer_site_masters');
    }
};
