<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hostels', function (Blueprint $table) {
            $table->id();
            $table->string('name');                                         // Boys Hostel, Girls Hostel
            $table->string('code', 10)->unique();                           // BH, GH
            $table->enum('gender_type', ['male', 'female', 'mixed']);
            $table->unsignedSmallInteger('total_capacity')->default(0);
            $table->foreignId('warden_in_charge_id')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();
            $table->text('address')->nullable();
            $table->string('contact_phone', 15)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hostels');
    }
};
