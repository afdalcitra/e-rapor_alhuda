<!doctype html>
<html>

<head>
    <title>eRapor Al-Huda | Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container">

        <div class="row vh-100 justify-content-center align-items-center">

            <div class="col-md-4">

                <div class="card shadow">

                    <div class="card-body">

                        <h3 class="text-center mb-4">
                            e-Rapor Al-Huda
                        </h3>

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="mb-3">
                                <label>NIPY</label>

                                <input type="text" name="nipy" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label>Password</label>

                                <input type="password" name="password" class="form-control" required>
                            </div>

                            @error('nipy')
                                <div class="alert alert-danger">
                                    {{ $message }}
                                </div>
                            @enderror

                            <button type="submit" class="btn btn-primary w-100">

                                Login
                            </button>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

</body>

</html>