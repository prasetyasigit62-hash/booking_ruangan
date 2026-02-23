@extends('layouts.app')

@push('styles')
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet">
    <style>
        /* 1. Modifikasi Tabel Utama */
        .table-modern {
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
        }

        .table-modern thead th {
            background-color: #f8f9fc;
            color: #6c757d;
            font-weight: 700;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            border: none;
            padding: 15px 20px;
        }

        .table-modern thead th:first-child {
            border-top-left-radius: 12px;
            border-bottom-left-radius: 12px;
        }

        .table-modern thead th:last-child {
            border-top-right-radius: 12px;
            border-bottom-right-radius: 12px;
        }

        .table-modern tbody td {
            padding: 16px 20px;
            vertical-align: middle;
            border-bottom: 1px solid #edf2f9;
            color: #4b5563;
            font-size: 0.9rem;
            transition: background-color 0.2s ease;
        }

        .table-modern tbody tr:hover td {
            background-color: #f8fafc;
        }

        /* Efek hover baris yang sangat lembut */
        .table-modern tbody tr:last-child td {
            border-bottom: none;
        }

        /* 2. Modifikasi Tombol Aksi (Bulat Melayang) */
        .btn-action {
            width: 36px;
            height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            border: none;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .btn-action-info {
            background-color: rgba(13, 202, 240, 0.1);
            color: #0dcaf0;
        }

        .btn-action-info:hover {
            background-color: #0dcaf0;
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 4px 10px rgba(13, 202, 240, 0.3);
        }

        .btn-action-success {
            background-color: rgba(25, 135, 84, 0.1);
            color: #198754;
        }

        .btn-action-success:hover {
            background-color: #198754;
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 4px 10px rgba(25, 135, 84, 0.3);
        }

        /* 3. Merombak Bawaan DataTables (Search & Pagination) */
        .dataTables_wrapper .dataTables_length select {
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            padding: 4px 30px 4px 12px !important;
            /* Tambahan jarak 30px di kanan agar tidak menabrak panah */
            outline: none;
            width: auto;
            display: inline-block;
        }

        .dataTables_wrapper .dataTables_filter input:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.15);
        }

        .dataTables_wrapper .dataTables_length select {
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            padding: 4px 10px;
            outline: none;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            border-radius: 50% !important;
            margin: 0 4px;
            border: none !important;
            background: transparent !important;
            transition: all 0.2s;
            font-weight: 500;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: #0d6efd !important;
            color: white !important;
            box-shadow: 0 4px 10px rgba(13, 110, 253, 0.3);
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover:not(.current) {
            background: #f1f5f9 !important;
            color: #0d6efd !important;
        }

        /* 2. Style KHUSUS untuk menu yang SEDANG DIAKSES (Aktif) */
        .nav-item .nav-link.active {
            background: linear-gradient(135deg, #0d6efd, #0dcaf0);
            color: #ffffff !important;
            box-shadow: 0 4px 10px rgba(13, 110, 253, 0.2);
            transform: translateY(0);
            /* Sengaja dikunci agar tidak melayang */
        }

        .menu-nav-btn {
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1) !important;
        }

        .menu-nav-btn {
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1) !important;
        }

        /* Saat tombol disentuh (Hover) */
        .menu-nav-btn:hover {
            transform: translateY(-4px) !important;
            /* Melompat naik 4px */
        }

        /* Khusus tombol abu-abu/putih */
        .btn-light.menu-nav-btn:hover {
            background-color: #ffffff !important;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.12) !important;
            color: #0d6efd !important;
            /* Teks jadi biru menyala */
        }

        /* Khusus tombol utama (Biru) */
        .btn-primary.menu-nav-btn:hover {
            box-shadow: 0 6px 15px rgba(13, 110, 253, 0.4) !important;
        }
    </style>
@endpush

