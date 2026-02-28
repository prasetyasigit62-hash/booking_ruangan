<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use Yajra\DataTables\Facades\DataTables;

// ==========================================
// 1. ZONA OTENTIKASI (LOGIN & LOGOUT)
// ==========================================
Route::middleware('guest')->group(function () {
    // Hanya bisa diakses jika BELUM login
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Hanya bisa diakses jika SUDAH login
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');


// ==========================================
// 2. ZONA PUBLIK (PEGAWAI BEBAS AKSES)
// ==========================================
// Kalender dan Form Peminjaman Ruangan
Route::get('/', [BookingController::class, 'index'])->name('booking.index');
Route::get('/booking/events', [BookingController::class, 'getEvents'])->name('booking.events');
Route::post('/booking/store', [BookingController::class, 'store'])->name('booking.store');


// ==========================================
// 3. ZONA ADMIN (WAJIB LOGIN)
// ==========================================
Route::middleware('auth')->group(function () {

    // --- Rute Profil & Petugas ---
    Route::get('/profile', [UserController::class, 'profile'])->name('profile.index');
    Route::post('/profile/update', [UserController::class, 'updateProfile'])->name('profile.update');

    Route::resource('users', UserController::class);

    // Dashboard Admin
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Fitur Monitoring Real-Time
    Route::get('/monitoring', [BookingController::class, 'monitoring'])->name('booking.monitoring');
    Route::get('/monitoring/export', [BookingController::class, 'export'])->name('booking.export');
    Route::get('/booking/{id}/detail', [BookingController::class, 'show'])->name('booking.show');
    Route::post('/booking/{id}/confirm', [BookingController::class, 'confirm'])->name('booking.confirm');
    Route::get('/booking/{id}/pdf', [BookingController::class, 'cetakPDF'])->name('booking.pdf');

    // Fitur Check-In (AJAX)
    Route::get('/check-in', [BookingController::class, 'checkinIndex'])->name('booking.checkin');
    Route::get('/check-in/search', [BookingController::class, 'searchBooking'])->name('booking.checkin.search');
    Route::post('/check-in/proses', [BookingController::class, 'checkinProses'])->name('booking.checkin.proses');

    // 👇 TAMBAHKAN RUTE MASTER RUANGAN DI SINI 👇
    Route::get('/ruangan', [App\Http\Controllers\RuanganController::class, 'index'])->name('ruangan.index');
    Route::post('/ruangan', [App\Http\Controllers\RuanganController::class, 'store'])->name('ruangan.store');
    Route::put('/ruangan/{id}', [App\Http\Controllers\RuanganController::class, 'update'])->name('ruangan.update');
    Route::delete('/ruangan/{id}', [App\Http\Controllers\RuanganController::class, 'destroy'])->name('ruangan.destroy');

    Route::get('/get-harga-ruangan/{id}', [App\Http\Controllers\RuanganController::class, 'getHarga']);

    // Route untuk tombol kirim reminder manual
    Route::post('/admin/booking/send-reminder-manual', [BookingController::class, 'sendReminderManual'])->name('admin.booking.reminder');

    Route::get('/booking/remind/{id}', [App\Http\Controllers\BookingController::class, 'remindSingle'])->name('admin.booking.remind.single');
});
