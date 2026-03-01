<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RuanganController;

// ==========================================
// 1. ZONA GUEST (Hanya bisa diakses jika BELUM login)
// ==========================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});


// ==========================================
// 2. ZONA TERPROTEKSI (WAJIB LOGIN)
// ==========================================
Route::middleware('auth')->group(function () {

    // --- Logout ---
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // --- Halaman Utama (Sekarang Terkunci) ---
    // Inilah yang membuat user akan dilempar ke login jika belum masuk
    Route::get('/', [BookingController::class, 'index'])->name('booking.index');
    Route::get('/booking/events', [BookingController::class, 'getEvents'])->name('booking.events');
    Route::post('/booking/store', [BookingController::class, 'store'])->name('booking.store');

    // --- Dashboard & Profil ---
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [UserController::class, 'profile'])->name('profile.index');
    Route::post('/profile/update', [UserController::class, 'updateProfile'])->name('profile.update');

    // --- Manajemen User ---
    Route::resource('users', UserController::class);

    // --- Fitur Monitoring & Operasional ---
    Route::get('/monitoring', [BookingController::class, 'monitoring'])->name('booking.monitoring');
    Route::get('/monitoring/export', [BookingController::class, 'export'])->name('booking.export');
    Route::get('/booking/{id}/detail', [BookingController::class, 'show'])->name('booking.show');
    Route::post('/booking/{id}/confirm', [BookingController::class, 'confirm'])->name('booking.confirm');
    Route::get('/booking/{id}/pdf', [BookingController::class, 'cetakPDF'])->name('booking.pdf');

    // --- Fitur Check-In (AJAX) ---
    Route::get('/check-in', [BookingController::class, 'checkinIndex'])->name('booking.checkin');
    Route::get('/check-in/search', [BookingController::class, 'searchBooking'])->name('booking.checkin.search');
    Route::post('/check-in/proses', [BookingController::class, 'checkinProses'])->name('booking.checkin.proses');

    // --- Master Data Ruangan ---
    Route::get('/ruangan', [RuanganController::class, 'index'])->name('ruangan.index');
    Route::post('/ruangan', [RuanganController::class, 'store'])->name('ruangan.store');
    Route::put('/ruangan/{id}', [RuanganController::class, 'update'])->name('ruangan.update');
    Route::delete('/ruangan/{id}', [RuanganController::class, 'destroy'])->name('ruangan.destroy');
    Route::get('/get-harga-ruangan/{id}', [RuanganController::class, 'getHarga']);

    // --- Fitur Reminder ---
    Route::post('/admin/booking/send-reminder-manual', [BookingController::class, 'sendReminderManual'])->name('admin.booking.reminder');
    Route::get('/booking/remind/{id}', [BookingController::class, 'remindSingle'])->name('admin.booking.remind.single');
});
