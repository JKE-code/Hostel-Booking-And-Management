<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('otps', function (Blueprint $table) {
            $table->id();
            $table->string('identifier')->index(); // email or phone
            $table->string('otp_code', 255);      // Hashed storage for security
            $table->string('purpose')->default('student_signup');
            $table->unsignedTinyInteger('attempts')->default(0); // Max 5 attempts
            $table->boolean('is_locked')->default(false);
            $table->timestamp('expires_at');
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('otps');
    }
};
