<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('outpasses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('hostel_id')
                  ->nullable()
                  ->constrained('hostels')
                  ->nullOnDelete();           // Direct FK — avoids 5-table join for "who is outside"
            $table->enum('leave_type', ['day_pass', 'weekend', 'vacation', 'emergency', 'medical', 'academic']);
            $table->string('destination');
            $table->text('reason');
            $table->dateTime('out_datetime');
            $table->dateTime('in_datetime');
            $table->dateTime('actual_return_datetime')->nullable(); // filled on re-entry
            $table->boolean('parent_consent')->default(false);
            $table->string('parent_consent_via')->nullable();       // 'sms', 'call', 'form'
            $table->enum('status', ['pending', 'approved', 'rejected', 'cancelled', 'returned', 'overdue'])
                  ->default('pending');
            $table->foreignId('approved_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();
            $table->dateTime('approved_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->string('qr_verification_code', 64)->unique()->nullable();
            $table->timestamps();

            // Indexes for common query patterns
            $table->index(['student_id', 'status']);           // Student portal: "my pending outpasses"
            $table->index(['status', 'in_datetime']);          // Admin: overdue detection job
            $table->index(['hostel_id', 'status']);            // Security / Warden: "who is outside by hostel"
            $table->index(['hostel_id', 'out_datetime']);      // Security gate daily log
            $table->index('out_datetime');                     // Date-range reports
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('outpasses');
    }
};
