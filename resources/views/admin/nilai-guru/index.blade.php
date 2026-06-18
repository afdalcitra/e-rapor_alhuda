@extends('layouts.app')

@section('title', 'Nilai Guru')

@section('content')

    <style>
        .filter-bar {
            display: flex;
            gap: 12px;
            align-items: center;
            flex-wrap: wrap;
            background: #F9FAFB;
            border-radius: 10px;
            padding: 12px 16px;
            margin-bottom: 20px;
        }

        .filter-bar label {
            font-size: 12px;
            color: #6B7280;
            margin-right: 4px;
        }

        .filter-bar select {
            font-size: 13px;
        }

        .summary-card {
            border-radius: 10px;
            padding: 14px 16px;
        }

        .summary-label {
            font-size: 12px;
            margin-bottom: 4px;
        }

        .summary-num {
            font-size: 24px;
            font-weight: 600;
        }

        .nav-tabs .nav-link {
            font-size: 13px;
            font-weight: 500;
        }

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
            background: #EEF2FF;
            color: #4F46E5;
        }

        .avatar-circle.belum {
            background: #FEF2F2;
            color: #B91C1C;
        }

        .row-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 0;
            border-bottom: 1px solid #F3F4F6;
        }

        .row-item:last-child {
            border-bottom: none;
        }

        .item-name {
            font-size: 14px;
            font-weight: 500;
            color: #111827;
        }

        .item-meta {
            font-size: 12px;
            color: #9CA3AF;
        }

        .badge-pill {
            display: inline-flex;
            align-items: center;
            padding: 3px 10px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 500;
            white-space: nowrap;
        }

        .b-draft {
            background: #F1EFE8;
            color: #444441;
        }

        .b-menunggu {
            background: #FEF3C7;
            color: #854F0B;
        }

        .b-disetujui {
            background: #DCFCE7;
            color: #15803D;
        }

        .b-ditolak {
            background: #FEE2E2;
            color: #B91C1C;
        }

        .b-predikat {
            background: #EEF2FF;
            color: #4F46E5;
        }

        .b-belum {
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

        .btn-input-modern {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 12px;
            color: #B91C1C;
            background: #FEF2F2;
            border: none;
            border-radius: 6px;
            padding: 5px 12px;
            cursor: pointer;
            text-decoration: none;
            white-space: nowrap;
        }

        .btn-input-modern:hover {
            background: #FEE2E2;
        }
    </style>

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h4 class="mb-0 fw-semibold">Nilai Guru</h4>
            <small class="text-muted">Kelola nilai rapor guru</small>
        </div>
        <a href="{{ route('admin.nilai-guru.create') }}" class="btn btn-primary d-flex align-items-center gap-1">
            <i class="bi bi-plus-circle"></i> Tambah Nilai
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Filter periode --}}
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
        <form method="GET" class="filter-bar mb-0">
            <div>
                <label>Tahun ajaran</label>
                <select name="tahun_ajaran" class="form-select form-select-sm d-inline-block w-auto"
                    onchange="this.form.submit()">
                    @foreach($daftarTahunAjaran as $ta)
                        <option value="{{ $ta }}" @selected($tahunAjaran == $ta)>{{ $ta }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label>Semester</label>
                <select name="semester" class="form-select form-select-sm d-inline-block w-auto"
                    onchange="this.form.submit()">
                    <option value="ganjil" @selected($semester == 'ganjil')>Ganjil</option>
                    <option value="genap" @selected($semester == 'genap')>Genap</option>
                </select>
            </div>
        </form>

        @if($tahunAjaran == $periodeAktif->tahun_ajaran && $semester == $periodeAktif->semester)
            <span class="badge-pill" style="background:#DCFCE7; color:#15803D; padding:6px 14px;">
                <i class="bi bi-check-circle"></i> Ini periode aktif
            </span>
        @else
            <form method="POST" action="{{ route('admin.nilai-guru.set-periode-aktif') }}">
                @csrf
                <input type="hidden" name="tahun_ajaran" value="{{ $tahunAjaran }}">
                <input type="hidden" name="semester" value="{{ $semester }}">
                <button type="submit" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-flag"></i> Jadikan periode aktif
                </button>
            </form>
        @endif
    </div>

    <p class="text-muted mb-4" style="font-size:12px;">
        Periode aktif sistem saat ini:
        <strong>{{ $periodeAktif->tahun_ajaran }} · {{ ucfirst($periodeAktif->semester) }}</strong>
        — ini yang dipakai di kartu "Belum Input Nilai" pada dashboard.
    </p>

    {{-- Summary cards --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="summary-card" style="background:#EAF3DE">
                <div class="summary-label" style="color:#3B6D11">Total guru aktif</div>
                <div class="summary-num" style="color:#27500A">{{ $totalGuruAktif }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="summary-card" style="background:#DCFCE7">
                <div class="summary-label" style="color:#15803D">Sudah diinput</div>
                <div class="summary-num" style="color:#15803D">{{ $guruSudahInput->count() }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="summary-card" style="background:#FEF2F2">
                <div class="summary-label" style="color:#B91C1C">Belum diinput</div>
                <div class="summary-num" style="color:#B91C1C">{{ $guruBelumInput->count() }}</div>
            </div>
        </div>
    </div>

    {{-- Tabs --}}
    <ul class="nav nav-tabs mb-3" id="nilaiTab">
        <li class="nav-item">
            <button class="nav-link active" type="button" onclick="switchNilaiTab('sudah', this)">
                Sudah diinput ({{ $guruSudahInput->count() }})
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link" type="button" onclick="switchNilaiTab('belum', this)">
                Belum diinput ({{ $guruBelumInput->count() }})
            </button>
        </li>
    </ul>

    <div class="tab-content">

        {{-- Tab: Sudah diinput --}}
        <div class="tab-pane show active" id="tab-sudah">
            <div class="card border-0 shadow-sm px-3">
                @forelse($guruSudahInput as $nilai)
                    <div class="row-item">
                        <div class="avatar-circle">
                            {{ strtoupper(substr($nilai->guru->nama_lengkap, 0, 1)) }}{{ strtoupper(substr(strrchr($nilai->guru->nama_lengkap, ' '), 1, 1)) }}
                        </div>
                        <div class="flex-grow-1 overflow-hidden">
                            <div class="item-name">{{ $nilai->guru->nama_lengkap }}</div>
                            <div class="item-meta">
                                Total {{ number_format($nilai->total_nilai, 1) }} ·
                                <span class="badge-pill b-predikat">{{ $nilai->predikat }}</span>
                            </div>
                        </div>

                        @php
                            $statusClass = match ($nilai->status_verifikasi) {
                                'draft' => 'b-draft',
                                'menunggu' => 'b-menunggu',
                                'disetujui' => 'b-disetujui',
                                default => 'b-ditolak',
                            };
                        @endphp
                        <span class="badge-pill {{ $statusClass }}">
                            {{ ucwords(str_replace('_', ' ', $nilai->status_verifikasi)) }}
                        </span>

                        <a href="{{ route('admin.nilai-guru.edit', $nilai) }}" class="btn-edit-modern">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                    </div>
                @empty
                    <p class="text-center text-muted py-4 mb-0">Belum ada nilai yang diinput untuk periode ini.</p>
                @endforelse
            </div>
        </div>

        {{-- Tab: Belum diinput --}}
        <div class="tab-pane" id="tab-belum" style="display:none;">
            <div class="card border-0 shadow-sm px-3">
                @forelse($guruBelumInput as $guru)
                    <div class="row-item">
                        <div class="avatar-circle belum">
                            {{ strtoupper(substr($guru->nama_lengkap, 0, 1)) }}{{ strtoupper(substr(strrchr($guru->nama_lengkap, ' '), 1, 1)) }}
                        </div>
                        <div class="flex-grow-1 overflow-hidden">
                            <div class="item-name">{{ $guru->nama_lengkap }}</div>
                            <div class="item-meta">Belum ada data untuk periode ini</div>
                        </div>
                        <span class="badge-pill b-belum">Belum input</span>
                        <a href="{{ route('admin.nilai-guru.create', ['guru_id' => $guru->id, 'tahun_ajaran' => $tahunAjaran, 'semester' => $semester]) }}"
                            class="btn-input-modern">
                            <i class="bi bi-plus-circle"></i> Input
                        </a>
                    </div>
                @empty
                    <p class="text-center text-muted py-4 mb-0">Semua guru sudah diinput nilainya untuk periode ini.</p>
                @endforelse
            </div>
        </div>

    </div>

    <script>
        function switchNilaiTab(name, el) {
            document.querySelectorAll('#nilaiTab .nav-link').forEach(t => t.classList.remove('active'));
            el.classList.add('active');

            document.getElementById('tab-sudah').style.display = (name === 'sudah') ? 'block' : 'none';
            document.getElementById('tab-belum').style.display = (name === 'belum') ? 'block' : 'none';
        }
    </script>

@endsection