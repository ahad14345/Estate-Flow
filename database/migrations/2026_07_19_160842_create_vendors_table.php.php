<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->string('vendor_code', 30)->unique();
            $table->string('company_name', 150);
            $table->string('company_logo', 255)->nullable();
            $table->string('contact_person', 100);
            $table->string('biz_reg_no', 50)->nullable();
            $table->string('tax_vat_no', 50)->nullable();
            $table->string('phone', 30);
            $table->string('alt_phone', 30)->nullable();
            $table->string('email', 100)->unique();
            $table->string('website', 150)->nullable();
            $table->string('biz_category', 50); // e.g., Manufacturer, Distributor
            $table->string('mat_category', 50); // e.g., Cement, Steel
            $table->text('address');
            $table->string('city', 50);
            $table->string('postal_code', 20)->nullable();
            $table->string('country', 50)->default('Bangladesh');
            $table->string('bank_name', 100)->nullable();
            $table->string('bank_acc_no', 50)->nullable();
            $table->string('pay_method', 50)->nullable(); // Cash, EFT, Cheque
            $table->string('pay_terms', 50)->nullable();  // Net 30, Net 60
            $table->string('status', 20)->default('Active'); // Active, Inactive
            $table->unsignedTinyInteger('rating')->default(5);
            $table->text('notes')->nullable();
            $table->string('trade_license', 255)->nullable();
            
            // Aggregates stored for high-performance Oracle metrics/sorting
            $table->integer('total_pos')->default(0);
            $table->decimal('total_po_value', 14, 2)->default(0.00);
            $table->decimal('pending_payment', 14, 2)->default(0.00);
            
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            
            // Oracle-safe soft delete timestamp column mapping
            $table->timestamp('deleted_at')->nullable(); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};