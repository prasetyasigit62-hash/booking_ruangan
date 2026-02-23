<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Ruangan;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat 1 User Dummy
        $user = User::create([
            'name' => 'Admin Ruangan',
            'email' => 'admin@booking.com',
            'password' => bcrypt('password'),
        ]);

        // 2. Buat 2 Ruangan Dummy
        $ruang1 = Ruangan::create([
            'nama_ruangan' => 'Ruang Meeting Alpha',
            'kapasitas' => 20,
            'fasilitas' => 'AC, Proyektor, WiFi, Papan Tulis',
            'deskripsi' => 'Ruangan nyaman untuk rapat internal divisi.',
            'harga_per_jam' => 150000,
            'status' => 'Tersedia',
        ]);

        $ruang2 = Ruangan::create([
            'nama_ruangan' => 'Auditorium Utama',
            'kapasitas' => 100,
            'fasilitas' => 'AC Sentral, Sound System, Proyektor Layar Lebar, Podium',
            'deskripsi' => 'Cocok untuk seminar dan acara besar.',
            'harga_per_jam' => 500000,
            'status' => 'Tersedia',
        ]);

        // 3. Buat Data Booking Dummy (Jadwal Hari Ini)
        Booking::create([
            'kode_booking' => 'BKG-' . strtoupper(Str::random(6)),
            'user_id' => $user->id,
            'ruangan_id' => $ruang1->id,
            'waktu_mulai' => Carbon::now()->setHour(9)->setMinute(0),   // Jam 09:00 Hari ini
            'waktu_selesai' => Carbon::now()->setHour(12)->setMinute(0), // Jam 12:00 Hari ini
            'keperluan' => 'Rapat Evaluasi Bulanan',
            'total_bayar' => 450000, // 3 Jam x 150.000
            'status_booking' => 'Dikonfirmasi',
            'status_pembayaran' => 'Lunas',
        ]);
    }
}
