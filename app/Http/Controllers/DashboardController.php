<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ruangan;
use App\Models\Booking;

class DashboardController extends Controller
{
   public function index()
    {
        // 1. Data untuk Kartu Ringkasan Atas
        $totalBooking = Booking::count();
        $bookingPending = Booking::where('status_booking', 'Pending')->count();
        $bookingSukses = Booking::where('status_booking', 'Dikonfirmasi')->count();
        
        // 👇 Data Baru untuk Total Check-In (Selesai)
        $selesai = Booking::where('status_booking', 'Selesai')->count();

        // Total ruangan yang terdaftar di sistem
        $totalRuangan = Ruangan::count();

        // Total uang dari booking yang sudah Dikonfirmasi ATAU Selesai (Digabung)
        $totalPendapatan = Booking::whereIn('status_booking', ['Dikonfirmasi', 'Selesai'])->sum('total_bayar');

        // 2. Data Grafik Batang (Popularitas Ruangan)
        $ruangan = Ruangan::withCount('bookings')->get();
        $namaRuangan = $ruangan->pluck('nama_ruangan');
        $jumlahBooking = $ruangan->pluck('bookings_count');

        // 3. Data Grafik Donat (Rasio Status)
        $labelStatus = ['Menunggu Konfirmasi', 'Dikonfirmasi', 'Selesai (Digunakan)'];
        // 👇 Menggunakan nama variabel asli Anda agar tidak error
        $dataStatus = [$bookingPending, $bookingSukses, $selesai];

        // 4. Data Tabel Aktivitas Terbaru (Ambil 5 transaksi terakhir)
        $bookingTerbaru = Booking::with(['ruangan', 'user'])->latest()->take(5)->get();

        return view('dashboard.index', compact(
            'totalBooking',
            'bookingPending',
            'bookingSukses',
            'selesai',         // 👈 Wajib ditambahkan ke sini agar tampil di Blade
            'totalRuangan',
            'totalPendapatan',
            'namaRuangan',
            'jumlahBooking',
            'dataStatus',
            'labelStatus',
            'bookingTerbaru'
        ));
    } 
}
