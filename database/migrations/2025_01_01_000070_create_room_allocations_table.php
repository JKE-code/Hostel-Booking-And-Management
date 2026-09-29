<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('room_allocations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('bed_id')->constrained('beds')->cascadeOnDelete();
            $table->string('academic_year', 9);                    // e.g. 2025-2026
            $table->date('allocated_from');
            $table->date('allocated_to')->nullable();
            $table->date('vacated_at')->nullable();                 // Set upon room transfer or checkout
            $table->enum('status', ['active', 'vacated', 'cancelled'])->default('active');
            $table->text('remarks')->nullable();
            $table->foreignId('allocated_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();
            $table->timestamps();

            // Indexes for fast lookups & lifecycle tracking (allows room transfers within same AY)
            $table->index(['student_id', 'status']);
            $table->index(['student_id', 'academic_year', 'status']);
            $table->index(['bed_id', 'status']);
            $table->index(['academic_year', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('room_allocations');
    }
};
