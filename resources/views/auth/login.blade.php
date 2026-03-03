<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Manajemen Booking Ruangan</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* ========================================== */
        /* DESAIN BACKGROUND & ANIMASI MODERN         */
        /* ========================================== */
        body {
            /* Background gradasi biru elegan yang bergerak pelan */
            background: linear-gradient(-45deg, #0f2027, #203a43, #2c5364, #007bff);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow: hidden;
        }

        @keyframes gradientBG {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        /* ========================================== */
        /* DESAIN KARTU LOGIN (GLASSMORPHISM)         */
        /* ========================================== */
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.2);
            overflow: hidden;
            width: 100%;
            max-width: 450px;
            transform: translateY(20px);
            opacity: 0;
            animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        @keyframes slideUp {
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .login-header {
            background: linear-gradient(135deg, #007bff, #00d2ff);
            padding: 30px 20px;
            text-align: center;
            color: white;
        }

        .login-icon {
            font-size: 3rem;
            margin-bottom: 10px;
            text-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        /* ========================================== */
        /* DESAIN FORM & INPUT FLOAT                  */
        /* ========================================== */
        .form-floating>.form-control {
            border-radius: 12px;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
        }

        .form-floating>.form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 4px rgba(0, 123, 255, 0.1);
        }

        .btn-login {
            background: linear-gradient(135deg, #007bff, #00d2ff);
            border: none;
            border-radius: 12px;
            padding: 14px;
            font-weight: 700;
            letter-spacing: 1px;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 123, 255, 0.4);
            color: white;
        }

        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6c757d;
            z-index: 10;
        }
    </style>
</head>

<body>

    <div class="container d-flex justify-content-center px-3">
        <div class="login-card">

            <div class="login-header">
                <i class="fas fa-fingerprint login-icon"></i>
                <h3 class="fw-bold mb-1">Portal Admin</h3>
                <p class="mb-0 opacity-75 small">Sistem Informasi Manajemen Booking Ruangan</p>
            </div>

            <div class="p-4 p-md-5">

                @if ($errors->any())
                    <div class="alert alert-danger rounded-3 text-sm pb-0">
                        <ul class="mb-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="form-floating mb-4 position-relative">
                        <input type="email" class="form-control" id="email" name="email"
                            value="{{ old('email') }}" placeholder="name@example.com" required autofocus>
                        <label for="email"><i class="fas fa-envelope me-2 text-muted"></i>Alamat Email</label>
                    </div>

                    <div class="form-floating mb-4 position-relative">
                        <input type="password" class="form-control" id="password" name="password"
                            placeholder="Password" required>
                        <label for="password"><i class="fas fa-lock me-2 text-muted"></i>Kata Sandi</label>
                        <i class="fas fa-eye password-toggle" id="togglePassword"></i>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label text-muted small fw-medium" for="remember">
                                Ingat Saya
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 btn-login text-white">
                        <i class="fas fa-sign-in-alt me-2"></i> MASUK SISTEM
                    </button>

                </form>
            </div>

        </div>
    </div>

    <script>
        document.getElementById('togglePassword').addEventListener('click', function(e) {
            const passwordInput = document.getElementById('password');
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.classList.toggle('fa-eye-slash');
        });
    </script>

</body>

</html>
