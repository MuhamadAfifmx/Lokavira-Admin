<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            // Posisi kolom disusun sesuai request Anda
            $table->id(); 
            $table->string('logo')->nullable();
            $table->string('business_name')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('email')->unique(); // Ini email usaha sekaligus email login
            $table->string('username')->unique(); // Username login
            $table->string('password');
            $table->string('representative_name')->nullable(); // Nama Pengusaha / PIC
            $table->timestamp('subscribed_at')->nullable(); // Kapan Mulai
            $table->timestamp('expires_at')->nullable();    // Kapan Habis
            
            // Kolom pendukung Laravel
            $table->foreignId('package_id')->nullable()->constrained('packages')->onDelete('set null');
            $table->boolean('is_admin')->default(false);
            $table->rememberToken();
            $table->timestamps(); // Menghasilkan created_at dan updated_at
        });

        // Token reset password tetap diperlukan oleh Laravel UI
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};