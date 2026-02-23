<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use App\Mail\ReminderBookingMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendBookingReminder extends Command
{
    // Ini adalah perintah yang akan kita ketik di terminal nanti
    protected $signature = 'app:send-reminder';
    protected $description = 'Kirim email pengingat H-1 peminjaman ruangan';

    public function handle()
    {
        // Cari tanggal besok
        $besok = Carbon::tomorrow()->toDateString();

        // Cari booking yang statusnya "Dikonfirmasi" DAN waktu_mulainya adalah besok
        $bookings = Booking::with('user', 'ruangan')
            ->where('status_booking', 'Dikonfirmasi')
            ->whereDate('waktu_mulai', $besok)
            ->get();

        if ($bookings->isEmpty()) {
            $this->info('Tidak ada jadwal ruangan untuk besok.');
            return;
        }

        // Jika ada, kirim email satu per satu
        foreach ($bookings as $booking) {
            Mail::to($booking->user->email)->send(new ReminderBookingMail($booking));
            $this->info('Email pengingat berhasil dikirim ke: ' . $booking->user->email);
        }

        $this->info('Semua email pengingat H-1 telah terkirim!');
    }
}
