@extends('layouts.app')
@section('content')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/material_blue.css">

    <style>
        /* ======================================================= */
        /* 1. NAVBAR & BASE KALENDER BUMPER                        */
        /* ======================================================= */
        .navbar-custom {
            background-color: #ffffff;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04);
            padding: 12px 0;
        }

        .navbar-brand {
            font-weight: 700;
            color: #333 !important;
            font-size: 1.4rem;
        }

        .logo-icon {
            width: 38px;
            height: 38px;
            background: linear-gradient(135deg, #0d6efd, #0dcaf0);
        }

        .nav-item .nav-link {
            font-weight: 500;
            color: #666;
            padding: 10px 20px !important;
            border-radius: 50px;
            transition: all 0.3s ease;
        }

        .nav-item .nav-link:not(.active):hover {
            color: #0d6efd;
            background-color: rgba(13, 110, 253, 0.08);
            transform: translateY(-3px);
        }

        .nav-item .nav-link.active {
            background: linear-gradient(135deg, #0d6efd, #0dcaf0);
            color: #ffffff !important;
            box-shadow: 0 4px 10px rgba(13, 110, 253, 0.2);
            transform: translateY(0);
        }

        /* ======================================================= */
        /* 2. DESAIN KALENDER WEEK & DAY (MIGGUAN/HARIAN)          */
        /* ======================================================= */
        .fc-v-event {
            border: none !important;
        }

        .fc-timegrid-event {
            position: relative !important;
            top: 0 !important;
            bottom: auto !important;
            height: auto !important;
            min-height: 35px !important;
            max-height: 45px !important;
            overflow: hidden !important;
            transition: max-height 0.4s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.3s ease, transform 0.2s ease !important;
            z-index: 1 !important;
            border: none !important;
            border-left: 4px solid rgba(255, 255, 255, 0.6) !important;
            border-radius: 6px !important;
            margin: 2px !important;
        }

        .fc-timegrid-event:hover {
            max-height: 300px !important;
            z-index: 9999 !important;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.4) !important;
            transform: scale(1.02);
        }

        .fc-timegrid-event .fc-event-main-frame,
        .fc-timegrid-event .fc-event-title-container {
            display: block !important;
            overflow: hidden !important;
        }

        .fc-timegrid-event:hover .fc-event-main-frame,
        .fc-timegrid-event:hover .fc-event-title-container {
            overflow: visible !important;
        }

        .fc-timegrid-event .fc-event-title {
            white-space: normal !important;
            display: block !important;
            font-size: 0.8rem !important;
            line-height: 1.3 !important;
            margin-top: 3px !important;
        }

        /* Warna Status Mingguan */
        .fc-timegrid-event.event-selesai {
            background-color: #0d6efd !important;
        }

        .fc-timegrid-event.event-selesai .fc-event-time,
        .fc-timegrid-event.event-selesai .fc-event-title {
            color: #ffffff !important;
        }

        .fc-timegrid-event.event-dikonfirmasi {
            background-color: #198754 !important;
        }

        .fc-timegrid-event.event-dikonfirmasi .fc-event-time,
        .fc-timegrid-event.event-dikonfirmasi .fc-event-title {
            color: #ffffff !important;
        }

        .fc-timegrid-event.event-pending {
            background-color: #ffc107 !important;
        }

        .fc-timegrid-event.event-pending .fc-event-time,
        .fc-timegrid-event.event-pending .fc-event-title {
            color: #212529 !important;
        }

        /* ======================================================= */
        /* 3. DESAIN KALENDER MONTH (BULANAN - ANTI-TEXT BOUNCE)   */
        /* ======================================================= */
        .fc-daygrid-event-harness {
            max-width: 100% !important;
            overflow: visible !important;
        }

        .fc-daygrid-event-harness:hover {
            z-index: 9999 !important;
        }

        .fc-daygrid-event {
            background-color: #ffffff !important;
            border: 1px solid #e2e8f0 !important;
            border-radius: 6px !important;
            padding: 4px 8px 4px 22px !important;
            margin: 2px !important;
            display: block !important;
            width: calc(100% - 4px) !important;
            max-width: 100% !important;
            height: 26px !important;
            box-sizing: border-box !important;
            position: relative !important;
            overflow: hidden !important;
            z-index: 1 !important;
            transition: transform 0.2s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.2s ease !important;
            transform-origin: top left !important;
        }

        .fc-daygrid-event-dot {
            display: none !important;
        }

        .fc-daygrid-event::before {
            content: "";
            position: absolute;
            left: 8px;
            top: 9px;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: #cbd5e1;
        }

        .fc-daygrid-event.event-selesai::before {
            background-color: #0d6efd !important;
        }

        .fc-daygrid-event.event-dikonfirmasi::before {
            background-color: #198754 !important;
        }

        .fc-daygrid-event.event-pending::before {
            background-color: #ffc107 !important;
        }

        .fc-daygrid-event .fc-event-main {
            display: flex !important;
            align-items: flex-start !important;
            width: 100% !important;
            overflow: hidden !important;
            white-space: nowrap !important;
        }

        .fc-daygrid-event .fc-event-time {
            font-weight: 800 !important;
            margin: 0 5px 0 0 !important;
            color: #334155 !important;
            flex-shrink: 0 !important;
            font-size: 0.75rem !important;
        }

        .fc-daygrid-event .fc-event-title {
            font-weight: 600 !important;
            color: #475569 !important;
            font-size: 0.75rem !important;
            overflow: hidden !important;
            text-overflow: ellipsis !important;
            white-space: nowrap !important;
            flex-grow: 1 !important;
        }

        .fc-daygrid-event:hover {
            width: max-content !important;
            min-width: 100% !important;
            max-width: 280px !important;
            height: auto !important;
            position: absolute !important;
            z-index: 99999 !important;
            transform: translateY(-3px) scale(1.02) !important;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2) !important;
            border-color: #b6d4fe !important;
        }

        .fc-daygrid-event:hover .fc-event-main {
            white-space: normal !important;
            flex-wrap: wrap !important;
        }

        .fc-daygrid-event:hover .fc-event-title {
            white-space: normal !important;
            overflow: visible !important;
            line-height: 1.4 !important;
        }

        /* ======================================================= */
        /* 4. EFEK HOVER & POINTER TANGGALAN (TANGAN)              */
        /* ======================================================= */
        .fc-daygrid-day-frame,
        .fc-timegrid-slot {
            cursor: pointer !important;
            transition: background-color 0.2s ease, box-shadow 0.2s ease !important;
        }

        .fc-daygrid-day-frame:hover,
        .fc-timegrid-slot:hover {
            background-color: rgba(13, 110, 253, 0.04) !important;
            box-shadow: inset 0 0 15px rgba(13, 110, 253, 0.08) !important;
        }

        .fc-daygrid-day-frame:hover .fc-daygrid-day-number {
            color: #0d6efd !important;
            font-weight: bold !important;
            transform: scale(1.1);
            transition: all 0.2s ease;
        }

        /* ======================================================= */
        /* 5. DESAIN SEAMLESS DURASI SEWA (WADAH INPUT)            */
        /* ======================================================= */
        .durasi-group {
            border: 2px solid #b6d4fe;
            border-radius: 10px;
            transition: all 0.3s ease;
            background-color: #f8faff;
            position: relative;
        }

        .durasi-group:focus-within {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15) !important;
            background-color: #ffffff;
        }

        .durasi-group input {
            background-color: transparent !important;
            border-radius: 10px 0 0 10px;
        }

        /* ======================================================= */
        /* 6. CUSTOM UI DROPDOWN MODERN (FIX ULTRA SMOOTH)         */
        /* ======================================================= */
        .custom-select-btn {
            padding-right: 15px;
            position: relative;
            transition: all 0.2s ease;
            border-radius: 0 10px 10px 0 !important;
        }

        .custom-select-btn:after {
            display: inline-block;
            margin-left: 8px;
            vertical-align: middle;
            content: "";
            border-top: 5px solid #0d6efd;
            border-right: 5px solid transparent;
            border-bottom: 0;
            border-left: 5px solid transparent;
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .custom-select-btn[aria-expanded="true"]:after {
            transform: rotate(180deg);
        }

        .custom-dropdown-menu {
            border: none !important;
            border-radius: 12px !important;
            padding: 8px !important;
            min-width: 150px !important;
            background-color: #ffffff !important;
            box-shadow: 0 10px 35px rgba(0, 0, 0, 0.12) !important;
            z-index: 9999 !important;
            display: none;
        }

        .dropdown-menu.custom-dropdown-menu.show {
            display: block;
            animation: smoothDropdown 0.35s cubic-bezier(0.16, 1, 0.3, 1) forwards !important;
        }

        @keyframes smoothDropdown {
            0% {
                opacity: 0;
                margin-top: -10px;
            }

            100% {
                opacity: 1;
                margin-top: 8px;
            }
        }

        .custom-dropdown-item {
            border-radius: 8px !important;
            padding: 10px 15px !important;
            font-weight: 600 !important;
            color: #64748b !important;
            margin-bottom: 4px !important;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1) !important;
        }

        .custom-dropdown-item:last-child {
            margin-bottom: 0 !important;
        }

        .custom-dropdown-item:hover,
        .custom-dropdown-item:focus {
            background-color: #f0f7ff !important;
            color: #0d6efd !important;
            padding-left: 22px !important;
        }

        .custom-dropdown-item.active {
            background-color: #0d6efd !important;
            color: #ffffff !important;
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.25) !important;
        }

        .custom-dropdown-item.active:hover {
            padding-left: 15px !important;
            color: #ffffff !important;
        }

        /* ======================================================= */
        /* 7. ANIMASI TRANSISI MENU (MONTH/WEEK/LIST) & TOMBOL     */
        /* ======================================================= */
        .fc-view-harness {
            background-color: #ffffff;
            overflow: hidden !important;
            border-radius: 10px;
        }

        .fc-view {
            animation: smoothViewChange 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        @keyframes smoothViewChange {
            0% {
                opacity: 0;
                transform: translateY(15px) scale(0.99);
            }

            100% {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .fc-button-group {
            background-color: #f1f5f9;
            border-radius: 50px;
            padding: 4px;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.04);
        }

        .fc-button-primary {
            background-color: transparent !important;
            border: none !important;
            color: #64748b !important;
            font-weight: 600 !important;
            text-transform: capitalize !important;
            border-radius: 50px !important;
            padding: 8px 16px !important;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1) !important;
            box-shadow: none !important;
        }

        .fc-button-primary:not(:disabled):hover {
            color: #0d6efd !important;
            background-color: rgba(13, 110, 253, 0.08) !important;
        }

        .fc-button-primary:not(:disabled).fc-button-active,
        .fc-button-primary:not(:disabled):active {
            background-color: #ffffff !important;
            color: #0d6efd !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08) !important;
            transform: scale(1.02);
        }

        .fc-button-primary:focus {
            box-shadow: none !important;
        }

        /* 8. PERMAK TOMBOL PREV & NEXT */
        .fc-prev-button,
        .fc-next-button {
            border-radius: 8px !important;
            background-color: #ffffff !important;
            border: 1px solid #e2e8f0 !important;
            color: #475569 !important;
            margin: 0 2px !important;
            transition: all 0.2s ease !important;
        }

        .fc-prev-button:hover,
        .fc-next-button:hover {
            background-color: #f8faff !important;
            border-color: #b6d4fe !important;
            color: #0d6efd !important;
        }

        /* ======================================================= */
        /* 9. PERMAK TOMBOL TODAY (DIPAKSA SELALU BIRU TERANG 100%)*/
        /* ======================================================= */
        .fc-today-button,
        .fc-today-button:disabled {
            background: #0d6efd !important;
            border: 1px solid #0d6efd !important;
            color: #ffffff !important;
            font-weight: 700 !important;
            margin: 0 2px 0 10px !important;
            padding: 8px 18px !important;
            border-radius: 8px !important;
            opacity: 1 !important;
            text-transform: capitalize !important;
            box-shadow: 0 4px 10px rgba(13, 110, 253, 0.25) !important;
            transition: all 0.3s ease !important;
        }

        .fc-today-button:not(:disabled):hover {
            background: #0b5ed7 !important;
            border-color: #0a58ca !important;
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(13, 110, 253, 0.4) !important;
            cursor: pointer !important;
        }

        .fc-today-button:disabled {
            cursor: default !important;
            transform: none !important;
            box-shadow: none !important;
        }

        /* Efek Berkedip Halus */
        .blink-alert {
            animation: blinker 1.5s cubic-bezier(.5, 0, 1, 1) infinite alternate;
            background-color: #fff8e1;
            border: 1px solid #ffe082;
        }

        @keyframes blinker {
            from {
                opacity: 1;
            }

            to {
                opacity: 0.6;
            }
        }

        /* Efek Berdenyut pada Badge */
        .animate-pulse {
            animation: pulse-sm 2s infinite;
        }

        @keyframes pulse-sm {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }

            100% {
                transform: scale(1);
            }
        }

        @keyframes blinkSuperWarning {
            0% {
                background-color: #dc3545 !important;
                border-color: #a71d2a !important;
                color: #fff !important;
                box-shadow: 0 0 12px rgba(220, 53, 69, 0.9);
                transform: scale(1.02);
            }

            50% {
                background-color: #ffc107 !important;
                border-color: #d39e00 !important;
                color: #000 !important;
                box-shadow: none;
                transform: scale(1);
            }

            100% {
                background-color: #dc3545 !important;
                border-color: #a71d2a !important;
                color: #fff !important;
                box-shadow: 0 0 12px rgba(220, 53, 69, 0.9);
                transform: scale(1.02);
            }
        }

        .event-h-min-2 {
            animation: blinkSuperWarning 0.8s infinite !important;
            cursor: pointer !important;
            font-weight: 800 !important;
            z-index: 99 !important;
            /* Agar tampil di atas jadwal lain */
        }
    </style>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
                    <h5 class="mb-0 fw-bold text-primary"><i class="fas fa-calendar-alt me-2"></i> Jadwal Peminjaman Ruangan
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex flex-wrap justify-content-end gap-2 mb-4">
                        <span class="badge rounded-pill bg-success px-3 py-2 shadow-sm">
                            <i class="fas fa-check-circle me-1"></i> Dikonfirmasi
                        </span>
                        <span class="badge rounded-pill bg-warning text-dark px-3 py-2 shadow-sm">
                            <i class="fas fa-clock me-1"></i> Menunggu
                        </span>
                        <span class="badge rounded-pill bg-primary px-3 py-2 shadow-sm">
                            <i class="fas fa-flag-checkered me-1"></i> Selesai / Digunakan
                        </span>
                    </div>
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalBooking" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 15px;">
                <div class="modal-header bg-primary text-white" style="border-radius: 15px 15px 0 0;">
                    <h5 class="modal-title fw-bold"><i class="fas fa-calendar-plus me-2"></i> Form Booking Ruangan</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form id="formBooking">
                    <div class="modal-body p-4">

                        <div class="row mb-2">
                            <div class="col-md-6 mb-2 mb-md-0">
                                <label class="form-label fw-bold">Nama Lengkap Anda <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="nama_peminjam" class="form-control" required
                                    placeholder="Masukkan nama Anda">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Nomor WhatsApp Pelanggan <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="no_hp" class="form-control" placeholder="Contoh: 628123456789"
                                    required>
                                <small class="text-muted text-danger" style="font-size: 0.75rem;">*Gunakan awalan 62, tanpa
                                    spasi atau +</small>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12">
                                <label class="form-label fw-bold text-secondary">Pilih Ruangan <span
                                        class="text-danger">*</span></label>
                                <select class="form-select border-primary cursor-pointer" name="ruangan_id"
                                    id="pilih_ruangan" required>
                                    <option value="">-- Silakan Pilih Ruangan --</option>
                                    @foreach ($ruangans as $r)
                                        <option value="{{ $r->id }}" data-h5="{{ $r->harga_5_jam ?? 0 }}"
                                            data-h1d="{{ $r->harga_1_hari ?? 0 }}" data-h3d="{{ $r->harga_3_hari ?? 0 }}"
                                            data-h1w="{{ $r->harga_1_minggu ?? 0 }}">
                                            {{ $r->nama_ruangan }} (Kapasitas: {{ $r->kapasitas }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row bg-light pt-3 pb-1 mb-3 rounded border mx-0">
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold text-secondary small">Waktu Mulai</label>
                                <input type="text" class="form-control jam-premium bg-white border-primary"
                                    name="waktu_mulai" id="waktu_mulai" required placeholder="Pilih Mulai">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold text-primary small"><i class="fas fa-stopwatch me-1"></i>
                                    Durasi Sewa</label>
                                <div class="input-group shadow-sm durasi-group">
                                    <input type="number"
                                        class="form-control border-0 text-center fw-bold text-primary ps-3"
                                        id="input_durasi" value="5" min="1" step="1"
                                        style="font-size: 1.15rem; box-shadow: none; z-index: 1;">

                                    <div class="d-flex align-items-center">
                                        <div style="width: 1px; height: 25px; background-color: #b6d4fe;"></div>
                                    </div>

                                    <div class="dropdown" style="z-index: 2;">
                                        <button class="btn border-0 text-primary fw-bold custom-select-btn w-100"
                                            type="button" id="dropdownDurasi" data-bs-toggle="dropdown"
                                            aria-expanded="false"
                                            style="box-shadow: none; font-size: 0.95rem; background: transparent; height: 100%; border-radius: 0 10px 10px 0;">
                                            <span id="text_satuan">Jam</span>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg custom-dropdown-menu"
                                            aria-labelledby="dropdownDurasi">
                                            <li><a class="dropdown-item custom-dropdown-item active" href="#"
                                                    data-value="jam" data-text="Jam">Satuan Jam</a></li>
                                            <li><a class="dropdown-item custom-dropdown-item" href="#"
                                                    data-value="hari" data-text="Hari">Satuan Hari</a></li>
                                            <li><a class="dropdown-item custom-dropdown-item" href="#"
                                                    data-value="minggu" data-text="Mgg">Satuan Minggu</a></li>
                                        </ul>
                                    </div>
                                    <input type="hidden" id="satuan_durasi" value="jam">
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold text-secondary small">Waktu Selesai</label>
                                <input type="text" class="form-control jam-premium bg-white border-primary"
                                    name="waktu_selesai" id="waktu_selesai" required placeholder="Pilih Selesai">
                            </div>
                        </div>

                        <div id="box-estimasi" class="p-3 rounded-3 mb-3 border d-none"
                            style="background-color: #eaf4ff; border-color: #b6d4fe !important;">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="small text-muted d-block fw-bold mb-1"><i
                                            class="fas fa-tags text-primary me-1"></i> Estimasi Biaya Sewa:</span>
                                    <h3 class="fw-bold text-primary mb-0" id="label-total-harga">Rp 0</h3>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-primary rounded-pill px-3 py-2 shadow-sm fs-6"
                                        id="label-durasi">0 Jam</span>
                                </div>
                            </div>
                            <input type="hidden" name="total_bayar" id="input-total-harga">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-secondary">Keperluan / Kegiatan <span
                                    class="text-danger">*</span></label>
                            <textarea class="form-control border-primary" name="keperluan" rows="3"
                                placeholder="Contoh: Rapat Presentasi Proyek X" required style="resize: none;"></textarea>
                        </div>

                    </div>
                    <div class="modal-footer bg-light" style="border-radius: 0 0 15px 15px;">
                        <button type="button" class="btn btn-secondary rounded-pill px-4"
                            data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold"
                            id="btnSave"><i class="fas fa-save me-1"></i> Simpan Booking</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            // 1. Inisialisasi Jam Premium
            flatpickr(".jam-premium", {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                altInput: true,
                altFormat: "d M Y, H:i",
                time_24hr: true,
                minuteIncrement: 15,
                disableMobile: "true",
                onChange: function() {
                    hitungHargaOtomatis();
                }
            });

            // 2A. SCRIPT CUSTOM UI DROPDOWN
            function updateDropdownUI(val) {
                $('.custom-dropdown-item').removeClass('active');
                let item = $('.custom-dropdown-item[data-value="' + val + '"]');
                item.addClass('active');
                $('#text_satuan').text(item.data('text'));
                $('#satuan_durasi').val(val);
            }

            $('.custom-dropdown-item').on('click', function(e) {
                e.preventDefault();
                updateDropdownUI($(this).data('value'));
                $('#satuan_durasi').trigger('change'); // Pancing perhitungan ulang
            });

            // 2B. KETIKA ANGKA DURASI ATAU SATUAN DIUBAH
            $('#input_durasi, #satuan_durasi').on('input change', function() {
                let durasi = parseInt($('#input_durasi').val());
                if (isNaN(durasi) || durasi < 1) return;

                let satuan = $('#satuan_durasi').val();
                let jamTambahan = durasi;

                // Konversi satuan ke Jam
                if (satuan === 'hari') jamTambahan = durasi * 24;
                if (satuan === 'minggu') jamTambahan = durasi * 168;

                let fpMulai = document.getElementById("waktu_mulai")._flatpickr;
                let fpSelesai = document.getElementById("waktu_selesai")._flatpickr;

                if (fpMulai.selectedDates[0]) {
                    // Majukan waktu selesai sebanyak X jam
                    let newEnd = new Date(fpMulai.selectedDates[0].getTime());
                    newEnd.setHours(newEnd.getHours() + jamTambahan);

                    // Trigger parameter 'true' akan otomatis memanggil hitungHargaOtomatis
                    fpSelesai.setDate(newEnd, true);
                }
            });

            // 3. LOGIKA HARGA CERDAS (Smart Bracket Pricing)
            function hitungHargaOtomatis() {
                let option = $('#pilih_ruangan').find('option:selected');
                let ruanganId = option.val();

                let fpMulai = document.getElementById("waktu_mulai")._flatpickr;
                let fpSelesai = document.getElementById("waktu_selesai")._flatpickr;

                if (ruanganId && fpMulai.selectedDates[0] && fpSelesai.selectedDates[0]) {
                    let start = fpMulai.selectedDates[0];
                    let end = fpSelesai.selectedDates[0];

                    let diffMs = end - start;
                    let diffHours = Math.ceil(diffMs / (1000 * 60 * 60));

                    if (diffHours <= 0) {
                        $('#box-estimasi').addClass('d-none');
                        return;
                    }

                    // SINKRONISASI 2 ARAH (Cek agar tidak bentrok saat user mengetik)
                    if (!$('#input_durasi').is(':focus')) {
                        if (diffHours % 168 === 0) {
                            $('#input_durasi').val(diffHours / 168);
                            updateDropdownUI('minggu');
                        } else if (diffHours % 24 === 0) {
                            $('#input_durasi').val(diffHours / 24);
                            updateDropdownUI('hari');
                        } else {
                            $('#input_durasi').val(diffHours);
                            updateDropdownUI('jam');
                        }
                    }

                    // Tarik Harga dari Database HTML
                    let h5 = parseInt(option.attr('data-h5')) || 0;
                    let h1d = parseInt(option.attr('data-h1d')) || 0;
                    let h3d = parseInt(option.attr('data-h3d')) || 0;
                    let h1w = parseInt(option.attr('data-h1w')) || 0;

                    let sisaJam = diffHours;
                    let minggu = Math.floor(sisaJam / 168);
                    sisaJam = sisaJam % 168;

                    let tigaHari = Math.floor(sisaJam / 72);
                    sisaJam = sisaJam % 72;

                    let satuHari = Math.floor(sisaJam / 24);
                    sisaJam = sisaJam % 24;

                    let total = (minggu * h1w) + (tigaHari * h3d) + (satuHari * h1d);

                    if (sisaJam > 0) {
                        if (sisaJam <= 5) {
                            total += h5;
                        } else {
                            total += h1d;
                        }
                    }

                    // Tampilkan ke Layar
                    $('#box-estimasi').removeClass('d-none');
                    if (total === 0) {
                        $('#label-total-harga').html(
                            '<span class="text-danger fs-6 fw-bold">Harga Belum Diatur Admin</span>');
                    } else {
                        $('#label-total-harga').text("Rp " + total.toLocaleString('id-ID'));
                    }

                    $('#label-durasi').text(diffHours + " Jam Total");
                    $('#input-total-harga').val(total);
                } else {
                    $('#box-estimasi').addClass('d-none');
                }
            }

            $('#pilih_ruangan').on('change', function() {
                hitungHargaOtomatis();
            });

            // 4. Inisialisasi Kalender Utama
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,listMonth'
                },
                dayMaxEvents: true,
                locale: 'id',
                events: "{{ route('booking.events') }}",

                dateClick: function(info) {
                    $('#formBooking')[0].reset();
                    $('#box-estimasi').addClass('d-none');

                    // Kembalikan form ke setelan default 5 Jam
                    $('#input_durasi').val(5);
                    updateDropdownUI('jam');

                    let dateObj = info.date;

                    if (info.allDay) {
                        dateObj.setHours(8, 0, 0, 0);
                    } else {
                        let mins = dateObj.getMinutes();
                        let roundedMins = Math.round(mins / 15) * 15;
                        dateObj.setMinutes(roundedMins);
                        dateObj.setSeconds(0);
                    }

                    function formatUntukInput(d) {
                        let yyyy = d.getFullYear();
                        let mm = String(d.getMonth() + 1).padStart(2, '0');
                        let dd = String(d.getDate()).padStart(2, '0');
                        let hh = String(d.getHours()).padStart(2, '0');
                        let min = String(d.getMinutes()).padStart(2, '0');
                        return `${yyyy}-${mm}-${dd} ${hh}:${min}`;
                    }

                    let jamMulai = formatUntukInput(dateObj);
                    let endDateObj = new Date(dateObj.getTime());
                    endDateObj.setHours(endDateObj.getHours() + 5);
                    let jamSelesai = formatUntukInput(endDateObj);

                    document.getElementById("waktu_mulai")._flatpickr.setDate(jamMulai, true);
                    document.getElementById("waktu_selesai")._flatpickr.setDate(jamSelesai, true);

                    $('#modalBooking').modal('show');
                },

                // --- [FITUR BARU] A. Fungsi Cek H-2 dan Tambah Efek Kelap-Kelip ---
                eventDidMount: function(info) {
                    let eventDate = new Date(info.event.start);
                    let today = new Date();

                    // Nol-kan jam agar perhitungan hari akurat
                    eventDate.setHours(0, 0, 0, 0);
                    today.setHours(0, 0, 0, 0);

                    // Hitung selisih hari
                    let diffTime = eventDate.getTime() - today.getTime();
                    let diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

                    // Ambil status dari controller
                    let statusBooking = info.event.extendedProps.status;

                    // [REVISI]: Berkedip JIKA H-2 ***DAN*** Status BUKAN Selesai
                    if (diffDays === 2 && statusBooking !== 'Selesai') {
                        info.el.classList.add('event-h-min-2');
                    }
                },

                // --- [FITUR BARU] B. Fungsi Klik Event untuk Kirim WA ---
                eventClick: function(info) {
                    if (info.el.classList.contains('event-h-min-2')) {

                        let props = info.event.extendedProps;
                        let namaUser = props.nama_penyewa || "Penyewa";
                        let noHp = props.nohp || "";

                        // Hapus tulisan "- Keperluan" dari title kalender untuk pesan WA
                        let namaRuangan = info.event.title.split(' - ')[0];

                        // Format Tanggal dan Jam
                        let startDate = new Date(info.event.start);
                        let tglSewa = startDate.toLocaleDateString('id-ID', {
                            day: '2-digit',
                            month: 'long',
                            year: 'numeric'
                        });
                        let waktuMulai = startDate.toLocaleTimeString('id-ID', {
                            hour: '2-digit',
                            minute: '2-digit'
                        });

                        // JIKA DATA LAMA (Nomor HP Kosong)
                        if (!noHp) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Data Booking Lama',
                                text: 'Jadwal ini adalah data lama yang dibuat sebelum fitur Nomor WhatsApp aktif. Silakan hapus jadwal ini dan buat simulasi jadwal H-2 yang BARU.'
                            });
                            return; // Hentikan proses
                        }

                        // Format nomor HP ke standar internasional (62)
                        if (noHp.startsWith('0')) {
                            noHp = '62' + noHp.substring(1);
                        }

                        // FORMAT PESAN WA (Sama persis dengan format Submit Booking)
                        let textWa = `Halo *${namaUser}*! 🔔\n\n` +
                            `Ini adalah pengingat otomatis (Reminder H-2) dari sistem bahwa ruangan Anda akan segera digunakan.\n\n` +
                            `Berikut adalah rangkuman jadwal Anda:\n` +
                            `📍 *Ruangan:* ${namaRuangan}\n` +
                            `📅 *Tanggal:* ${tglSewa}\n` +
                            `⏰ *Waktu:* ${waktuMulai} WIB\n` +
                            `🎯 *Keperluan:* ${props.keperluan || '-'}\n` +
                            `🏷️ *KODE BOOKING ANDA: ${props.kode_booking || '-'}*\n\n` +
                            `Mohon persiapkan diri Anda sesuai jadwal tersebut. Kami tunggu kedatangannya!\n\n` +
                            `*Tim Admin Layanan Ruangan*`;

                        let urlWa = `https://wa.me/${noHp}?text=${encodeURIComponent(textWa)}`;

                        // Tampilkan Konfirmasi menggunakan SweetAlert
                        Swal.fire({
                            title: 'Kirim Reminder WA?',
                            html: `Pesan pengingat akan dikirimkan ke <b>${namaUser}</b><br><small>(${info.event.extendedProps.nohp})</small>`,
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonColor: '#25D366',
                            cancelButtonColor: '#6c757d',
                            confirmButtonText: '<i class="fab fa-whatsapp"></i> Kirim Pesan WA',
                            cancelButtonText: 'Batal'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // AMBIL ID DARI DATA KALENDER (info.event.id)
                                let idBooking = info.event.id;

                                // BUKA TAB BARU KE ROUTE REMIND
                                window.open('/booking/remind/' + idBooking, '_blank');
                            }
                        });
                    }
                }
            });
            calendar.render();

            // 5. Proses Simpan AJAX
            $('#formBooking').on('submit', function(e) {
                e.preventDefault();

                let btn = $('#btnSave');
                btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Memproses...');

                $.ajax({
                    url: "{{ route('booking.store') }}",
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#modalBooking').modal('hide');

                        // Modifikasi SweetAlert untuk menampilkan Tombol Lanjut WA
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message,
                            showConfirmButton: true,
                            confirmButtonText: '<i class="fab fa-whatsapp"></i> Kirim Bukti WA',
                            confirmButtonColor: '#25D366',
                            showCancelButton: true,
                            cancelButtonText: 'Tutup',
                        }).then((result) => {
                            if (result.isConfirmed && response.link_wa) {
                                window.open(response.link_wa, '_blank');
                            }
                        });

                        calendar.refetchEvents();
                        btn.prop('disabled', false).html(
                            '<i class="fas fa-save me-1"></i> Simpan Booking');
                    },
                    error: function(xhr) {
                        let errorMessage = 'Terjadi kesalahan sistem!';
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            errorMessage = Object.values(xhr.responseJSON.errors)[0][0];
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: errorMessage
                        });
                        btn.prop('disabled', false).html(
                            '<i class="fas fa-save me-1"></i> Simpan Booking');
                    }
                });
            });
        });
    </script>
@endpush
