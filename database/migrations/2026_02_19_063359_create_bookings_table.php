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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('kode_booking')->unique()->comment('Untuk kode di Nota PDF');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('ruangan_id')->constrained('ruangans')->onDelete('cascade');

            // Kolom wajib untuk FullCalendar.io
            $table->dateTime('waktu_mulai');
            $table->dateTime('waktu_selesai');

            $table->string('keperluan');
            $table->decimal('total_bayar', 15, 2)->default(0);

            // Status alur aplikasi
            $table->enum('status_booking', ['Pending', 'Dikonfirmasi', 'Selesai', 'Dibatalkan'])->default('Pending');
            $table->enum('status_pembayaran', ['Belum Bayar', 'Menunggu Verifikasi', 'Lunas'])->default('Belum Bayar');

            // Flag untuk fitur Email Reminder Otomatis
            $table->boolean('is_reminder_sent')->default(false)->comment('0 = Belum dikirim, 1 = Sudah dikirim');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
