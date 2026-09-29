<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hostel_id')->constrained('hostels')->cascadeOnDelete();
            $table->string('block_name');                          // Block A, Block B, East Wing
            $table->string('block_code', 5);                       // A, B, C — required for unique constraint to work reliably
            $table->unsignedTinyInteger('total_floors')->default(1);
            $table->timestamps();

            $table->unique(['hostel_id', 'block_code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blocks');
    }
};
