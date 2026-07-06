<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'e-Rapor Al-Huda')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            background: #f5f7fb;
            font-family: 'Segoe UI', sans-serif;
        }

        .main-content {
            min-height: 100vh;
        }

        .dashboard-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, .05);
            transition: .3s;
        }

        .dashboard-card:hover {
            transform: translateY(-3px);
        }
    </style>
</head>

<body>

    <div class="d-flex">

        @include('components.sidebar')

        <div class="flex-grow-1 main-content">

            @include('components.navbar')

            <div class="p-4">

                <div class="mb-4">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <h3 class="fw-bold mb-1">
                                @yield('page-title')
                            </h3>

                            <nav aria-label="breadcrumb">

                                <ol class="breadcrumb mb-0">

                                    @yield('breadcrumb')

                                </ol>

                            </nav>

                        </div>

                    </div>

                </div>

                @yield('content')

            </div>

        </div>

    </div>
@stack('scripts')
</body>

</html>