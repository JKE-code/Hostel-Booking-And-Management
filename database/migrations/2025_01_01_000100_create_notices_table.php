<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notices', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->enum('category', [
                'general', 'fee', 'maintenance', 'event',
                'academic', 'rule', 'emergency'
            ])->default('general');
            $table->enum('target_audience', ['all', 'boys_hostel', 'girls_hostel', 'new_boys_hostel'])
                  ->default('all');
            $table->boolean('is_pinned')->default(false);          // Pin urgent notices to top
            $table->boolean('is_published')->default(true);
            $table->foreignId('published_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();
            $table->string('attachment_url')->nullable();          // PDF / image URL
            $table->dateTime('expires_at')->nullable();            // Auto-archive date
            $table->timestamps();

            // Indexes for common query patterns
            $table->index(['is_published', 'is_pinned']);      // Public notice board: pinned first
            $table->index(['target_audience', 'is_published']); // Per-hostel notice filtering
            $table->index('expires_at');                        // Expiry cleanup job
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notices');
    }
};
