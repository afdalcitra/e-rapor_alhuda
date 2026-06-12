<nav class="navbar navbar-expand-lg bg-white shadow-sm px-4">

    <div class="container-fluid">

        <span class="fw-bold fs-5">
            e-Rapor Al-Huda
        </span>

        <div class="d-flex align-items-center">

            <div class="text-end me-3">

                <div class="fw-semibold">
                    {{ auth()->user()->nama_lengkap }}
                </div>

                <small class="text-muted text-capitalize">
                    {{ auth()->user()->role }}
                </small>

            </div>

            <img src="https://ui-avatars.com/api/?name={{ auth()->user()->nama_lengkap }}" width="40"
                class="rounded-circle">

        </div>

    </div>

</nav>