<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mess_menus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hostel_id')
                  ->nullable()
                  ->constrained('hostels')
                  ->nullOnDelete();           // null = applies to all hostels
            $table->enum('day_of_week', [
                'monday', 'tuesday', 'wednesday',
                'thursday', 'friday', 'saturday', 'sunday'
            ]);
            $table->enum('meal_type', ['breakfast', 'lunch', 'snacks', 'dinner']);
            $table->string('menu_title');                  // "Idli Sambar + Vada"
            $table->text('items');                         // Full item list (stored as text or JSON)
            $table->string('special_note')->nullable();    // "Egg option available"
            $table->boolean('is_active')->default(true);
            $table->date('effective_from')->nullable();    // For temporary menu changes
            $table->date('effective_until')->nullable();
            $table->timestamps();

            // One menu entry per hostel/day/meal combination
            $table->unique(['hostel_id', 'day_of_week', 'meal_type', 'effective_from'],
                           'mess_menus_unique');
            // Fast lookup for API: today's menu for a hostel
            $table->index(['hostel_id', 'day_of_week', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mess_menus');
    }
};
