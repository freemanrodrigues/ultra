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
        Schema::create('sample_details', function (Blueprint $table) {
            $table->id();
            $table->integer('sample_id')->unsigned();
            $table->integer('device_id')->unsigned();
            $table->integer('company_id')->unsigned();
            $table->integer('customer_id')->unsigned();
            $table->integer('sitemaster_id')->unsigned();

$table->integer('type_of_sample')->unsigned();
$table->integer('nature_of_sample')->unsigned();
$table->integer('running_hrs')->nullable();
$table->integer('sub_asy_no')->nullable();
$table->string('sub_asy_hrs')->nullable();
$table->dateTime('sampling_date')->nullable();
$table->string('brand_of_oil')->nullable();
$table->string('grade')->nullable();
$table->string('lube_oil_running_hrs')->nullable();
$table->string('top_up_volume')->nullable();
$table->string('sump_capacity')->nullable();
$table->string('sampling_from')->nullable();
$table->dateTime('report_expected_date')->nullable();
$table->string('qty')->nullable();
$table->integer('bottle_types_id')->unsigned();
$table->text('problem')->nullable();
$table->text('comments')->nullable();
$table->text('customer_note')->nullable();
$table->string('severity')->nullable();
$table->char('oil_drained')->nullable();
$table->string('image')->nullable();
$table->string('fir')->nullable();
$table->string('invoice')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sample_details');
    }
};
