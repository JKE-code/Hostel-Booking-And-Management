<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visitors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('hostel_id')
                  ->nullable()
                  ->constrained('hostels')
                  ->nullOnDelete();
            $table->string('visitor_name');
            $table->string('relation');                    // Father, Mother, Guardian, Friend, etc.
            $table->string('visitor_phone', 15);
            $table->string('visitor_id_type')->nullable(); // Aadhaar, PAN, Driving Licence
            $table->string('visitor_id_number')->nullable();
            $table->unsignedSmallInteger('visitor_count')->default(1); // number of people visiting
            $table->date('visit_date');
            $table->time('expected_arrival')->nullable();
            $table->time('expected_departure')->nullable();
            $table->text('purpose')->nullable();
            $table->enum('status', ['pre_registered', 'checked_in', 'checked_out', 'no_show', 'rejected'])
                  ->default('pre_registered');
            $table->timestamp('checked_in_at')->nullable();
            $table->timestamp('checked_out_at')->nullable();
            $table->foreignId('approved_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();
            $table->text('remarks')->nullable();
            $table->timestamps();

            // Performance indexes
            $table->index(['student_id', 'visit_date']);   // Student: my scheduled visitors
            $table->index(['hostel_id', 'visit_date', 'status']); // Gate desk: today's visitor log
            $table->index(['status', 'visit_date']);       // Admin: pending approvals by date
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visitors');
    }
};
