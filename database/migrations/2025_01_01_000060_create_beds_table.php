<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('beds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained('rooms')->cascadeOnDelete();
            $table->string('bed_identifier', 5);                   // A, B, C, D
            $table->enum('status', ['available', 'occupied', 'maintenance'])->default('available');
            $table->timestamps();

            $table->unique(['room_id', 'bed_identifier']);
            $table->index(['room_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('beds');
    }
};
