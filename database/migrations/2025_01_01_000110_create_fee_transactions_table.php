<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fee_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('hostel_id')
                  ->nullable()
                  ->constrained('hostels')
                  ->nullOnDelete();        // Which hostel the fee applies to
            $table->decimal('amount', 10, 2);
            $table->enum('payment_type', ['hostel_fee', 'mess_fee', 'amenity_fee', 'fine', 'security_deposit']);
            $table->string('academic_year', 9);                    // 2025-2026
            $table->string('transaction_id')->unique()->nullable(); // Payment gateway ref
            $table->string('payment_method')->nullable();          // 'online', 'cash', 'dd'
            $table->enum('status', ['paid', 'pending', 'failed', 'refunded'])->default('pending');
            $table->string('receipt_path')->nullable();            // Stored PDF receipt path
            $table->text('remarks')->nullable();
            $table->dateTime('paid_at')->nullable();
            $table->timestamps();

            // Indexes for common query patterns
            $table->index(['student_id', 'academic_year', 'status']); // Fee status per student per year
            $table->index(['status', 'payment_type']);                 // Admin: pending fee reports
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fee_transactions');
    }
};
