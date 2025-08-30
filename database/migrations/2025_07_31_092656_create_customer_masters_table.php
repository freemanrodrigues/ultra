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
            $table->string('customer_name');
            $table->string('site')->nullable();
            $table->string('division')->nullable();
            $table->integer('company_id')->nullable();
            $table->boolean('b2c_customer')->default(0);
            $table->string('gst_no',15)->unique()->nullable();
            $table->string('gst_state_code',2)->nullable();
            $table->string('address');
            $table->string('address1')->nullable();
            $table->string('address2')->nullable();
            $table->string('city');
            $table->string('state');
            $table->integer('country');
            $table->string('pincode');
            $table->boolean('is_billing')->nullable();
            $table->string('landline')->nullable();
            $table->string('billing_cycle');
            $table->string('credit_cycle');
            $table->string('group');
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
