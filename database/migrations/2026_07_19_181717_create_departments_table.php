
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->string('dept_head', 100)->nullable();
            $table->string('status', 20)->default('Active');
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });
    }
    public function down(): void { Schema::dropIfExists('departments'); }
};