<!DOCTYPE html>
<html>

<head>
    <title>Booking Dikonfirmasi</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #f4f7f6; padding: 20px;">
    <div
        style="max-width: 600px; margin: 0 auto; background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <h2 style="color: #198754;">Peminjaman Ruangan Dikonfirmasi! 🎉</h2>
        <p>Halo, <strong>{{ $booking->user->name }}</strong>.</p>
        <p>Pengajuan peminjaman ruangan Anda telah disetujui oleh Admin. Berikut adalah rinciannya:</p>

        <table style="width: 100%; text-align: left; border-collapse: collapse; margin-top: 15px;">
            <tr>
                <th style="padding: 8px; border-bottom: 1px solid #ddd;">Kode Booking</th>
                <td style="padding: 8px; border-bottom: 1px solid #ddd;"><strong>{{ $booking->kode_booking }}</strong>
                </td>
            </tr>
            <tr>
                <th style="padding: 8px; border-bottom: 1px solid #ddd;">Ruangan</th>
                <td style="padding: 8px; border-bottom: 1px solid #ddd;">{{ $booking->ruangan->nama_ruangan }}</td>
            </tr>
            <tr>
                <th style="padding: 8px; border-bottom: 1px solid #ddd;">Waktu Mulai</th>
                <td style="padding: 8px; border-bottom: 1px solid #ddd;">
                    {{ \Carbon\Carbon::parse($booking->waktu_mulai)->format('d M Y, H:i') }}</td>
            </tr>
        </table>

        <p style="margin-top: 20px;">Harap datang tepat waktu. Jika ada pertanyaan, silakan hubungi pengelola gedung.
        </p>
        <p style="color: #888; font-size: 12px; margin-top: 30px;">Email ini dikirim secara otomatis oleh Sistem
            SmartBooking.</p>
    </div>
</body>

</html>
