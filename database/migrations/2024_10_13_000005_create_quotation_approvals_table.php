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
        Schema::create('quotation_approvals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('quotation_id');
            $table->unsignedBigInteger('submitted_by');
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('submitted_at');
            $table->timestamp('approved_at')->nullable();
            $table->tinyInteger('status')->default(1)->comment('1:Pending, 2:Approved, 3:Rejected');
            $table->text('remarks')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
            
            // Foreign Keys
            $table->foreign('quotation_id')->references('id')->on('quotations')->onDelete('cascade');
            $table->foreign('submitted_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
            
            // Indexes
            $table->index('quotation_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotation_approvals');
    }
};

