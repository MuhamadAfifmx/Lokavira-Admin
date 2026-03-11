<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::create('posts', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->string('platform'); // instagram, tiktok, youtube
        $table->string('title')->nullable();
        $table->string('cover_image'); // Path gambar cover
        $table->string('post_url'); // Link ke video/postingan
        $table->date('upload_date');
        
        // Data Umum
        $table->integer('views')->default(0);
        $table->integer('likes')->default(0);
        $table->integer('comments')->default(0);
        $table->integer('shares')->default(0);

        // Khusus TikTok & YouTube (Usia Penonton dalam format JSON)
        // Contoh: {"18-24": "18%", "30-45": "24%"}
        $table->json('age_demographics')->nullable();

        // Khusus TikTok (Rata-rata Menonton)
        $table->string('avg_watch_time')->nullable();

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
