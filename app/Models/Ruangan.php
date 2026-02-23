<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ruangan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_ruangan',
        'kapasitas',
        'fasilitas',
        'foto',
        'harga_5_jam',    // Tambahan baru
        'harga_1_hari',   // Tambahan baru
        'harga_3_hari',   // Tambahan baru
        'harga_1_minggu'  // Tambahan baru
    ];

    // Relasi: Satu Ruangan bisa memiliki banyak Booking
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
