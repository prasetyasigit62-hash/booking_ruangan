<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('ruangans', function (Blueprint $table) {
            // Mengecek dulu: Jika kolom 'foto' belum ada, maka buatkan!
            if (!Schema::hasColumn('ruangans', 'foto')) {
                $table->string('foto')->nullable();
            }

            // Jaga-jaga: Jika kolom 'fasilitas' juga ternyata belum ada, buatkan sekalian
            if (!Schema::hasColumn('ruangans', 'fasilitas')) {
                $table->text('fasilitas')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('ruangans', function (Blueprint $table) {
            // Hapus kolom jika migrasi dibatalkan
            $table->dropColumn(['foto', 'fasilitas']);
        });
    }
};
