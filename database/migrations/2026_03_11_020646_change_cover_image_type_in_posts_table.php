<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('posts', function (Blueprint $table) {
            // Ubah tipe data menjadi text
            $table->text('cover_image')->change();
        });
    }

    public function down()
    {
        Schema::table('posts', function (Blueprint $table) {
            // Kembalikan ke string/varchar jika di-rollback
            $table->string('cover_image', 255)->change();
        });
    }
};