<!doctype html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>e-Rapor Al-Huda | Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #0f172a, #1e3a8a);
            font-family: 'Segoe UI', sans-serif;
        }

        .login-card {
            border: none;
            border-radius: 24px;
            backdrop-filter: blur(10px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, .15);
        }

        .logo-circle {
            width: 80px;
            height: 80px;
            background: #2563eb;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: auto;
            color: white;
            font-size: 36px;
        }

        .form-control {
            height: 50px;
            border-radius: 12px;
        }

        .input-group-text {
            border-radius: 12px 0 0 12px;
        }

        .btn-login {
            height: 50px;
            border-radius: 12px;
            font-weight: 600;
        }

        .welcome-panel {
            color: white;
        }

        .welcome-panel h1 {
            font-weight: 700;
        }
    </style>
</head>

<body>

    <div class="container-fluid">

        <div class="row min-vh-100">

            <!-- Kiri -->
            <div class="col-lg-7 d-none d-lg-flex align-items-center justify-content-center">

                <div class="welcome-panel text-center">

                    <h1 class="display-4 mb-3">
                        e-Rapor Al-Huda
                    </h1>

                    <p class="lead">
                        Sistem Pengelolaan Nilai dan Rapor Terintegrasi
                    </p>

                    <p class="text-light opacity-75">
                        TK • SD • SMP Al-Huda
                    </p>

                </div>

            </div>

            <!-- Kanan -->
            <div class="col-lg-5 d-flex align-items-center justify-content-center">

                <div class="card login-card bg-white p-4" style="width:100%; max-width:450px;">

                    <div class="card-body">

                        <div class="logo-circle mb-4">
                            <i class="bi bi-journal-bookmark-fill"></i>
                        </div>

                        <h3 class="text-center fw-bold">
                            Selamat Datang
                        </h3>

                        <p class="text-center text-muted mb-4">
                            Login menggunakan akun Anda
                        </p>

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="mb-3">

                                <label class="form-label">
                                    NIPY
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        <i class="bi bi-person"></i>
                                    </span>

                                    <input type="text" name="nipy" class="form-control" placeholder="Masukkan NIPY"
                                        required>

                                </div>

                            </div>

                            <div class="mb-3">

                                <label class="form-label">
                                    Password
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        <i class="bi bi-lock"></i>
                                    </span>

                                    <input type="password" name="password" class="form-control"
                                        placeholder="Masukkan Password" required>

                                </div>

                            </div>

                            @error('nipy')
                                <div class="alert alert-danger">
                                    {{ $message }}
                                </div>
                            @enderror

                            <button type="submit" class="btn btn-primary btn-login w-100">

                                <i class="bi bi-box-arrow-in-right me-2"></i>
                                Login

                            </button>

                        </form>

                        <div class="text-center mt-4">

                            <small class="text-muted">
                                © {{ date('Y') }} Yayasan Al-Huda
                            </small>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</body>

</html>