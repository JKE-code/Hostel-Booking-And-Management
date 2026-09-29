<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('roll_number')->unique();
            $table->enum('department', [
                'CSE', 'ECE', 'EEE', 'MECH', 'CIVIL', 'IT',
                'MBA', 'MCA', 'PHD', 'OTHER'
            ]);                                                    // Enum prevents free-text inconsistency
            $table->unsignedTinyInteger('year_of_study');          // 1–4
            $table->string('phone', 15)->index();
            $table->string('parent_name');
            $table->string('parent_phone', 15);
            $table->string('emergency_contact', 15)->nullable();
            $table->string('blood_group', 5)->nullable();          // A+, B-, O+, AB+, etc.
            $table->enum('gender', ['male', 'female', 'other']);
            $table->date('date_of_birth')->nullable();
            $table->text('permanent_address')->nullable();
            $table->enum('onboarding_status', ['whitelisted', 'active', 'archived'])->default('whitelisted');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
