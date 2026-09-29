<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('floor_id')->constrained('floors')->cascadeOnDelete();
            $table->string('room_number', 10);                     // A-101, B-204, G-108
            $table->unsignedTinyInteger('capacity')->default(2);   // 2, 3, 4 sharing
            $table->enum('room_type', ['AC', 'Non-AC'])->default('Non-AC');
            $table->decimal('base_fee', 10, 2)->default(0.00);     // Per academic year in INR
            $table->enum('status', ['active', 'available', 'full', 'maintenance', 'inactive'])->default('active');
            $table->timestamps();

            $table->unique(['floor_id', 'room_number']);
            $table->index(['floor_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
