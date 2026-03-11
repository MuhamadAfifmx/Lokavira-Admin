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
    Schema::create('package_features', function (Blueprint $table) {
        $table->id();
        // Menghubungkan fitur ke paket. Jika paket dihapus, fitur otomatis terhapus (cascade)
        $table->foreignId('package_id')->constrained('packages')->onDelete('cascade');
        $table->string('feature_name');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('package_features');
    }
};
