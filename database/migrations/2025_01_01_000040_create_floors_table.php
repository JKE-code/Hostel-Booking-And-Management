<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('floors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('block_id')->constrained('blocks')->cascadeOnDelete();
            $table->unsignedTinyInteger('floor_number');           // 0 = Ground, 1, 2, 3...
            $table->string('floor_label')->nullable();             // "Ground Floor", "First Floor"
            $table->timestamps();

            $table->unique(['block_id', 'floor_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('floors');
    }
};
