<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            // Foreign key linking to the projects table
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            
            $table->string('title', 150);
            $table->string('type', 50); // e.g., Apartment, Commercial Space, Plot
            $table->string('block', 50)->nullable();
            $table->string('size_sqft', 30)->nullable();
            $table->string('status', 30)->default('Available'); // Available, Sold, Reserved
            $table->decimal('price', 15, 2)->default(0.00);
            $table->text('details')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};