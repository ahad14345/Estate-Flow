
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('employee_code', 30)->unique();
            $table->string('first_name', 50);
            $table->string('last_name', 50);
            $table->string('photo', 255)->nullable();
            $table->string('gender', 20);
            $table->date('dob');
            $table->string('email', 100)->unique();
            $table->string('phone', 30);
            $table->text('address');
            $table->foreignId('department_id')->constrained();
            $table->string('designation', 100);
            $table->date('joining_date');
            $table->string('emp_type', 50); // Permanent, Contractual
            $table->decimal('salary', 14, 2);
            $table->string('emergency_contact', 50);
            $table->string('username', 50)->unique();
            $table->string('password', 255);
            $table->string('status', 20)->default('Active');
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });
    }
    public function down(): void { Schema::dropIfExists('employees'); }
};