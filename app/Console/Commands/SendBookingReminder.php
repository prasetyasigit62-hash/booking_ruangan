<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SendBookingReminder extends Command
{
    /**
     * Nama dan signature dari console command.
     *
     * @var string
     */
    protected $signature = 'booking:send-reminder';

    /**
     * Deskripsi dari console command.
     *
     * @var string
     */
    protected $description = 'Mengirimkan pesan WhatsApp pengingat H-1 kepada pemesan ruangan';

    /**
     * Jalankan console command.
     */
    public function handle()
    {
        // 1. Tentukan tanggal besok
        $besok = Carbon::tomorrow()->toDateString();

        $this->info("Mulai mencari jadwal booking untuk tanggal: {$besok}");

        // 2. Cari booking yang jadwal mulainya besok DAN statusnya sudah 'Dikonfirmasi'
        $bookings = Booking::with('user', 'ruangan') // Pastikan relasi model sudah ada
            ->whereDate('waktu_mulai', $besok)
            ->where('status_booking', 'Dikonfirmasi') // Hanya yang sudah ACC
            ->get();

        if ($bookings->isEmpty()) {
            $this->info('Tidak ada jadwal booking untuk besok yang perlu diingatkan.');
            return;
        }

        $count = 0;

        // 3. Looping setiap pemesan dan buat pesan pengingat
        foreach ($bookings as $booking) {

            // Format waktu agar lebih cantik
            $waktuMulai = Carbon::parse($booking->waktu_mulai)->format('H:i');
            $waktuSelesai = Carbon::parse($booking->waktu_selesai)->format('H:i');
            $tanggalIndo = Carbon::parse($booking->waktu_mulai)->translatedFormat('d F Y');
            $namaRuangan = $booking->ruangan ? $booking->ruangan->nama_ruangan : 'Ruangan ' . $booking->ruangan_id;
            $namaPeminjam = $booking->user ? $booking->user->name : 'Pelanggan';

            // 4. Susun Pesan Reminder (Bisa disesuaikan bahasanya)
            $pesan = "🔔 *PENGINGAT JADWAL RUANGAN* 🔔\n\n";
            $pesan .= "Halo *{$namaPeminjam}*, kami dari Admin Layanan Ruangan.\n\n";
            $pesan .= "Pesan ini adalah pengingat otomatis bahwa Anda memiliki jadwal penggunaan ruangan *BESOK*.\n\n";
            $pesan .= "📌 *Ruangan:* {$namaRuangan}\n";
            $pesan .= "🗓️ *Tanggal:* {$tanggalIndo}\n";
            $pesan .= "🕒 *Waktu:* {$waktuMulai} - {$waktuSelesai} WIB\n";
            $pesan .= "🎯 *Agenda:* {$booking->keperluan}\n";
            $pesan .= "🏷️ *KODE BOOKING:* {$booking->kode_booking}\n\n";
            $pesan .= "Mohon hadir tepat waktu dan jangan lupa menunjukkan *KODE BOOKING* Anda kepada petugas kami saat tiba di lokasi.\n\n";
            $pesan .= "Jika ada perubahan jadwal atau pembatalan, mohon segera hubungi kami melalui nomor ini.\n\n";
            $pesan .= "Terima kasih dan sampai jumpa besok! 👋\n";
            $pesan .= "*Tim Admin Layanan Ruangan*";

            // 5. Eksekusi Pengiriman Pesan (Gunakan API WhatsApp Anda di sini)
            // KARENA INI OTOMATIS DI BELAKANG LAYAR, KITA TIDAK BISA PAKAI wa.me (karena wa.me butuh browser user)
            // Anda harus menggunakan layanan API (seperti Fonnte, WABlas, Twilio, dll).
            // Contoh menggunakan Fonnte (Jika Anda punya):

            /* $this->kirimPesanFonnte($booking->no_hp, $pesan); 
            */

            // Untuk sementara, kita catat (Log) saja dulu untuk melihat apakah sistemnya jalan
            Log::info("Reminder WA disiapkan untuk: {$booking->no_hp} - {$namaPeminjam}");
            $this->info("Pesan disiapkan untuk {$namaPeminjam} ({$booking->no_hp})");

            $count++;
        }

        $this->info("Selesai! Telah menyiapkan pengingat untuk {$count} pemesan.");
    }

    /**
     * Contoh fungsi jika Anda menggunakan Fonnte API (Opsional)
     */
    private function kirimPesanFonnte($target, $pesan)
    {
        /*
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://api.fonnte.com/send',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => array(
            'target' => $target,
            'message' => $pesan,
            'countryCode' => '62',
          ),
          CURLOPT_HTTPHEADER => array(
            'Authorization: TOKEN_API_ANDA_DI_SINI' // Ganti dengan Token Fonnte Anda
          ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        */
    }
}
