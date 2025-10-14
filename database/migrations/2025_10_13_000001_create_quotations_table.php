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
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            $table->string('quotation_number')->unique();
            $table->integer('revision')->default(1);
            $table->string('reference_number')->nullable();
            
            // Customer Information
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('site_id')->nullable();
            $table->text('billing_address')->nullable();
            $table->text('shipping_address')->nullable();
            
            // Quotation Details
            $table->date('quotation_date');
            $table->date('valid_from');
            $table->date('valid_until');
            $table->integer('validity_days')->default(30);
            
            // Financial Details
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('discount_percent', 5, 2)->default(0);
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->decimal('tax_percent', 5, 2)->default(18.00);
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->decimal('total_amount', 15, 2)->default(0);
            
            // Additional Information
            $table->text('terms_and_conditions')->nullable();
            $table->text('notes')->nullable();
            $table->text('special_instructions')->nullable();
            
            // Status and Workflow
            $table->tinyInteger('status')->default(1)->comment('1:Draft, 2:Pending Approval, 3:Approved, 4:Converted to Order, 5:Closed, 6:Cancelled');
            
            // User Tracking
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            
            // Conversion Tracking
            $table->unsignedBigInteger('converted_to_po_id')->nullable();
            $table->timestamp('converted_at')->nullable();
            
            // Expiry Alert
            $table->boolean('expiry_alert_sent')->default(false);
            
            $table->timestamps();
            $table->softDeletes();
            
            // Foreign Keys
            $table->foreign('company_id')->references('id')->on('company_masters')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('customer_masters')->onDelete('set null');
            $table->foreign('site_id')->references('id')->on('site_masters')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
            
            // Indexes
            $table->index('quotation_number');
            $table->index('status');
            $table->index('quotation_date');
            $table->index('valid_until');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotations');
    }
};

