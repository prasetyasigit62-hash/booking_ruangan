@extends('layouts.app')

@section('content')
    <style>
        .card-premium {
            border: none;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
            transition: all 0.3s ease;
        }

        .table-premium thead th {
            background-color: #f8f9fc !important;
            color: #4e73df;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 1px;
            border-bottom: 2px solid #e3e6f0 !important;
            padding: 15px;
        }

        .table-premium tbody tr {
            transition: all 0.2s ease;
            border-bottom: 1px solid #f1f3f5;
        }

        .table-premium tbody tr:hover {
            background-color: #f8f9fc;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.03);
        }

        .dataTables_wrapper .dataTables_filter input {
            border-radius: 20px;
            padding: 6px 16px;
            border: 1px solid #d1d3e2;
            outline: none;
            transition: border-color 0.2s ease;
        }

        .dataTables_wrapper .dataTables_filter input:focus {
            border-color: #4e73df;
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        }

        .btn-aksi {
            border-radius: 8px;
            transition: all 0.2s;
        }

        .btn-aksi:hover {
            transform: scale(1.1);
        }

        #tableRuangan img {
            cursor: pointer;
            transition: transform 0.3s ease;
            border: 2px solid transparent;
        }

        #tableRuangan img:hover {
            transform: scale(1.15);
            z-index: 10;
            position: relative;
            border-color: #0d6efd;
            box-shadow: 0 8px 15px rgba(13, 110, 253, 0.3);
        }

        .modal-backdrop.show {
            opacity: 0.85 !important;
        }
    </style>

    <div class="container-fluid px-4 py-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
            <div>
                <h3 class="fw-bold mb-1 text-gray-800"><i class="fas fa-door-open text-primary me-2"></i> Master Data Ruangan
                </h3>
                <p class="text-muted small mb-0">Kelola daftar ruangan, harga sewa, dan fasilitas dengan mudah.</p>
            </div>
            <div class="mt-3 mt-md-0">
                <button class="btn btn-primary rounded-pill px-4 shadow-sm" data-bs-toggle="modal"
                    data-bs-target="#modalTambah">
                    <i class="fas fa-plus-circle me-1"></i> Tambah Ruangan Baru
                </button>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3" role="alert">
                <i class="fas fa-check-circle me-2 fs-5 align-middle"></i>
                <span class="align-middle">{{ session('success') }}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card card-premium">
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-borderless table-premium align-middle" id="tableRuangan" width="100%">
                        <thead>
                            <tr>
                                <th width="5%" class="text-center rounded-start">No</th>
                                <th width="15%" class="text-center">Visual</th>
                                <th width="25%">Detail Ruangan</th>
                                <th width="40%">Fasilitas</th>
                                <th width="15%" class="text-center rounded-end">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalPreviewFoto" tabindex="-1" aria-hidden="true" style="z-index: 9999;">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content border-0 bg-transparent shadow-none">
                <div class="modal-header border-0 pb-0 justify-content-end">
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"
                        style="filter: invert(1); opacity: 1; font-size: 1.5rem; text-shadow: 0 0 10px black;"></button>
                </div>
                <div class="modal-body text-center pt-0">
                    <img id="previewImageSrc" src="" class="img-fluid rounded-3"
                        style="max-height: 85vh; border: 4px solid white; box-shadow: 0 0 40px rgba(0,0,0,0.8);">
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalTambah" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
                <form action="{{ route('ruangan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header border-0 bg-primary text-white" style="border-radius: 16px 16px 0 0;">
                        <h5 class="modal-title fw-bold"><i class="fas fa-plus-circle me-2"></i>Tambah Ruangan Baru</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label class="form-label fw-medium text-muted">Nama Ruangan <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-lg bg-light border-0"
                                    name="nama_ruangan" required placeholder="Contoh: Ruang Rapat Alpha">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-medium text-muted">Kapasitas (Orang)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0"><i
                                            class="fas fa-users text-primary"></i></span>
                                    <input type="number" class="form-control form-control-lg bg-light border-0"
                                        name="kapasitas" placeholder="Misal: 30">
                                </div>
                            </div>
                        </div>

                        <div class="p-3 bg-light rounded-3 mb-3 border">
                            <h6 class="fw-bold text-primary mb-3"><i class="fas fa-tags me-1"></i> Konfigurasi Harga Sewa
                                (Rp)</h6>
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <label class="form-label small fw-bold text-muted">Per 5 Jam</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-0">Rp</span>
                                        <input type="text" class="form-control border-0 shadow-sm input-rupiah"
                                            name="harga_5_jam" placeholder="0">
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label small fw-bold text-muted">Per 1 Hari</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-0">Rp</span>
                                        <input type="text" class="form-control border-0 shadow-sm input-rupiah"
                                            name="harga_1_hari" placeholder="0">
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label small fw-bold text-muted">Per 3 Hari</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-0">Rp</span>
                                        <input type="text" class="form-control border-0 shadow-sm input-rupiah"
                                            name="harga_3_hari" placeholder="0">
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label small fw-bold text-muted">Per 1 Minggu</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-0">Rp</span>
                                        <input type="text" class="form-control border-0 shadow-sm input-rupiah"
                                            name="harga_1_minggu" placeholder="0">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-medium text-muted">Fasilitas Tersedia</label>
                            <textarea class="form-control bg-light border-0" name="fasilitas" rows="3"
                                placeholder="Misal: Proyektor, AC, Papan Tulis..."></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-medium text-muted">Unggah Foto Ruangan</label>
                            <input type="file" class="form-control bg-light border-0" name="foto"
                                accept="image/*">
                            <small class="text-primary mt-1 d-block"><i class="fas fa-info-circle me-1"></i> Format
                                disarankan: JPG/PNG.</small>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4 pt-0">
                        <button type="button" class="btn btn-light rounded-pill px-4"
                            data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm"><i
                                class="fas fa-save me-1"></i> Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalEdit" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
                <form id="formEditRuangan" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-header border-0 bg-warning" style="border-radius: 16px 16px 0 0;">
                        <h5 class="modal-title fw-bold text-dark"><i class="fas fa-edit me-2"></i>Edit Detail Ruangan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label class="form-label fw-medium text-muted">Nama Ruangan <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-lg bg-light border-0"
                                    name="nama_ruangan" id="edit_nama" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-medium text-muted">Kapasitas (Orang)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0"><i
                                            class="fas fa-users text-primary"></i></span>
                                    <input type="number" class="form-control form-control-lg bg-light border-0"
                                        name="kapasitas" id="edit_kapasitas">
                                </div>
                            </div>
                        </div>

                        <div class="p-3 bg-light rounded-3 mb-3 border border-warning">
                            <h6 class="fw-bold text-warning text-dark mb-3"><i class="fas fa-tags me-1"></i> Update Harga
                                Sewa (Rp)</h6>
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <label class="form-label small fw-bold text-muted">Per 5 Jam</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-0">Rp</span>
                                        <input type="text" class="form-control border-0 shadow-sm input-rupiah"
                                            name="harga_5_jam" id="edit_harga_5_jam" placeholder="0">
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label small fw-bold text-muted">Per 1 Hari</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-0">Rp</span>
                                        <input type="text" class="form-control border-0 shadow-sm input-rupiah"
                                            name="harga_1_hari" id="edit_harga_1_hari" placeholder="0">
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label small fw-bold text-muted">Per 3 Hari</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-0">Rp</span>
                                        <input type="text" class="form-control border-0 shadow-sm input-rupiah"
                                            name="harga_3_hari" id="edit_harga_3_hari" placeholder="0">
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label small fw-bold text-muted">Per 1 Minggu</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-0">Rp</span>
                                        <input type="text" class="form-control border-0 shadow-sm input-rupiah"
                                            name="harga_1_minggu" id="edit_harga_1_minggu" placeholder="0">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-medium text-muted">Fasilitas Tersedia</label>
                            <textarea class="form-control bg-light border-0" name="fasilitas" id="edit_fasilitas" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-medium text-muted">Perbarui Foto Ruangan</label>
                            <input type="file" class="form-control bg-light border-0" name="foto"
                                accept="image/*">
                            <small class="text-muted mt-1 d-block">Biarkan kosong jika tidak ingin mengubah foto saat
                                ini.</small>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4 pt-0">
                        <button type="button" class="btn btn-light rounded-pill px-4"
                            data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning rounded-pill px-4 shadow-sm fw-bold"><i
                                class="fas fa-save me-1"></i> Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

    <script>
        var $jq = jQuery.noConflict(true);

        $jq(document).ready(function() {
            $jq('#tableRuangan').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('ruangan.index') }}',
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "🔍 Cari ruangan...",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_ hingga _END_ dari _TOTAL_ ruangan",
                    infoEmpty: "Tidak ada data tersedia",
                    paginate: {
                        first: "Awal",
                        last: "Akhir",
                        next: "Selanjutnya ❯",
                        previous: "❮ Sebelumnya"
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'text-center fw-bold text-muted'
                    },
                    {
                        data: 'foto_ruangan',
                        name: 'foto',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'info',
                        name: 'nama_ruangan'
                    },
                    {
                        data: 'fasilitas_ruangan',
                        name: 'fasilitas',
                        render: function(data) {
                            return '<div class="text-muted" style="white-space: normal !important; word-wrap: break-word; min-width: 250px; line-height: 1.6;">' +
                                data + '</div>';
                        }
                    },
                    {
                        data: 'aksi',
                        name: 'aksi',
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        render: function(data, type, row) {
                            return data.replace(/btn-sm/g, 'btn-sm btn-aksi shadow-sm');
                        }
                    }
                ],
                dom: '<"row mb-3"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row mt-3"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>'
            });

            $jq('body').on('click', '#tableRuangan img', function() {
                let urlFoto = $jq(this).attr('src');
                $jq('#previewImageSrc').attr('src', urlFoto);
                var myModal = new bootstrap.Modal(document.getElementById('modalPreviewFoto'));
                myModal.show();
            });

            // ==========================================
            // SCRIPT TOMBOL EDIT & TARIK DATA
            // ==========================================
            $jq('body').on('click', '.btn-edit', function() {
                let id = $jq(this).data('id');
                $jq('#edit_nama').val($jq(this).data('nama'));
                $jq('#edit_kapasitas').val($jq(this).data('kapasitas'));
                $jq('#edit_fasilitas').val($jq(this).data('fasilitas'));

                // Menyedot data harga dan langsung di-format pakai titik
                $jq('#edit_harga_5_jam').val(formatRupiah(String($jq(this).data('harga-5-jam') || 0)));
                $jq('#edit_harga_1_hari').val(formatRupiah(String($jq(this).data('harga-1-hari') || 0)));
                $jq('#edit_harga_3_hari').val(formatRupiah(String($jq(this).data('harga-3-hari') || 0)));
                $jq('#edit_harga_1_minggu').val(formatRupiah(String($jq(this).data('harga-1-minggu') ||
                0)));

                $jq('#formEditRuangan').attr('action', '/ruangan/' + id);

                var modalEdit = bootstrap.Modal.getOrCreateInstance(document.getElementById('modalEdit'));
                modalEdit.show();
            });

            // ==========================================
            // ENGINE AUTO-FORMAT RUPIAH
            // ==========================================
            // Fungsi inti untuk memberi titik
            function formatRupiah(angka) {
                let number_string = angka.replace(/[^,\d]/g, '').toString(),
                    split = number_string.split(','),
                    sisa = split[0].length % 3,
                    rupiah = split[0].substr(0, sisa),
                    ribuan = split[0].substr(sisa).match(/\d{3}/gi);

                if (ribuan) {
                    separator = sisa ? '.' : '';
                    rupiah += separator + ribuan.join('.');
                }
                return rupiah;
            }

            // Terapkan efek titik secara real-time saat Admin mengetik
            $jq('body').on('keyup', '.input-rupiah', function() {
                $jq(this).val(formatRupiah($jq(this).val()));
            });

            // Trik Licik: Buang kembali semua titik tepat sebelum form disubmit ke Laravel
            // Agar database (MySQL) tidak error karena menerima huruf string
            $jq('form').on('submit', function() {
                $jq('.input-rupiah').each(function() {
                    let nilaiAsli = $jq(this).val().replace(/\./g, ''); // Hapus semua titik
                    $jq(this).val(nilaiAsli);
                });
            });
        });
    </script>
@endsection
