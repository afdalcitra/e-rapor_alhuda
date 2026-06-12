@extends('layouts.app')

@section('title', 'Manajemen User')

@section('content')

    <style>
        .avatar-circle {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 600;
            flex-shrink: 0;
        }

        .av-admin {
            background: #EEF2FF;
            color: #4F46E5;
        }

        .av-yayasan {
            background: #F0FDF4;
            color: #15803D;
        }

        .av-guru {
            background: #FFF7ED;
            color: #C2410C;
        }

        .tab-btn {
            border: none;
            background: none;
            padding: 10px 18px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            color: #6B7280;
            border-bottom: 2px solid transparent;
            transition: color .15s;
        }

        .tab-btn.active {
            color: #6366F1;
            border-bottom-color: #6366F1;
        }

        .tab-btn:hover:not(.active) {
            color: #111827;
        }

        .tab-panel {
            display: none;
        }

        .tab-panel.active {
            display: block;
        }

        .user-row {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 0;
            border-bottom: 1px solid #F3F4F6;
        }

        .user-row:last-child {
            border-bottom: none;
        }

        .user-name {
            font-size: 14px;
            font-weight: 500;
            color: #111827;
        }

        .user-meta {
            font-size: 12px;
            color: #9CA3AF;
            margin-top: 1px;
        }

        .badge-pill {
            display: inline-flex;
            align-items: center;
            padding: 3px 10px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 500;
        }

        .badge-aktif {
            background: #DCFCE7;
            color: #15803D;
        }

        .badge-nonaktif {
            background: #FEE2E2;
            color: #B91C1C;
        }

        .btn-edit-modern {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 12px;
            color: #6366F1;
            background: #EEF2FF;
            border: none;
            border-radius: 6px;
            padding: 5px 12px;
            cursor: pointer;
            text-decoration: none;
            white-space: nowrap;
        }

        .btn-edit-modern:hover {
            background: #E0E7FF;
            color: #4F46E5;
        }

        .stat-card {
            background: #F9FAFB;
            border-radius: 10px;
            padding: 14px 16px;
        }

        .stat-label {
            font-size: 12px;
            color: #6B7280;
            margin-bottom: 4px;
        }

        .stat-num {
            font-size: 22px;
            font-weight: 600;
            color: #111827;
        }

        .stat-dot {
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            margin-right: 6px;
        }
    </style>

    @section('breadcrumb')

        <li class="breadcrumb-item">
            Home
        </li>

        <li class="breadcrumb-item active">
            User
        </li>

    @endsection

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h4 class="mb-0 fw-semibold">Manajemen User</h4>
            <small class="text-muted">Kelola akun Admin, Yayasan, dan Guru</small>
        </div>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary d-flex align-items-center gap-1">
            <span>+</span> Tambah User
        </a>
    </div>

    {{-- Stat cards --}}
    <div class="row g-3 mb-4">
        <div class="col-4">
            <div class="stat-card">
                <div class="stat-label"><span class="stat-dot" style="background:#6366F1"></span>Admin</div>
                <div class="stat-num">{{ $admins->count() }}</div>
            </div>
        </div>
        <div class="col-4">
            <div class="stat-card">
                <div class="stat-label"><span class="stat-dot" style="background:#10B981"></span>Yayasan</div>
                <div class="stat-num">{{ $yayasans->count() }}</div>
            </div>
        </div>
        <div class="col-4">
            <div class="stat-card">
                <div class="stat-label"><span class="stat-dot" style="background:#F59E0B"></span>Guru</div>
                <div class="stat-num">{{ $gurus->count() }}</div>
            </div>
        </div>
    </div>

    {{-- Tab bar --}}
    <div class="d-flex gap-1 border-bottom mb-3">
        <button class="tab-btn active" onclick="switchTab('admin', this)">Admin</button>
        <button class="tab-btn" onclick="switchTab('yayasan', this)">Yayasan</button>
        <button class="tab-btn" onclick="switchTab('guru', this)">Guru</button>
    </div>

    {{-- Tab: Admin --}}
    <div id="tab-admin" class="tab-panel active card border-0 shadow-sm px-3">
        @forelse($admins as $user)
            <div class="user-row">
                <div class="avatar-circle av-admin">
                    {{ strtoupper(substr($user->nama_lengkap, 0, 1)) }}{{ strtoupper(substr(strrchr($user->nama_lengkap, ' '), 1, 1)) }}
                </div>
                <div class="flex-grow-1 overflow-hidden">
                    <div class="user-name">{{ $user->nama_lengkap }}</div>
                    <div class="user-meta">{{ $user->nipy }}</div>
                </div>
                <span class="badge-pill {{ $user->status == 'aktif' ? 'badge-aktif' : 'badge-nonaktif' }}">
                    {{ $user->status_label }}
                </span>
                <a href="{{ route('admin.users.edit', $user) }}" class="btn-edit-modern">Edit</a>
            </div>
        @empty
            <p class="text-center text-muted py-4 mb-0">Tidak ada data admin.</p>
        @endforelse
    </div>

    {{-- Tab: Yayasan --}}
    <div id="tab-yayasan" class="tab-panel card border-0 shadow-sm px-3">
        @forelse($yayasans as $user)
            <div class="user-row">
                <div class="avatar-circle av-yayasan">
                    {{ strtoupper(substr($user->nama_lengkap, 0, 1)) }}{{ strtoupper(substr(strrchr($user->nama_lengkap, ' '), 1, 1)) }}
                </div>
                <div class="flex-grow-1 overflow-hidden">
                    <div class="user-name">{{ $user->nama_lengkap }}</div>
                    <div class="user-meta">{{ $user->nipy }}</div>
                </div>
                <span class="badge-pill {{ $user->status == 'aktif' ? 'badge-aktif' : 'badge-nonaktif' }}">
                    {{ $user->status_label }}
                </span>
                <a href="{{ route('admin.users.edit', $user) }}" class="btn-edit-modern">Edit</a>
            </div>
        @empty
            <p class="text-center text-muted py-4 mb-0">Tidak ada data yayasan.</p>
        @endforelse
    </div>

    {{-- Tab: Guru --}}
    <div id="tab-guru" class="tab-panel card border-0 shadow-sm px-3">
        @forelse($gurus as $user)
            <div class="user-row">
                <div class="avatar-circle av-guru">
                    {{ strtoupper(substr($user->nama_lengkap, 0, 1)) }}{{ strtoupper(substr(strrchr($user->nama_lengkap, ' '), 1, 1)) }}
                </div>
                <div class="flex-grow-1 overflow-hidden">
                    <div class="user-name">{{ $user->nama_lengkap }}</div>
                    <div class="user-meta">{{ $user->nipy }}{{ $user->jabatan ? ' · ' . $user->jabatan : '' }}</div>
                </div>
                <span class="badge-pill {{ $user->status == 'aktif' ? 'badge-aktif' : 'badge-nonaktif' }}">
                    {{ $user->status_label }}
                </span>
                <a href="{{ route('admin.users.edit', $user) }}" class="btn-edit-modern">Edit</a>
            </div>
        @empty
            <p class="text-center text-muted py-4 mb-0">Tidak ada data guru.</p>
        @endforelse
    </div>

    <script>
        function switchTab(name, el) {
            document.querySelectorAll('.tab-btn').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
            el.classList.add('active');
            document.getElementById('tab-' + name).classList.add('active');
        }
    </script>

@endsection