<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hostel_wardens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hostel_id')->constrained('hostels')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('role_title', ['chief_warden', 'assistant_warden', 'resident_warden'])->default('assistant_warden');
            $table->boolean('is_primary')->default(false);
            $table->string('contact_phone', 15)->nullable();
            $table->timestamps();

            $table->unique(['hostel_id', 'user_id']);
            $table->index(['hostel_id', 'is_primary']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hostel_wardens');
    }
};
