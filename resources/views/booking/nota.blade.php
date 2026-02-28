<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Nota Booking Ruangan</title>
    <style>
        body {
            font-family: sans-serif;
            color: #333;
            line-height: 1.5;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #0d6efd;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .title {
            font-size: 24px;
            font-weight: bold;
            color: #0d6efd;
            margin: 0;
        }

        .subtitle {
            font-size: 14px;
            color: #666;
        }

        .table-data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .table-data th,
        .table-data td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .table-data th {
            background-color: #f4f7f6;
            width: 30%;
        }

        .footer {
            text-align: right;
            margin-top: 50px;
        }

        .signature-line {
            border-top: 1px solid #333;
            display: inline-block;
            width: 200px;
            margin-top: 50px;
            padding-top: 5px;
            text-align: center;
        }
    </style>
</head>

<body>

    <div class="header">
        <h1 class="title">SMART BOOKING</h1>
        <div class="subtitle">Sistem Peminjaman Ruangan Terintegrasi</div>
        <p style="margin: 5px 0;">Jl. Teknologi No. 123, Kota Digital | Telp: (021) 123456</p>
    </div>

    <h3 style="text-align: center;">INVOICE PEMINJAMAN RUANGAN</h3>

    <p><strong>Kode Booking:</strong> {{ $booking->kode_booking }} <br>
        <strong>Tanggal Cetak:</strong> {{ $booking->tanggal_cetak }}
    </p>

    <table class="table-data">
        <tr>
            <th>Peminjam / Instansi</th>
            <td>{{ $booking->user->name }}</td>
        </tr>
        <tr>
            <th>Ruangan Digunakan</th>
            <td><strong>{{ $booking->ruangan->nama_ruangan }}</strong> (Kapasitas: {{ $booking->ruangan->kapasitas }}
                org)</td>
        </tr>
        <tr>
            <th>Agenda / Kegiatan</th>
            <td>{{ $booking->keperluan }}</td>
        </tr>
        <tr>
            <th>Jadwal Pelaksanaan</th>
            <td>
                Mulai: {{ \Carbon\Carbon::parse($booking->waktu_mulai)->format('d/m/Y H:i') }} <br>
                Selesai: {{ \Carbon\Carbon::parse($booking->waktu_selesai)->format('d/m/Y H:i') }}
            </td>
        </tr>
        <tr>
            <td class="text-secondary">Status Pembayaran</td>
            <td>
                @if ($booking->status_booking == 'Dikonfirmasi' || $booking->status_booking == 'Selesai')
                    <span style="color: #198754; font-weight: bold;">LUNAS</span>
                @else
                    <span style="color: #dc3545; font-weight: bold;">BELUM BAYAR</span>
                @endif
            </td>
        </tr>
        <tr>
            <th>Total Harga Sewa</th>
            <td style="font-size: 18px; font-weight: bold;">Rp {{ number_format($booking->total_bayar, 0, ',', '.') }}
            </td>
        </tr>
    </table>

    <div class="footer">
        <p>Disetujui Oleh, <br> Admin Pengelola Gedung</p>
        <div class="signature-line">Smart Booking System</div>
    </div>

</body>

</html>
