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
        Schema::table('bookings', function (Blueprint $table) {
            // Tambahkan baris ini untuk membuat kolom harga
            $table->bigInteger('total_harga')->default(0)->after('keperluan');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Tambahkan baris ini untuk menghapus kolom jika rollback
            $table->dropColumn('total_harga');
        });
    }
};
