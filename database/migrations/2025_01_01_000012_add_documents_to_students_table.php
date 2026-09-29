<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->string('photo_url')->nullable()->after('permanent_address');
            $table->string('id_proof_url')->nullable()->after('photo_url');
            $table->string('admission_letter_url')->nullable()->after('id_proof_url');
            $table->string('medical_cert_url')->nullable()->after('admission_letter_url');
            $table->dateTime('document_verified_at')->nullable()->after('medical_cert_url');
            $table->foreignId('document_verified_by')->nullable()->after('document_verified_at')->constrained('users')->nullOnDelete();
            $table->text('verification_notes')->nullable()->after('document_verified_by');
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign(['document_verified_by']);
            $table->dropColumn([
                'photo_url',
                'id_proof_url',
                'admission_letter_url',
                'medical_cert_url',
                'document_verified_at',
                'document_verified_by',
                'verification_notes',
            ]);
        });
    }
};
