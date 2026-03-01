<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Booking Ruangan' }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">

    <style>
        /* Styling Navbar Modern */
        .navbar-custom {
            background-color: #ffffff;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04);
            padding: 12px 0;
            font-family: 'Poppins', sans-serif;
        }

        .navbar-brand {
            font-weight: 700;
            color: #333 !important;
            font-size: 1.4rem;
            letter-spacing: 0.5px;
        }

        .logo-icon {
            width: 38px;
            height: 38px;
            background: linear-gradient(135deg, #0d6efd, #0dcaf0);
        }

        .nav-item .nav-link {
            font-weight: 500;
            color: #666;
            padding: 10px 18px !important;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .nav-item .nav-link:hover,
        .nav-item .nav-link.active {
            color: #0d6efd;
            background-color: rgba(13, 110, 253, 0.08);
        }

        .btn-dashboard {
            background: linear-gradient(135deg, #0d6efd, #0dcaf0);
            box-shadow: 0 4px 10px rgba(13, 110, 253, 0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .btn-dashboard:hover {
            color: #ffffff !important;
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(13, 110, 253, 0.4);
        }

        .hover-translate-y {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .hover-translate-y:hover {
            transform: translateY(-3px);
        }

        .hover-primary:hover {
            color: #0d6efd !important;
            background-color: rgba(13, 110, 253, 0.05);
        }

        .transition-all {
            transition: all 0.3s ease;
        }

        .nav-item .nav-link.active {
            background: linear-gradient(135deg, #007bff, #00d2ff) !important;
            color: #ffffff !important;
            border: none !important;
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3) !important;
            font-weight: 700 !important;
            padding-left: 20px !important;
            padding-right: 20px !important;
            border-radius: 50rem !important;
            transform: translateY(0);
        }

        .nav-link {
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1) !important;
        }

        .nav-link:not(.active):hover {
            transform: translateY(-4px) !important;
            background-color: #f8f9fa !important;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.08) !important;
            color: #0d6efd !important;
        }

        .nav-link.active:hover {
            transform: translateY(-3px) !important;
            box-shadow: 0 6px 15px rgba(13, 110, 253, 0.5) !important;
        }

        .btn-hover-animasi {
            transition: all 0.3s ease-in-out !important;
        }

        .btn-hover-animasi:hover {
            transform: scale(1.08) translateY(-2px);
            box-shadow: 0 6px 15px rgba(220, 53, 69, 0.4) !important;
        }
    </style>
    @stack('styles')
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-custom sticky-top">
        <div class="container-fluid px-4">
            <a class="navbar-brand d-flex align-items-center gap-2" href="/">
                <div
                    class="logo-icon bg-primary text-white rounded p-2 d-flex justify-content-center align-items-center">
                    <i class="fas fa-building"></i>
                </div>
                SmartBooking
            </a>

            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 gap-2 align-items-center">
                    <li class="nav-item">
                        <a class="nav-link fw-bold px-3 py-2 rounded-pill transition-all {{ request()->routeIs('booking.index') ? 'active bg-primary text-white shadow-sm' : 'text-secondary hover-primary' }}"
                            href="{{ route('booking.index') }}">
                            <i class="fas fa-calendar-alt me-1"></i> Kalender Ruangan
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('ruangan.index') ? 'active fw-bold text-primary' : '' }}"
                            href="{{ route('ruangan.index') }}">
                            <i class="fas fa-door-open me-1"></i> Data Ruangan
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link fw-bold px-3 py-2 rounded-pill transition-all {{ request()->routeIs('booking.monitoring') ? 'active bg-primary text-white shadow-sm' : 'text-secondary hover-primary' }}"
                            href="{{ route('booking.monitoring') }}">
                            <i class="fas fa-desktop me-1"></i> Monitoring Real-Time
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link fw-bold px-3 py-2 rounded-pill transition-all {{ request()->routeIs('booking.checkin') ? 'active bg-primary text-white shadow-sm' : 'text-secondary hover-primary' }}"
                            href="{{ route('booking.checkin') }}">
                            <i class="fas fa-qrcode me-1"></i> Check-In Ruangan
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link fw-bold px-3 py-2 rounded-pill transition-all {{ request()->is('dashboard*') ? 'active bg-primary text-white shadow-sm' : 'text-secondary hover-primary' }}"
                            href="{{ url('/dashboard') }}">
                            <i class="fas fa-chart-pie me-1"></i> Dashboard Admin
                        </a>
                    </li>

                    @auth
                        <li class="nav-item dropdown ms-3">
                            <a class="nav-link dropdown-toggle d-flex align-items-center bg-white rounded-pill px-3 py-1 shadow-sm border transition-all hover-primary"
                                href="#" id="navbarDropdownProfile" role="button" data-bs-toggle="dropdown"
                                aria-expanded="false">

                                <img src="{{ auth()->user()->foto ? asset(auth()->user()->foto) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=0d6efd&color=fff&rounded=true&bold=true' }}"
                                    alt="Profile" class="rounded-circle me-2 shadow-sm" width="32" height="32"
                                    style="object-fit: cover;">

                                <span
                                    class="fw-bold text-dark me-1 d-none d-sm-inline-block">{{ auth()->user()->name }}</span>
                            </a>

                            <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-3 p-2"
                                aria-labelledby="navbarDropdownProfile" style="border-radius: 15px; min-width: 240px;">

                                <li class="px-3 py-3 text-center">
                                    <span class="d-block fw-bold text-dark">{{ auth()->user()->name }}</span>
                                    <small class="text-muted">{{ auth()->user()->email }}</small>
                                </li>

                                <li>
                                    <hr class="dropdown-divider mb-2 mx-2">
                                </li>

                                <li class="mb-1 px-1">
                                    <a class="dropdown-item py-2 fw-medium rounded-pill {{ request()->routeIs('profile.index') ? 'active bg-primary text-white shadow-sm' : 'text-secondary hover-primary' }}"
                                        href="{{ route('profile.index') }}">
                                        <i
                                            class="fas fa-user-edit me-2 {{ request()->routeIs('profile.index') ? 'text-white' : 'text-primary' }}"></i>
                                        Edit Profil
                                    </a>
                                </li>

                                <li class="px-1">
                                    <a class="dropdown-item py-2 fw-medium rounded-pill {{ request()->routeIs('users.*') ? 'active bg-primary text-white shadow-sm' : 'text-secondary hover-primary' }}"
                                        href="{{ route('users.index') }}">
                                        <i
                                            class="fas fa-users-cog me-2 {{ request()->routeIs('users.*') ? 'text-white' : 'text-primary' }}"></i>
                                        Kelola Petugas
                                    </a>
                                </li>

                                <li>
                                    <hr class="dropdown-divider mt-2 mb-2 mx-2">
                                </li>

                                <li class="px-1">
                                    <a class="dropdown-item py-2 fw-bold text-danger rounded-pill hover-danger"
                                        href="#" id="btn-animasi-logout">
                                        <i class="fas fa-sign-out-alt me-2"></i> Logout Keluar
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        @yield('content')
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

    @stack('scripts')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let btnLogout = document.getElementById('btn-animasi-logout');

            if (btnLogout) {
                btnLogout.addEventListener('click', function(e) {
                    e.preventDefault();

                    Swal.fire({
                        title: 'Keluar dari Sistem?',
                        text: 'Anda harus login kembali untuk mengakses halaman ini.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc3545',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: '<i class="fas fa-sign-out-alt"></i> Ya, Logout',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire({
                                title: 'Memproses Keluar...',
                                allowOutsideClick: false,
                                showConfirmButton: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });

                            setTimeout(() => {
                                document.getElementById('logout-form').submit();
                            }, 500);
                        }
                    });
                });
            }
        });
    </script>
</body>

</html>
