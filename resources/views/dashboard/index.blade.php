<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - SmartBooking</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f7f6;
            color: #333;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
        }

        .icon-box {
            width: 45px;
            height: 45px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .bg-soft-primary {
            background-color: rgba(13, 110, 253, 0.1);
            color: #0d6efd;
        }

        .bg-soft-success {
            background-color: rgba(25, 135, 84, 0.1);
            color: #198754;
        }

        .bg-soft-warning {
            background-color: rgba(255, 193, 7, 0.1);
            color: #ffc107;
        }

        .bg-soft-info {
            background-color: rgba(13, 202, 240, 0.1);
            color: #0dcaf0;
        }

        /* Tabel Lebih Kompak & Interaktif */
        .table-custom th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #555;
            border-bottom: 2px solid #eee;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .table-custom td {
            vertical-align: middle;
            color: #444;
            font-size: 0.9rem;
        }

        .table-custom tbody tr {
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .table-custom tbody tr:hover {
            background-color: #f0f7ff;
            transform: scale(1.01) translateX(4px);
            box-shadow: 0 2px 8px rgba(13, 110, 253, 0.1);
            position: relative;
            z-index: 2;
            border-radius: 8px;
        }

        /* Styling Navbar Modern */
        .navbar-custom {
            background-color: #ffffff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
            padding: 10px 0;
        }

        .navbar-brand {
            font-weight: 700;
            color: #333 !important;
            font-size: 1.3rem;
        }

        .logo-icon {
            width: 34px;
            height: 34px;
            background: linear-gradient(135deg, #0d6efd, #0dcaf0);
            font-size: 16px;
        }

        .nav-item .nav-link {
            font-weight: 500;
            color: #666;
            padding: 8px 16px !important;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-size: 0.95rem;
        }

        .nav-item .nav-link:hover,
        .nav-item .nav-link.active {
            color: #0d6efd;
            background-color: rgba(13, 110, 253, 0.08);
            transform: translateY(-2px);
        }

        /* Tombol Dashboard */
        .btn-dashboard {
            background: linear-gradient(135deg, #0d6efd, #0dcaf0);
            color: white !important;
            box-shadow: 0 4px 10px rgba(13, 110, 253, 0.2);
            transition: transform 0.3s ease;
        }

        .btn-dashboard:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(13, 110, 253, 0.4);
        }

        /* Tombol Logout Merah Premium */
        .btn-logout {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white !important;
            box-shadow: 0 4px 10px rgba(239, 68, 68, 0.2);
            transition: transform 0.3s ease;
        }

        .btn-logout:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(239, 68, 68, 0.4);
        }

        /* KUNCI WADAH GRAFIK MINIMALIS */
        .chart-container-modern {
            position: relative;
            height: 220px !important;
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .status-badge {
            font-weight: 600;
            padding: 0.4rem 0.8rem;
            font-size: 0.8rem;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-custom sticky-top mb-3">
        <div class="container-fluid px-4">
            <a class="navbar-brand d-flex align-items-center gap-2" href="/">
                <div class="logo-icon text-white rounded p-2 d-flex justify-content-center align-items-center">
                    <i class="fas fa-building"></i>
                </div>
                SmartBooking
            </a>
            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center gap-2">
                    <li class="nav-item"><a class="nav-link" href="/"><i class="fas fa-calendar-alt me-1"></i>
                            Kalender</a></li>
                    <li class="nav-item"><a class="nav-link" href="/monitoring"><i class="fas fa-desktop me-1"></i>
                            Monitoring</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('booking.checkin') ? 'active' : '' }}"
                            href="{{ route('booking.checkin') }}"><i class="fas fa-qrcode me-1"></i> Check-In</a></li>
                    <li class="nav-item ms-lg-2"><a class="nav-link btn-dashboard rounded-pill px-4 py-2"
                            href="/dashboard"><i class="fas fa-chart-pie me-1"></i> Dashboard</a></li>

                    <li class="nav-item ms-lg-2 d-flex align-items-center">
                        <form action="{{ route('logout') }}" method="POST" class="m-0">
                            @csrf
                            <button type="submit" class="nav-link btn-logout rounded-pill px-4 py-2 border-0 fw-bold"
                                style="font-size: 0.95rem;">
                                <i class="fas fa-sign-out-alt me-1"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid py-2 px-4">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold mb-0 text-dark"><i class="fas fa-chart-pie text-primary me-2"></i> Ringkasan Statistik
            </h4>
            <div><span class="text-muted small fw-bold"><i class="fas fa-calendar-alt me-1"></i>
                    {{ \Carbon\Carbon::now()->format('d F Y') }}</span></div>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-xl-3 col-sm-6">
                <div class="card h-100 p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 fs-6 fw-500">Total Booking</p>
                            <h3 class="fw-bold mb-0 text-dark">{{ $totalBooking }}</h3>
                        </div>
                        <div class="icon-box bg-soft-primary"><i class="fas fa-clipboard-list"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6">
                <div class="card h-100 p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 fs-6 fw-500">Menunggu (Pending)</p>
                            <h3 class="fw-bold mb-0 text-dark">{{ $bookingPending }}</h3>
                        </div>
                        <div class="icon-box bg-soft-warning"><i class="fas fa-clock"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6">
                <div class="card h-100 p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 fs-6 fw-500">Selesai / Check-In</p>
                            <h3 class="fw-bold mb-0 text-dark">{{ $selesai }}</h3>
                        </div>
                        <div class="icon-box bg-soft-info"><i class="fas fa-door-open"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6">
                <div class="card h-100 p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 fs-6 fw-500">Total Pendapatan</p>
                            <h4 class="fw-bold mb-0 text-success">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                            </h4>
                        </div>
                        <div class="icon-box bg-soft-success"><i class="fas fa-wallet"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-lg-8">
                <div class="card h-100 p-3">
                    <h6 class="fw-bold mb-2 text-secondary"><i class="fas fa-chart-bar me-1"></i> Popularitas Ruangan
                    </h6>
                    <div class="chart-container-modern">
                        <canvas id="barChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card h-100 p-3">
                    <h6 class="fw-bold mb-2 text-secondary"><i class="fas fa-chart-pie me-1"></i> Rasio Status
                        Peminjaman</h6>
                    <div class="chart-container-modern">
                        <canvas id="doughnutChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card p-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold mb-0 text-dark"><i class="fas fa-list me-1"></i> 5 Transaksi Terbaru</h6>
                        <a href="/monitoring" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-bold">Lihat
                            Semua</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm table-custom table-hover mb-0">
                            <thead>
                                <tr>
                                    <th class="py-2">Kode</th>
                                    <th class="py-2">Peminjam</th>
                                    <th class="py-2">Ruangan</th>
                                    <th class="py-2">Pelaksanaan</th>
                                    <th class="py-2 text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($bookingTerbaru as $b)
                                    <tr>
                                        <td class="fw-bold text-primary py-2">{{ $b->kode_booking }}</td>
                                        <td class="fw-bold py-2">{{ $b->user->name }}</td>
                                        <td class="py-2">{{ $b->ruangan->nama_ruangan }}</td>
                                        <td class="py-2"><i class="far fa-calendar-alt text-muted me-1"></i>
                                            {{ \Carbon\Carbon::parse($b->waktu_mulai)->format('d M Y, H:i') }}</td>
                                        <td class="text-center py-2">
                                            @if ($b->status_booking == 'Pending')
                                                <span class="badge bg-warning text-dark rounded-pill status-badge"><i
                                                        class="fas fa-clock me-1"></i> Pending</span>
                                            @elseif ($b->status_booking == 'Dikonfirmasi')
                                                <span class="badge bg-success rounded-pill status-badge"><i
                                                        class="fas fa-check-circle me-1"></i> Dikonfirmasi</span>
                                            @elseif ($b->status_booking == 'Selesai' || $b->status_booking == 'Digunakan')
                                                <span class="badge bg-primary rounded-pill status-badge"><i
                                                        class="fas fa-flag-checkered me-1"></i> Selesai</span>
                                            @elseif ($b->status_booking == 'Dibatalkan')
                                                <span class="badge bg-danger rounded-pill status-badge"><i
                                                        class="fas fa-times-circle me-1"></i> Dibatalkan</span>
                                            @else
                                                <span
                                                    class="badge bg-secondary rounded-pill status-badge">{{ $b->status_booking }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted fw-bold"><i
                                                class="fas fa-inbox fs-4 mb-2 d-block"></i> Belum ada transaksi</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        // 1. Inisialisasi Bar Chart (Grafik Batang)
        const ctxBar = document.getElementById('barChart').getContext('2d');
        new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: {!! json_encode($namaRuangan) !!},
                datasets: [{
                    label: 'Jumlah Dipinjam',
                    data: {!! json_encode($jumlahBooking) !!},
                    backgroundColor: 'rgba(13, 110, 253, 0.85)',
                    borderRadius: 6,
                    barPercentage: 0.5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false, // <-- KUNCI ANTI MELAR
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grace: 1,
                        grid: {
                            borderDash: [5, 5]
                        },
                        ticks: {
                            stepSize: 1
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // 2. Inisialisasi Doughnut Chart (Grafik Donat)
        const ctxDoughnut = document.getElementById('doughnutChart').getContext('2d');
        new Chart(ctxDoughnut, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($labelStatus) !!},
                datasets: [{
                    data: {!! json_encode($dataStatus) !!},
                    backgroundColor: [
                        'rgba(255, 193, 7, 0.85)', // Kuning (Pending)
                        'rgba(25, 135, 84, 0.85)', // Hijau (Dikonfirmasi)
                        'rgba(13, 110, 253, 0.85)' // Biru (Selesai / Check-In)
                    ],
                    borderWidth: 2,
                    borderColor: '#ffffff',
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false, // <-- KUNCI ANTI MELAR
                cutout: '65%',
                plugins: {
                    legend: {
                        position: 'right', // Dipindah ke kanan agar lebih proporsional
                        labels: {
                            usePointStyle: true,
                            padding: 15,
                            font: {
                                size: 11
                            }
                        }
                    }
                }
            }
        });
    </script>

</body>

</html>
