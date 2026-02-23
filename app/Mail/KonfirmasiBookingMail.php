<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Booking;

class KonfirmasiBookingMail extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    public function build()
    {
        return $this->subject('Hore! Booking Ruangan Dikonfirmasi - ' . $this->booking->kode_booking)
            ->view('emails.konfirmasi'); // Kita akan buat file view ini di langkah berikutnya
    }
}
