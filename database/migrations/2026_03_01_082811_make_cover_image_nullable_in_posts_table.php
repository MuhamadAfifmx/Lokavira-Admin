<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            // Mengubah kolom cover_image menjadi nullable
            $table->string('cover_image')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            // Kembalikan ke tidak nullable jika rollback
            $table->string('cover_image')->nullable(false)->change();
        });
    }
};