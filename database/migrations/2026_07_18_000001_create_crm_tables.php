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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('customer_code')->unique();
            $table->string('full_name');
            $table->string('email')->unique();
            $table->string('phone_number')->unique();
            $table->string('national_id')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('customer_type');
            $table->string('preferred_property_type')->nullable();
            $table->decimal('budget', 12, 2)->nullable()->default(0); // 👈 Nullable to prevent SQLite constraints
            $table->string('assigned_employee')->nullable();
            $table->string('status')->default('Lead');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('lead_code')->unique();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->string('full_name');
            $table->string('email')->unique();
            $table->string('phone_number')->unique();
            $table->string('lead_source');
            $table->string('priority')->default('Medium');
            $table->string('status')->default('New');
            $table->string('assigned_employee')->nullable();
            $table->decimal('budget', 12, 2)->nullable()->default(0); // 👈 Nullable to prevent SQLite constraints
            $table->text('notes')->nullable();
            $table->timestamp('converted_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('follow_ups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->cascadeOnDelete();
            $table->foreignId('lead_id')->nullable()->constrained('leads')->nullOnDelete();
            $table->string('follow_up_type');
            $table->string('subject');
            $table->text('notes')->nullable();
            $table->dateTime('scheduled_at');
            $table->string('reminder_status')->default('Pending');
            $table->string('status')->default('Pending');
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('customer_property_interests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->string('property_name');
            $table->string('property_reference')->nullable();
            $table->string('interest_level')->default('High');
            $table->date('visit_date')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });

        Schema::create('customer_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->string('activity_type');
            $table->string('subject');
            $table->text('description')->nullable();
            $table->string('performed_by')->nullable();
            $table->timestamp('occurred_at')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_activities');
        Schema::dropIfExists('customer_property_interests');
        Schema::dropIfExists('follow_ups');
        Schema::dropIfExists('leads');
        Schema::dropIfExists('customers');
    }
};