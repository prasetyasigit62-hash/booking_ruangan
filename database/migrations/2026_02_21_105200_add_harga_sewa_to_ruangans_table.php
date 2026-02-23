<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('ruangans', function (Blueprint $table) {
            // Kita set nullable agar data ruangan lama Anda tidak error/crash
            if (!Schema::hasColumn('ruangans', 'harga_5_jam')) {
                $table->bigInteger('harga_5_jam')->nullable()->default(0);
                $table->bigInteger('harga_1_hari')->nullable()->default(0);
                $table->bigInteger('harga_3_hari')->nullable()->default(0);
                $table->bigInteger('harga_1_minggu')->nullable()->default(0);
            }
        });
    }

    public function down()
    {
        Schema::table('ruangans', function (Blueprint $table) {
            $table->dropColumn(['harga_5_jam', 'harga_1_hari', 'harga_3_hari', 'harga_1_minggu']);
        });
    }
};
