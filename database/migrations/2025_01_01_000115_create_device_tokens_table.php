<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('device_tokens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('fcm_token')->unique();         // Firebase Cloud Messaging device token
            $table->string('device_type', 10)
                  ->default('android');                   // 'android' | 'ios'
            $table->string('device_name')->nullable();     // e.g. "Rahul's Redmi Note 12"
            $table->string('app_version', 20)->nullable(); // e.g. "1.0.4"
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_used_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'is_active']); // Get all active tokens for a user
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('device_tokens');
    }
};
