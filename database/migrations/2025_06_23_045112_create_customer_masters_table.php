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
        Schema::create('customer_masters', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name')->nullable();
            $table->string('display_name')->nullable();
            $table->string('company_id');
            $table->string('gst_no');
            $table->string('address')->nullable();
            $table->string('address1')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('pincode')->nullable();
            $table->string('billing_cycle')->nullable();
            $table->string('credit_cycle')->nullable();
            $table->string('group')->nullable();
            $table->integer('sales_person_id')->nullable();
            $table->boolean('status')->default(1);
            $table->string('account_category')->nullable();
          
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_masters');
    }
};
