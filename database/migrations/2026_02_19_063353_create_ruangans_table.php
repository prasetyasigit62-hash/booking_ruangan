<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
    {
        Schema::create('ruangans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_ruangan');
            $table->integer('kapasitas')->comment('Kapasitas maksimal orang');
            $table->text('fasilitas')->comment('Misal: AC, Proyektor, Sound System, WiFi');
            $table->longText('deskripsi')->nullable();
            $table->string('foto_utama')->nullable()->comment('Untuk thumbnail katalog');
            $table->decimal('harga_per_jam', 15, 2)->default(0)->comment('Bisa 0 jika ruangan gratis');
            $table->enum('status', ['Tersedia', 'Maintenance'])->default('Tersedia');
            $table->timestamps();
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ruangans');
    }
};
