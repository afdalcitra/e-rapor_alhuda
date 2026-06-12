@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('breadcrumb')
    <li class="breadcrumb-item">Home</li>
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')

    <style>
        .stat-card {
            background: #fff;
            border-radius: 10px;
            padding: 14px 16px;
        }

        .stat-icon {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 17px;
            flex-shrink: 0;
        }

        .ic-purple {
            background: #EEEDFE;
            color: #534AB7;
        }

        .ic-amber {
            background: #FAEEDA;
            color: #854F0B;
        }

        .ic-teal {
            background: #E1F5EE;
            color: #0F6E56;
        }

        .ic-coral {
            background: #FAECE7;
            color: #993C1D;
        }

        .act-row {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 9px 0;
            border-bottom: 1px solid #F3F4F6;
        }

        .act-row:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .act-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            margin-top: 5px;
            flex-shrink: 0;
        }

        .quick-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 8px;
            border: 1px solid #F3F4F6;
            margin-bottom: 8px;
            text-decoration: none;
            color: inherit;
            transition: background .15s;
        }

        .quick-btn:last-child {
            margin-bottom: 0;
        }

        .quick-btn:hover {
            background: #F9FAFB;
        }

        .quick-btn-icon {
            width: 30px;
            height: 30px;
            border-radius: 7px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            flex-shrink: 0;
        }
    </style>

    {{-- Header --}}
    <div class="mb-4">
        <h5 class="fw-semibold mb-1">
            Selamat datang, <span class="uppercase">{{ auth()->user()->nama_lengkap }}</span>
        </h5>
        <p class="text-muted small mb-0">Berikut ringkasan kondisi sistem hari ini.</p>
        <p class="text-muted" style="font-size:11px;">{{ now()->translatedFormat('l, d F Y') }}</p>
    </div>

    {{-- Stat cards --}}
    <div class="row g-3 mb-4">

        <!-- Total Admin -->
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-1" style="font-size:12px;">Total Admin</p>
                        <h3 class="fw-semibold mb-0">{{ $totalAdmin ?? 0 }}</h3>
                    </div>
                    <div class="stat-icon ic-coral">
                        <i class="bi bi-shield-check"></i>
                    </div>
                </div>
                <p class="text-muted mt-2 mb-0" style="font-size:11px;">{{ $adminTidakAktif ?? 0 }} tidak aktif</p>
            </div>
        </div>

        <!-- Total Guru -->
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-1" style="font-size:12px;">Total Guru</p>
                        <h3 class="fw-semibold mb-0">{{ $totalGuru ?? 0 }}</h3>
                    </div>
                    <div class="stat-icon ic-purple">
                        <i class="bi bi-person-workspace"></i>
                    </div>
                </div>
                <p class="text-muted mt-2 mb-0" style="font-size:11px;">{{ $guruTidakAktif ?? 0 }} tidak aktif</p>
            </div>
        </div>

        <!-- Total Yayasan -->
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-1" style="font-size:12px;">Total Yayasan</p>
                        <h3 class="fw-semibold mb-0">{{ $totalYayasan ?? 0 }}</h3>
                    </div>
                    <div class="stat-icon ic-teal">
                        <i class="bi bi-building"></i>
                    </div>
                </div>
                <p class="text-muted mt-2 mb-0" style="font-size:11px;">Semua aktif</p>
            </div>
        </div>

        <!-- Menunggu Verifikasi -->
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-1" style="font-size:12px;">Menunggu Verifikasi</p>
                        <h3 class="fw-semibold mb-0">{{ $menungguVerifikasi ?? 0 }}</h3>
                    </div>
                    <div class="stat-icon ic-amber">
                        <i class="bi bi-clock-history"></i>
                    </div>
                </div>
                <p class="text-muted mt-2 mb-0" style="font-size:11px;">Perlu ditinjau segera</p>
            </div>
        </div>
    </div>

    {{-- Bottom row --}}
    <div class="row g-3">

        {{-- Aktivitas Terbaru --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h6 class="fw-semibold mb-3">
                        <i class="bi bi-activity me-1"></i> Aktivitas Terbaru
                    </h6>

                    @forelse($aktivitas ?? [] as $item)
                        <div class="act-row">
                            <div class="act-dot" style="background: {{ $item['warna'] ?? '#9CA3AF' }}"></div>
                            <div>
                                <div style="font-size:13px;">{{ $item['pesan'] }}</div>
                                <div class="text-muted" style="font-size:11px;">{{ $item['waktu'] }}</div>
                            </div>
                        </div>
                    @empty
                        {{-- Fallback statis jika $aktivitas belum diimplementasi --}}
                        <div class="act-row">
                            <div class="act-dot" style="background:#7F77DD"></div>
                            <div>
                                <div style="font-size:13px;">Belum ada aktivitas tercatat.</div>
                                <div class="text-muted" style="font-size:11px;">–</div>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Akses Cepat --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h6 class="fw-semibold mb-3">
                        <i class="bi bi-lightning-charge me-1"></i> Akses Cepat
                    </h6>

                    <a href="{{ route('admin.users.index') }}" class="quick-btn">
                        <div class="quick-btn-icon ic-teal"><i class="bi bi-people"></i></div>
                        <span style="font-size:13px; flex:1;">Manajemen guru</span>
                        <i class="bi bi-chevron-right text-muted" style="font-size:12px;"></i>
                    </a>

                    <a href="{{ route('admin.users.create') }}" class="quick-btn">
                        <div class="quick-btn-icon ic-purple"><i class="bi bi-person-plus"></i></div>
                        <span style="font-size:13px; flex:1;">Tambah user baru</span>
                        <i class="bi bi-chevron-right text-muted" style="font-size:12px;"></i>
                    </a>

                    <a href="#" class="quick-btn">
                        <div class="quick-btn-icon ic-amber"><i class="bi bi-clock-history"></i></div>
                        <span style="font-size:13px; flex:1;">Tinjau verifikasi</span>
                        <i class="bi bi-chevron-right text-muted" style="font-size:12px;"></i>
                    </a>

                    {{-- Tambah route lain sesuai kebutuhan --}}
                    <a href="#" class="quick-btn">
                        <div class="quick-btn-icon ic-coral"><i class="bi bi-bar-chart-line"></i></div>
                        <span style="font-size:13px; flex:1;">Lihat laporan</span>
                        <i class="bi bi-chevron-right text-muted" style="font-size:12px;"></i>
                    </a>
                </div>
            </div>
        </div>

    </div>

@endsection