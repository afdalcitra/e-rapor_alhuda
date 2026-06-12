<div class="bg-dark text-white vh-100 p-3" style="width:250px;">

    <h4 class="mb-4">
        e-Rapor
    </h4>

    @if(auth()->user()->role == 'admin')

        <a href="#" class="d-block text-white text-decoration-none mb-3">
            Dashboard
        </a>

        <a href="#" class="d-block text-white text-decoration-none mb-3">
            User
        </a>

        <a href="#" class="d-block text-white text-decoration-none mb-3">
            Nilai Guru
        </a>

    @elseif(auth()->user()->role == 'yayasan')

        <a href="#" class="d-block text-white text-decoration-none mb-3">
            Dashboard
        </a>

        <a href="#" class="d-block text-white text-decoration-none mb-3">
            Verifikasi Nilai
        </a>

    @elseif(auth()->user()->role == 'guru')

        <a href="#" class="d-block text-white text-decoration-none mb-3">
            Dashboard
        </a>

        <a href="#" class="d-block text-white text-decoration-none mb-3">
            Nilai Saya
        </a>

    @endif

    <form method="POST" action="{{ route('logout') }}" class="mt-5">

        @csrf

        <button class="btn btn-danger w-100">

            Logout

        </button>

    </form>

</div>