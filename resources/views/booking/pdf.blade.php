<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Peminjaman Ruangan</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #0d6efd;
            padding-bottom: 10px;
        }

        .header h2 {
            margin: 0;
            color: #0d6efd;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .header p {
            margin: 5px 0 0;
            color: #555;
            font-style: italic;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 10px 8px;
            text-align: left;
        }

        th {
            background-color: #0d6efd;
            color: white;
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
            font-size: 11px;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .text-center {
            text-align: center;
        }

        .badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
            color: white;
        }

        .bg-success {
            background-color: #198754;
        }

        .bg-warning {
            background-color: #ffc107;
            color: #000;
        }

        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 11px;
            font-style: italic;
            color: #777;
        }
    </style>
</head>

<body>

    <div class="header">
        <h2>Laporan Peminjaman Ruangan</h2>

        @if ($start_date && $end_date)
            <p>Periode: {{ \Carbon\Carbon::parse($start_date)->format('d F Y') }} -
                {{ \Carbon\Carbon::parse($end_date)->format('d F Y') }}</p>
        @else
            <p>Periode: Semua Waktu</p>
        @endif

    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="12%">Kode</th>
                <th width="18%">Peminjam</th>
                <th width="15%">Ruangan</th>
                <th width="20%">Waktu Mulai</th>
                <th width="15%">Total (Rp)</th>
                <th width="15%">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bookings as $index => $b)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center fw-bold">{{ $b->kode_booking }}</td>
                    <td>{{ $b->user->name ?? '-' }}</td>
                    <td>{{ $b->ruangan->nama_ruangan ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($b->waktu_mulai)->format('d M Y, H:i') }}</td>
                    <td class="text-center">{{ number_format($b->total_bayar, 0, ',', '.') }}</td>
                    <td class="text-center">
                        <span class="badge {{ $b->status_booking == 'Dikonfirmasi' ? 'bg-success' : 'bg-warning' }}">
                            {{ $b->status_booking }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data peminjaman pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada: {{ \Carbon\Carbon::now()->format('d F Y, H:i') }} oleh Sistem SmartBooking
    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>

</body>

</html>
