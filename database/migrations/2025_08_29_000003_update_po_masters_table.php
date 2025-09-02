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
        Schema::table('po_masters', function (Blueprint $table) {
            // Add new columns
            $table->unsignedBigInteger('customer_id')->nullable()->after('id');
            $table->date('po_start_date')->nullable()->after('po_date');
            $table->date('po_end_date')->nullable()->after('po_start_date');
            $table->decimal('total_amount', 10, 2)->default(0)->after('po_end_date');
            
            // Add foreign key for customer_id
            $table->foreign('customer_id')->references('id')->on('customer_masters')->onDelete('restrict');
        });

        // Update existing records to set default values
        DB::statement('UPDATE po_masters SET po_start_date = po_date WHERE po_start_date IS NULL');
        DB::statement('UPDATE po_masters SET po_end_date = DATE_ADD(po_date, INTERVAL 1 YEAR) WHERE po_end_date IS NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('po_masters', function (Blueprint $table) {
            // Drop foreign key
            $table->dropForeign(['customer_id']);
            
            // Drop new columns
            $table->dropColumn(['customer_id', 'po_start_date', 'po_end_date', 'total_amount']);
        });
    }
};

