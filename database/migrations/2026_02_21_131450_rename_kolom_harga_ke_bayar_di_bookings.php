<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        //Schema::table('bookings', function (Blueprint $table) {
            // Mengubah nama kolom kembali menjadi total_bayar
           // $table->renameColumn('total_harga', 'total_bayar');
        //});
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->renameColumn('total_bayar', 'total_harga');
        });
    }
};
