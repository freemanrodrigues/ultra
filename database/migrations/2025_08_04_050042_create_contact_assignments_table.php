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
        Schema::create('contact_assignments', function (Blueprint $table) {
            $table->id();
            $table->integer('contact_id')->nullable();
            $table->integer('company_id')->nullable();
            $table->integer('customer_id')->nullable();
            $table->integer('customer_site_id')->nullable();
            $table->integer('equipment_id')->nullable();
            $table->string('department')->nullable();
            $table->string('designation')->nullable();
            $table->string('role')->nullable();
            $table->string('level')->nullable();
            $table->boolean('send_bill')->nullable();
            $table->boolean('send_report')->nullable();
            $table->boolean('whatsapp')->nullable();
            $table->boolean('is_primary')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_assignments');
    }
};
