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
        
        Schema::table('test_masters', function (Blueprint $table) {
            // Add new fields
            $table->unsignedBigInteger('sample_type_id')->nullable()->after('default_unit');
            $table->boolean('active')->default(1)->after('tat_hours_default');
            
            // Add foreign key constraint
            $table->foreign('sample_type_id')->references('id')->on('sample_types')->onDelete('set null');
            
            // Drop the old status column if it exists
            if (Schema::hasColumn('test_masters', 'status')) {
                $table->dropColumn('status');
            }
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('test_masters', function (Blueprint $table) {
            // Drop foreign key constraint
            $table->dropForeign(['sample_type_id']);
            
            // Drop new columns
            $table->dropColumn(['sample_type_id', 'active']);
            
            // Recreate the old status column
            $table->boolean('status')->default(1)->after('tat_hours_default');
        });
        
    }
};