@section('content')
    <div class="d-flex flex-wrap gap-3 mb-4">
        <a href="{{ route('booking.index') }}"
            class="btn btn-light rounded-pill px-4 py-2 fw-bold shadow-sm menu-nav-btn text-secondary">
            <i class="fas fa-calendar-alt me-2"></i> Kalender Ruangan
        </a>

        <a href="{{ route('booking.monitoring') }}"
            class="btn btn-primary rounded-pill px-4 py-2 fw-bold shadow-sm menu-nav-btn">
            <i class="fas fa-desktop me-2"></i> Monitoring Real-Time
        </a>

        <a href="{{ route('booking.checkin') }}"
            class="btn btn-light rounded-pill px-4 py-2 fw-bold shadow-sm menu-nav-btn text-secondary">
            <i class="fas fa-sign-in-alt me-2"></i> Check-In Ruangan
        </a>
    </div>

    <div class="card shadow-sm border-0 mb-4" style="border-radius: 15px;">
        <div
            class="card-body p-3 p-md-4 d-flex flex-column flex-xl-row align-items-xl-center justify-content-between gap-4">

            <div class="d-flex align-items-center gap-3">
                <div
                    class="bg-primary bg-opacity-10 p-3 rounded-circle d-none d-sm-flex align-items-center justify-content-center">
                    <i class="fas fa-desktop text-primary fs-4"></i>
                </div>
                <div>
                    <h4 class="fw-bold mb-2 text-dark">Monitoring Real-Time</h4>
                </div>
            </div>

            <div class="bg-light p-2 rounded-pill border d-inline-block w-100 w-xl-auto shadow-sm">
                <form action="{{ route('booking.export') }}" method="GET"
                    class="d-flex flex-column flex-sm-row align-items-center gap-2 m-0">

                    <div class="d-flex align-items-center gap-2 bg-white rounded-pill px-3 py-1">
                        <input type="date" name="start_date"
                            class="form-control form-control-sm border-0 shadow-none text-center fw-medium text-secondary"
                            required style="background: transparent;">
                        <span class="text-muted fw-bold small">s/d</span>
                        <input type="date" name="end_date"
                            class="form-control form-control-sm border-0 shadow-none text-center fw-medium text-secondary"
                            required style="background: transparent;">
                    </div>

                    <div class="d-none d-sm-block border-start h-100 mx-1 py-3"></div>

                    <div class="d-flex gap-2 w-100 justify-content-center mt-2 mt-sm-0 px-2 px-sm-0">
                        <button type="submit" name="format" value="excel"
                            class="btn btn-success btn-sm rounded-pill px-4 fw-bold shadow-sm flex-fill flex-sm-grow-0 transition-all hover-translate-y menu-nav-btn">
                            <i class="fas fa-file-excel me-1"></i> Excel
                        </button>
                        <button type="submit" name="format" value="pdf"
                            class="btn btn-danger btn-sm rounded-pill px-4 fw-bold shadow-sm flex-fill flex-sm-grow-0 transition-all hover-translate-y menu-nav-btn">
                            <i class="fas fa-file-pdf me-1"></i> PDF
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="table-responsive border-0 shadow-sm rounded-4 bg-white p-3">
        <table id="tableMonitoring" class="table table-modern align-middle w-100">
            <thead>
                <tr>
                    <th class="text-center">No</th>
                    <th>Kode Booking</th>
                    <th>Peminjam</th>
                    <th>Ruangan</th>
                    <th>Waktu Mulai</th>
                    <th>Total Bayar</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="border-top-0">
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="modalDetail" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow" style="border-radius: 15px;">
                <div class="modal-header bg-info text-white" style="border-radius: 15px 15px 0 0;">
                    <h5 class="modal-title fw-bold"><i class="fas fa-file-invoice me-2"></i> Detail Peminjaman</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td width="40%" class="text-secondary">Kode Booking</td>
                            <td width="5%">:</td>
                            <th id="dtl_kode"></th>
                        </tr>
                        <tr>
                            <td class="text-secondary">Peminjam</td>
                            <td>:</td>
                            <th id="dtl_peminjam"></th>
                        </tr>
                        <tr>
                            <td class="text-secondary">Ruangan</td>
                            <td>:</td>
                            <th id="dtl_ruangan"></th>
                        </tr>
                        <tr>
                            <td class="text-secondary">Kegiatan</td>
                            <td>:</td>
                            <th id="dtl_kegiatan"></th>
                        </tr>
                        <tr>
                            <td class="text-secondary">Waktu Mulai</td>
                            <td>:</td>
                            <th id="dtl_mulai"></th>
                        </tr>
                        <tr>
                            <td class="text-secondary">Waktu Selesai</td>
                            <td>:</td>
                            <th id="dtl_selesai"></th>
                        </tr>
                        <tr>
                            <td class="text-secondary">Total Tagihan</td>
                            <td>:</td>
                            <th id="dtl_tagihan" class="text-primary fs-5"></th>
                        </tr>
                        <tr>
                            <td class="text-secondary">Status</td>
                            <td>:</td>
                            <th id="dtl_status"></th>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer bg-light" style="border-radius: 0 0 15px 15px;">
                    <a href="#" id="btnCetakNota" class="btn btn-danger rounded-pill px-4 shadow-sm"
                        style="display: none;"><i class="fas fa-file-pdf me-1"></i> Cetak PDF</a>
                    <button type="button" class="btn btn-secondary rounded-pill px-4"
                        data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#tableMonitoring').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('booking.monitoring') }}', // Pastikan route ini sesuai dengan milik Anda
                // Matikan garis vertikal bawaan datatables jika ada
                bLengthChange: true,
                language: {
                    search: "",
                    searchPlaceholder: "🔍 Cari data...",
                    processing: '<i class="fas fa-spinner fa-spin fa-2x text-primary"></i>'
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'text-center fw-bold text-muted'
                    },
                    {
                        data: 'kode_booking',
                        name: 'kode_booking',
                        className: 'fw-bold text-primary'
                    },
                    {
                        data: 'user.name',
                        name: 'user.name',
                        defaultContent: '-'
                    },
                    {
                        data: 'ruangan.nama_ruangan',
                        name: 'ruangan.nama_ruangan',
                        defaultContent: '-'
                    },
                    {
                        data: 'waktu_mulai',
                        name: 'waktu_mulai'
                    },
                    {
                        data: 'total_bayar',
                        name: 'total_bayar',
                        className: 'fw-bold'
                    },

                    // 👇 INI DIA KUNCI UTAMANYA! Kita panggil status_badge dari PHP 👇
                    // (Pastikan tidak ada perintah "render: function..." sama sekali di baris ini)
                    {
                        data: 'status_badge',
                        name: 'status_booking',
                        orderable: false,
                        searchable: true,
                        className: 'text-center'
                    },

                    // 👇 Kita panggil aksi dari PHP 👇
                    {
                        data: 'aksi',
                        name: 'aksi',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    }
                ]
            });
        });
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Template Fungsi Tombol (Akan kita buat di langkah berikutnya)
        // Fungsi Menampilkan Detail
        function detailBooking(id) {
            $.get('/booking/' + id + '/detail', function(data) {
                $('#dtl_kode').text(data.kode_booking);
                $('#dtl_peminjam').text(data.user.name);
                $('#dtl_ruangan').text(data.ruangan.nama_ruangan);
                $('#dtl_kegiatan').text(data.keperluan);
                $('#dtl_mulai').text(data.waktu_mulai_format);
                $('#dtl_selesai').text(data.waktu_selesai_format);
                $('#dtl_tagihan').text(data.harga_format);

                let statusBadge = '';
                if (data.status_booking === 'Selesai') {
                    statusBadge =
                        '<span class="badge bg-primary px-3 py-1"><i class="fas fa-flag-checkered me-1"></i> Selesai</span>';
                } else if (data.status_booking === 'Dikonfirmasi') {
                    statusBadge =
                        '<span class="badge bg-success px-3 py-1"><i class="fas fa-check-circle me-1"></i> Dikonfirmasi</span>';
                } else if (data.status_booking === 'Dibatalkan' || data.status_booking === 'Ditolak') {
                    statusBadge = '<span class="badge bg-danger px-3 py-1">' + data.status_booking + '</span>';
                } else {
                    statusBadge =
                        '<span class="badge bg-warning text-dark px-3 py-1"><i class="fas fa-clock me-1"></i> ' + (
                            data.status_booking ? data.status_booking : 'Pending') + '</span>';
                }

                $('#dtl_status').html(statusBadge);

                if (data.status_booking == 'Dikonfirmasi') {
                    $('#btnCetakNota').attr('href', '/booking/' + id + '/pdf').show();
                } else {
                    $('#btnCetakNota').hide();
                }

                $('#modalDetail').modal('show');
            });
        }

        // Fungsi Menyetujui Booking
        function konfirmasiBooking(id) {
            Swal.fire({
                title: 'Setujui Peminjaman?',
                text: "Status akan diubah menjadi Dikonfirmasi dan tagihan dianggap lunas.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Setujui!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post('/booking/' + id + '/confirm', function(response) {
                        Swal.fire('Berhasil!', response.message, 'success');

                        // Refresh otomatis DataTables tanpa reload halaman!
                        $('#tableMonitoring').DataTable().ajax.reload();
                    });
                }
            });
        }
    </script>
@endpush
