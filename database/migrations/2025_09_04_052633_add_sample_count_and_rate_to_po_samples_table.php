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
        Schema::table('po_samples', function (Blueprint $table) {
            $table->integer('sample_count')->default(1)->after('description');
            $table->decimal('sample_rate', 10, 2)->default(0.00)->after('sample_count');
            $table->decimal('sample_total', 10, 2)->default(0.00)->after('sample_rate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('po_samples', function (Blueprint $table) {
            $table->dropColumn(['sample_count', 'sample_rate', 'sample_total']);
        });
    }
};