@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card shadow-sm border-0" style="border-radius: 15px;">
                <div class="card-header bg-primary text-white" style="border-radius: 15px 15px 0 0;">
                    <h5 class="card-title mb-0 fw-bold"><i class="fas fa-user-cog me-2"></i> Pengaturan Profil Akun</h5>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-4 text-center">
                            @if (auth()->user()->foto)
                                <img src="{{ asset(auth()->user()->foto) }}" alt="Foto Profil"
                                    class="rounded-circle shadow-sm mb-3 border border-3 border-primary" width="120"
                                    height="120" style="object-fit: cover;">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=0d6efd&color=fff&rounded=true&bold=true&size=120"
                                    alt="Foto Profil" class="rounded-circle shadow-sm mb-3" width="120" height="120">
                            @endif

                            <div>
                                <label for="foto" class="form-label fw-bold text-secondary">Ganti Foto Profil</label>
                                <input class="form-control form-control-sm mx-auto" type="file" id="foto"
                                    name="foto" style="max-width: 300px;" accept="image/*">
                                <small class="text-muted d-block mt-1">Format: JPG, PNG (Maks: 2MB)</small>
                            </div>
                        </div>

                        <hr class="text-muted mb-4">

                        <div class="mb-3">
                            <label class="form-label fw-bold text-secondary">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-secondary">Alamat Email (Untuk Login)</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>

                        <hr class="text-muted mb-4">

                        <h6 class="fw-bold text-primary mb-3"><i class="fas fa-lock me-2"></i> Ubah Kata Sandi (Opsional)
                        </h6>
                        <p class="text-muted small mb-3">Kosongkan kolom di bawah ini jika Anda tidak ingin mengubah kata
                            sandi saat ini.</p>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-secondary">Kata Sandi Baru</label>
                                <input type="password" name="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    placeholder="Minimal 8 karakter">
                                @error('password')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold text-secondary">Ulangi Kata Sandi Baru</label>
                                <input type="password" name="password_confirmation" class="form-control"
                                    placeholder="Ketik ulang sandi baru">
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">
                                <i class="fas fa-save me-2"></i> Simpan Perubahan
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
