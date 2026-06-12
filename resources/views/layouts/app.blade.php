<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'e-Rapor Al-Huda')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div class="d-flex">

        @include('components.sidebar')

        <div class="flex-grow-1">

            @include('components.navbar')

            <div class="container-fluid p-4">
                @yield('content')
            </div>

        </div>

    </div>

</body>

</html>