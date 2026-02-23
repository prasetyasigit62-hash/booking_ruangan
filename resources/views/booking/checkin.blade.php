@extends('layouts.app')

@section('content')
    <div class="d-flex flex-wrap gap-3 mb-4">
        <a href="{{ route('booking.index') }}"
            class="btn btn-light rounded-pill px-4 py-2 fw-bold shadow-sm menu-nav-btn text-secondary">
            <i class="fas fa-calendar-alt me-2"></i> Kalender Ruangan
        </a>

        <a href="{{ route('booking.monitoring') }}"
            class="btn btn-light rounded-pill px-4 py-2 fw-bold shadow-sm menu-nav-btn text-secondary">
            <i class="fas fa-desktop me-2"></i> Monitoring Real-Time
        </a>

        <a href="{{ route('booking.checkin') }}"
            class="btn btn-primary rounded-pill px-4 py-2 fw-bold shadow-sm menu-nav-btn">
            <i class="fas fa-sign-in-alt me-2"></i> Check-In Ruangan
        </a>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-10 mx-auto">

            <div id="alert-container"></div>

            <div class="card shadow-sm border-0 mb-4" style="border-radius: 15px;">
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                            style="width: 70px; height: 70px;">
                            <i class="fas fa-qrcode fa-2x"></i>
                        </div>
                        <h3 class="fw-bold text-dark">Check-In Ruangan</h3>
                        <p class="text-muted">Masukkan Kode Booking pelanggan untuk verifikasi pemakaian ruangan.</p>
                    </div>

                    <form id="formSearch" class="mb-4">
                        <div class="input-group input-group-lg shadow-sm rounded-pill overflow-hidden border">
                            <input type="text" id="inputKode"
                                class="form-control border-0 shadow-none text-center text-uppercase fw-bold text-primary"
                                placeholder="Contoh: BKG-XYZ123" required>
                            <button type="submit" id="btnSearch" class="btn btn-primary px-4 fw-bold transition-all">
                                <i class="fas fa-search me-1"></i> <span id="textSearch">Cari</span>
                            </button>
                        </div>
                    </form>

                    <div id="loading" class="text-center d-none py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="text-muted mt-2 mb-0">Mencari data...</p>
                    </div>

                    <div id="result-container" class="border rounded-4 p-4 bg-light shadow-sm d-none"
                        style="transition: opacity 0.3s ease;">
                        <h5 class="fw-bold border-bottom pb-2 mb-3 text-secondary">Detail Peminjaman</h5>

                        <div class="row mb-2">
                            <div class="col-sm-5 text-muted">Nama Peminjam</div>
                            <div class="col-sm-7 fw-bold" id="resNama"></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-5 text-muted">Ruangan</div>
                            <div class="col-sm-7 fw-bold text-primary" id="resRuangan"></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-5 text-muted">Jadwal Pakai</div>
                            <div class="col-sm-7 fw-bold text-dark">
                                <span id="resMulai"></span> <br>
                                <span class="text-muted fw-normal">s/d</span> <span id="resSelesai"></span>
                            </div>
                        </div>

                        <div id="action-container" class="mt-4"></div>
                    </div>
                    <div class="card border-0 shadow-sm mt-5 rounded-4 overflow-hidden">
                        <div class="card-header bg-white border-bottom-0 pt-4 pb-2 px-4">
                            <h5 class="fw-bold mb-0 text-primary">
                                <i class="fas fa-history me-2"></i> Riwayat Check-In Hari Ini
                            </h5>
                            <p class="text-muted small mt-1 mb-0">Daftar ruangan yang sudah aktif digunakan.</p>
                            <div class="card-body px-4 pb-4 pt-2">
                                <table id="tabel-riwayat-checkin" class="table table-hover align-middle w-100"
                                    style="width:100%">
                                    <thead class="table-primary text-primary">
                                        <tr>
                                            <th class="text-center py-3" width="5%">NO</th>
                                            <th class="py-3">WAKTU CHECK-IN</th>
                                            <th class="py-3">KODE BOOKING</th>
                                            <th class="py-3">PEMINJAM</th>
                                            <th class="py-3">RUANGAN</th>
                                        </tr>
                                    </thead>
                                    <tbody class="border-top-0">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endsection

        @push('scripts')
            <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

            <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
            <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
            <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

            <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
            <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
            <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

            <script>
                $(document).ready(function() {

                    // ==========================================
                    // SETUP CSRF TOKEN UNTUK AJAX POST
                    // ==========================================
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    // ==========================================
                    // INISIALISASI DATATABLES RIWAYAT CHECK-IN
                    // ==========================================
                    // Kita simpan ke dalam variabel 'tabelRiwayat' agar nanti bisa di-refresh otomatis
                    let tabelRiwayat = $('#tabel-riwayat-checkin').DataTable({
                        processing: true,
                        serverSide: true,
                        ajax: window.location.href,
                        columns: [{
                                data: 'DT_RowIndex',
                                name: 'DT_RowIndex',
                                orderable: false,
                                searchable: false,
                                className: 'text-center'
                            },
                            {
                                data: 'waktu_checkin',
                                name: 'waktu_checkin',
                                searchable: false
                            },
                            {
                                data: 'kode_booking_badge',
                                name: 'kode_booking'
                            },
                            {
                                data: 'peminjam',
                                name: 'user.name'
                            },
                            {
                                data: 'ruangan_nama',
                                name: 'ruangan.nama_ruangan'
                            }
                        ],
                        language: {
                            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json',
                        },
                        drawCallback: function() {
                            $('.dataTables_wrapper table').removeClass('dataTable');
                        }
                    });

                    // ==========================================
                    // 1. PROSES PENCARIAN (AJAX GET)
                    // ==========================================
                    $('#formSearch').on('submit', function(e) {
                        e.preventDefault();

                        let kode = $('#inputKode').val();

                        $('#textSearch').text('Mencari...');
                        $('#btnSearch').prop('disabled', true);

                        $('#result-container').addClass('d-none');
                        $('#alert-container').html('');
                        $('#loading').removeClass('d-none');

                        $.ajax({
                            url: "{{ route('booking.checkin.search') }}",
                            type: "GET",
                            data: {
                                kode: kode
                            },
                            success: function(response) {
                                $('#loading').addClass('d-none');

                                if (response.status === 'success') {
                                    let data = response.data;

                                    $('#resNama').text(data.user ? data.user.name : '-');
                                    $('#resRuangan').text(data.ruangan ? data.ruangan.nama_ruangan :
                                        '-');
                                    $('#resMulai').text(data.waktu_mulai_format);
                                    $('#resSelesai').text(data.waktu_selesai_format);

                                    let actionHtml = '';
                                    if (data.status_booking === 'Dikonfirmasi') {
                                        actionHtml = `
                                    <button type="button" id="btnCheckinProses" data-kode="${data.kode_booking}" class="btn btn-success w-100 rounded-pill py-2 fw-bold shadow-sm hover-translate-y">
                                        <i class="fas fa-sign-in-alt me-1"></i> Konfirmasi & Check-In Sekarang
                                    </button>
                                `;
                                    } else if (data.status_booking === 'Selesai') {
                                        actionHtml =
                                            `<div class="alert alert-info text-center mb-0 rounded-3 border-0"><i class="fas fa-door-open me-1"></i> Ruangan ini sudah <b>Selesai</b> Check-In / Digunakan.</div>`;
                                    } else {
                                        actionHtml =
                                            `<div class="alert alert-warning text-center mb-0 rounded-3 border-0"><i class="fas fa-exclamation-triangle me-1"></i> Status peminjaman ini adalah <b>${data.status_booking}</b>.</div>`;
                                    }

                                    $('#action-container').html(actionHtml);
                                    $('#result-container').removeClass('d-none').hide().fadeIn('fast');
                                } else {
                                    $('#alert-container').html(`
                                <div class="alert alert-danger alert-dismissible fade show rounded-4 shadow-sm border-0" role="alert">
                                    <i class="fas fa-times-circle me-1"></i> ${response.message}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            `);
                                }
                            },
                            error: function() {
                                $('#loading').addClass('d-none');
                                alert('Terjadi kesalahan jaringan.');
                            },
                            complete: function() {
                                $('#textSearch').text('Cari');
                                $('#btnSearch').prop('disabled', false);
                            }
                        });
                    });

                    // ==========================================
                    // 2. PROSES TOMBOL CHECK-IN (AJAX POST)
                    // ==========================================
                    // PERHATIKAN: Karena tombol di-generate dari AJAX, kita gunakan $(document).on('click'...)
                    $(document).on('click', '#btnCheckinProses', function() {
                        let kode_booking = $(this).data('kode');
                        let btn = $(this);

                        btn.html('<i class="fas fa-spinner fa-spin me-1"></i> Memproses...');
                        btn.prop('disabled', true);

                        $.ajax({
                            url: "{{ route('booking.checkin.proses') }}",
                            type: "POST",
                            data: {
                                kode_booking: kode_booking
                            },
                            success: function(response) {
                                if (response.status === 'success') {
                                    $('#alert-container').html(`
                                <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm border-0" role="alert">
                                    <i class="fas fa-check-circle me-1"></i> ${response.message}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            `);
                                    $('#action-container').html(
                                        `<div class="alert alert-info text-center mb-0 rounded-3 border-0"><i class="fas fa-door-open me-1"></i> Ruangan ini sudah <b>Selesai</b> Check-In / Digunakan.</div>`
                                    );

                                    // 👇 BONUS: Refresh DataTables Otomatis saat berhasil Check-In! 👇
                                    tabelRiwayat.ajax.reload(null, false);

                                } else {
                                    alert(response.message);
                                    btn.html(
                                        '<i class="fas fa-sign-in-alt me-1"></i> Konfirmasi & Check-In Sekarang'
                                    );
                                    btn.prop('disabled', false);
                                }
                            },
                            error: function(xhr) {
                                let errorMsg = 'Terjadi kesalahan server yang tidak diketahui.';

                                if (xhr.status === 419) {
                                    errorMsg =
                                        'Sesi keamanan ditolak (CSRF Token). Pastikan meta tag terpasang dan Refresh halaman ini.';
                                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                                    errorMsg = xhr.responseJSON.message;
                                }

                                alert(errorMsg);
                                btn.html(
                                    '<i class="fas fa-sign-in-alt me-1"></i> Konfirmasi & Check-In Sekarang'
                                );
                                btn.prop('disabled', false);
                            }
                        });
                    });

                });
            </script>
        @endpush
