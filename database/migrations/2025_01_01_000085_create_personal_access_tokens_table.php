<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Laravel Sanctum personal access tokens table.
// Required for Phase 3: Flutter Bearer Token authentication.
// This is the standard Sanctum schema — do NOT modify column names.
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('personal_access_tokens', function (Blueprint $table) {
            $table->id();
            $table->morphs('tokenable');                   // polymorphic: tokenable_type + tokenable_id
            $table->string('name');                        // token nickname e.g. 'flutter-app'
            $table->string('token', 64)->unique();         // hashed token value
            $table->text('abilities')->nullable();         // JSON array of abilities e.g. ["*"]
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('expires_at')->nullable();   // optional token expiry
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('personal_access_tokens');
    }
};
