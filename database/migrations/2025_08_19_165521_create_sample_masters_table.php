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
        Schema::create('sample_masters', function (Blueprint $table) {
            $table->id();
            $table->dateTime('sample_date');
            $table->integer('courier_id');
            $table->string('pod_no')->nullable();
            $table->integer('no_of_samples');
            $table->integer('customer_site_id')->nullable(); // customer_site_masters
            $table->integer('customer_id')->nullable();
            $table->dateTime('expected_report_date')->nullable();
            $table->string('work_order')->nullable();
            $table->dateTime('work_order_date')->nullable();
            $table->string('additional_info')->nullable();
            $table->dateTime('site_sample_dispacted_date')->nullable();
            $table->dateTime('collection_center_sample_received_date')->nullable();
            $table->dateTime('collection_center_sample_collected_date')->nullable();
            $table->dateTime('lab_sample_received_date')->nullable();
            $table->decimal('freight_charges', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sample_masters');
    }
};
