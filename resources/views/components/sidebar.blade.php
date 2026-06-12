<div class="bg-dark text-white p-3" style="width:260px; min-height:100vh;">

    <div class="text-center mb-5">

        <h4 class="fw-bold">
            📚 e-Rapor
        </h4>

        <small class="text-secondary">
            Al-Huda
        </small>

    </div>

    @if(auth()->user()->role == 'admin')

        <a href="{{ route('admin.dashboard') }}"
            class="menu-link {{ request()->routeIs('admin.dashboard') ? 'active-menu' : '' }}">
            <i class="bi bi-grid-fill"></i>
            Dashboard
        </a>

        <a href="{{ route('admin.users.index') }}"
            class="menu-link {{ request()->routeIs('admin.users.*') ? 'active-menu' : '' }}">
            <i class="bi bi-people-fill"></i>
            User
        </a>

        <a href="#" class="menu-link">
            <i class="bi bi-journal-check"></i>
            Nilai Guru
        </a>

    @elseif(auth()->user()->role == 'yayasan')

        <a href="#" class="menu-link">
            <i class="bi bi-grid-fill"></i>
            Dashboard
        </a>

        <a href="#" class="menu-link">
            <i class="bi bi-patch-check-fill"></i>
            Verifikasi Nilai
        </a>

    @elseif(auth()->user()->role == 'guru')

        <a href="#" class="menu-link">
            <i class="bi bi-grid-fill"></i>
            Dashboard
        </a>

        <a href="#" class="menu-link">
            <i class="bi bi-journal-text"></i>
            Nilai Saya
        </a>

    @endif

    <hr class="border-secondary">

    <form method="POST" action="{{ route('logout') }}">

        @csrf

        <button class="btn btn-danger w-100 rounded-3">

            <i class="bi bi-box-arrow-right"></i>
            Logout

        </button>

    </form>

</div>

<style>
    .menu-link {
        display: flex;
        align-items: center;
        gap: 10px;
        color: #d1d5db;
        text-decoration: none;
        padding: 12px 15px;
        border-radius: 10px;
        margin-bottom: 8px;
        transition: .3s;
    }

    .menu-link:hover {
        background: #2563eb;
        color: white;
    }

    .active-menu {
        background: #2563eb;
        color: white !important;
    }
</style>