<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Creates the students table with all required fields.
     */
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();                          // Auto-increment primary key
            $table->string('name', 100);           // Student full name (max 100 chars)
            $table->unsignedTinyInteger('age');    // Age (0–255, no negatives needed)
            $table->string('email', 150)->unique();// Unique email address
            $table->string('course', 100);         // Course/program name
            $table->timestamps();                  // created_at + updated_at
        });
    }

    /**
     * Reverse the migrations.
     * Drops the students table if we roll back.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};