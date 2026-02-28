@extends('layouts.app')

@push('styles')
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet">
    <style>
        /* Desain Tabel Modern (Menyamakan dengan Monitoring) */
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

        .table-modern tbody td {
            padding: 16px 20px;
            vertical-align: middle;
            border-bottom: 1px solid #edf2f9;
            color: #4b5563;
            font-size: 0.9rem;
        }

        .btn-action {
            width: 36px;
            height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-action-info {
            background-color: rgba(13, 202, 240, 0.1);
            color: #0dcaf0;
        }

        .btn-action-info:hover {
            background-color: #0dcaf0;
            color: white;
            transform: translateY(-3px);
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card shadow-sm border-0 mb-4" style="border-radius: 15px;">
            <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center pt-4 pb-3 px-4"
                style="border-radius: 15px 15px 0 0;">
                <h5 class="card-title mb-0 fw-bold text-dark"><i class="fas fa-users text-primary me-2"></i> Daftar Petugas
                    & Admin</h5>
                <button class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm" data-bs-toggle="modal"
                    data-bs-target="#modalTambah">
                    <i class="fas fa-plus me-1"></i> Tambah Petugas
                </button>
            </div>

            <div class="card-body p-4 pt-0">
                <div class="table-responsive">
                    <table id="tableUsers" class="table table-modern align-middle w-100">
                        <thead>
                            <tr>
                                <th width="5%" class="text-center">No</th>
                                <th>Nama Lengkap</th>
                                <th>Alamat Email</th>
                                <th>Terdaftar Sejak</th>
                                <th width="15%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow" style="border-radius: 15px;">
                <div class="modal-header bg-primary text-white" style="border-radius: 15px 15px 0 0;">
                    <h5 class="modal-title fw-bold"><i class="fas fa-user-plus me-2"></i> Tambah Petugas Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form action="{{ route('users.store') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold text-secondary">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold text-secondary">Alamat Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label fw-bold text-secondary">Kata Sandi Awal</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer bg-light" style="border-radius: 0 0 15px 15px;">
                        <button type="button" class="btn btn-secondary rounded-pill px-4"
                            data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalEdit" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow" style="border-radius: 15px;">
                <div class="modal-header bg-info text-white" style="border-radius: 15px 15px 0 0;">
                    <h5 class="modal-title fw-bold"><i class="fas fa-edit me-2"></i> Edit Data Petugas</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form id="formEditPetugas" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold text-secondary">Nama Lengkap</label>
                            <input type="text" id="edit_name" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold text-secondary">Alamat Email</label>
                            <input type="email" id="edit_email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label fw-bold text-secondary">Kata Sandi Baru (Opsional)</label>
                            <input type="password" name="password" class="form-control"
                                placeholder="Isi untuk reset sandi">
                        </div>
                    </div>
                    <div class="modal-footer bg-light" style="border-radius: 0 0 15px 15px;">
                        <button type="button" class="btn btn-secondary rounded-pill px-4"
                            data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-info text-white rounded-pill px-4 shadow-sm">Simpan
                            Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#tableUsers').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('users.index') }}',
                language: {
                    search: "",
                    searchPlaceholder: "🔍 Cari nama / email...",
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
                        data: 'name',
                        name: 'name',
                        className: 'fw-bold text-dark'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'terdaftar',
                        name: 'created_at'
                    },
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

        // Fungsi untuk melempar data dari DataTables ke Modal Edit
        function editPetugas(id, name, email) {
            // Isi inputan form
            $('#edit_name').val(name);
            $('#edit_email').val(email);

            // Ubah tujuan URL Form ke route Update sesuai ID
            let url = `/users/${id}`;
            $('#formEditPetugas').attr('action', url);

            // Munculkan Modal
            $('#modalEdit').modal('show');
        }
    </script>
@endpush
