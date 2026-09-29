<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academic_years', function (Blueprint $table) {
            $table->id();
            $table->string('label', 9)->unique();          // '2025-2026'
            $table->date('start_date');                    // 2025-08-01
            $table->date('end_date');                      // 2026-05-31
            $table->boolean('is_current')->default(false); // Only one should be true at a time
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_years');
    }
};
