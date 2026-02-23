<!DOCTYPE html>
<html>

<head>
    <title>Pengingat Booking Ruangan</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #f4f7f6; padding: 20px;">
    <div
        style="max-width: 600px; margin: 0 auto; background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <h2 style="color: #0d6efd;">⏰ Pengingat Jadwal Ruangan</h2>
        <p>Halo, <strong>{{ $booking->user->name }}</strong>.</p>
        <p>Ini adalah email pengingat otomatis bahwa Anda memiliki jadwal penggunaan ruangan <strong>besok</strong>:</p>

        <table
            style="width: 100%; text-align: left; border-collapse: collapse; margin-top: 15px; background-color: #f8f9fa;">
            <tr>
                <th style="padding: 10px; border-bottom: 1px solid #ddd;">Ruangan</th>
                <td style="padding: 10px; border-bottom: 1px solid #ddd;">
                    <strong>{{ $booking->ruangan->nama_ruangan }}</strong></td>
            </tr>
            <tr>
                <th style="padding: 10px; border-bottom: 1px solid #ddd;">Kegiatan</th>
                <td style="padding: 10px; border-bottom: 1px solid #ddd;">{{ $booking->keperluan }}</td>
            </tr>
            <tr>
                <th style="padding: 10px; border-bottom: 1px solid #ddd;">Waktu Mulai</th>
                <td style="padding: 10px; border-bottom: 1px solid #ddd; color: #dc3545;">
                    <strong>{{ \Carbon\Carbon::parse($booking->waktu_mulai)->format('d F Y, H:i') }}</strong></td>
            </tr>
        </table>

        <p style="margin-top: 20px;">Mohon pastikan Anda datang tepat waktu dan menjaga kebersihan ruangan setelah
            digunakan.</p>
        <p style="color: #888; font-size: 12px; margin-top: 30px;">Email ini dikirim secara otomatis oleh Sistem
            SmartBooking.</p>
    </div>
</body>

</html>
