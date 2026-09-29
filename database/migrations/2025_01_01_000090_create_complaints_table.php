<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('room_id')
                  ->nullable()
                  ->constrained('rooms')
                  ->nullOnDelete();
            $table->enum('category', [
                'electrical', 'plumbing', 'wifi', 'cleaning',
                'carpentry', 'ac_fan', 'pest_control', 'other'
            ]);
            $table->string('title');
            $table->text('description');
            $table->string('photo_url')->nullable();
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->enum('status', ['submitted', 'in_progress', 'resolved', 'closed'])->default('submitted');
            $table->foreignId('assigned_to')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();
            $table->dateTime('assigned_at')->nullable();
            $table->text('warden_remarks')->nullable();
            $table->dateTime('resolved_at')->nullable();
            $table->unsignedTinyInteger('resolution_rating')->nullable(); // 1–5 stars
            $table->timestamps();

            // Indexes for common query patterns
            $table->index(['student_id', 'status']);           // Student portal: "my open tickets"
            $table->index(['status', 'priority']);             // Admin: filter by status & priority
            $table->index('assigned_to');                      // Staff: "tickets assigned to me"
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};
