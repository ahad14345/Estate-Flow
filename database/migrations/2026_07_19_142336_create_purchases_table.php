<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id(); // Native Oracle Identity Column Sequence
            $table->string('purchase_order_no')->unique(); // Unique PO-2026-0001
            $table->date('purchase_date');
            $table->date('expected_delivery_date')->nullable();
            
            // Stakeholder Strings (Matches Oracle string definitions)
            $table->string('vendor_name', 150);
            $table->string('contractor_name', 150)->nullable();
            $table->string('project_name', 150);
            $table->string('category', 50);
            
            // Item Information
            $table->string('item_name', 200);
            $table->string('item_description', 1000)->nullable();
            $table->decimal('quantity', 12, 2);
            $table->string('unit', 20); // Bag, Piece, Ton, etc.
            
            // Financial Calculations
            $table->decimal('unit_price', 12, 2);
            $table->decimal('discount', 12, 2)->default(0.00);
            $table->decimal('tax', 12, 2)->default(0.00);
            $table->decimal('total_amount', 14, 2);
            
            // ERP Transaction State Flow Indicators
            $table->string('payment_method', 30); // Cash, Bank, Cheque, bKash, etc.
            $table->string('payment_status', 20)->default('Unpaid'); // Paid, Partial, Unpaid
            $table->string('purchase_status', 20)->default('Pending'); // Pending, Ordered, Delivered, Cancelled
            
            // Document Tracking & Audit Logs
            $table->string('invoice_attachment', 255)->nullable();
            $table->string('remarks', 1000)->nullable();
            $table->string('created_by', 100);
            
            $table->softDeletes(); // Oracle TIMESTAMP wrapper tracking via Eloquent
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};