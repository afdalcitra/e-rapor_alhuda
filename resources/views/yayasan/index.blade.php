@extends('layouts.app')

@section('title', 'Dashboard Verifikasi')

@section('breadcrumb')
    <li class="breadcrumb-item">Home</li>
    <li class="breadcrumb-item active">Verifikasi Nilai Guru</li>
@endsection

@section('content')

    {{-- Flash message --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ── Header + Filter ─────────────────────────────────────── --}}
    <div class="d-flex flex-wrap align-items-end justify-content-between gap-3 mb-4">
        <div>
            <h5 class="fw-semibold mb-0">Verifikasi Nilai Guru</h5>
            <small class="text-muted">Kelola status verifikasi penilaian guru</small>
        </div>

        <form method="GET" action="{{ route('yayasan.index') }}" class="d-flex flex-wrap gap-2 align-items-end">
            <div>
                <label class="form-label form-label-sm mb-1">Tahun ajaran</label>
                <select name="tahun_ajaran" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">Semua</option>
                    @foreach ($tahunAjaranList as $ta)
                        <option value="{{ $ta }}" @selected($filterTahun === $ta)>{{ $ta }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label form-label-sm mb-1">Semester</label>
                <select name="semester" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">Semua</option>
                    <option value="ganjil" @selected($filterSemester === 'ganjil')>Ganjil</option>
                    <option value="genap" @selected($filterSemester === 'genap')>Genap</option>
                </select>
            </div>
        </form>
    </div>

    {{-- ── Metric Cards ─────────────────────────────────────────── --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 bg-warning-subtle h-100">
                <div class="card-body">
                    <p class="text-muted small mb-1">
                        <i class="ti ti-clock me-1"></i>Menunggu verifikasi
                    </p>
                    <h3 class="fw-semibold mb-0">{{ $summary['menunggu'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 bg-success-subtle h-100">
                <div class="card-body">
                    <p class="text-muted small mb-1">
                        <i class="ti ti-circle-check me-1"></i>Disetujui
                    </p>
                    <h3 class="fw-semibold mb-0 text-success">{{ $summary['disetujui'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 bg-danger-subtle h-100">
                <div class="card-body">
                    <p class="text-muted small mb-1">
                        <i class="ti ti-circle-x me-1"></i>Ditolak
                    </p>
                    <h3 class="fw-semibold mb-0 text-danger">{{ $summary['ditolak'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Tabel ────────────────────────────────────────────────── --}}
    <div class="card">
        <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
            <span class="fw-semibold">Daftar Menunggu Verifikasi</span>

            <div class="d-flex gap-2">
                {{-- Verif Semua --}}
                <form method="POST" action="{{ route('yayasan.bulk-verify') }}"
                    onsubmit="return confirm('Verifikasi semua yang menunggu sesuai filter aktif?')">
                    @csrf
                    <input type="hidden" name="tahun_ajaran" value="{{ $filterTahun }}">
                    <input type="hidden" name="semester" value="{{ $filterSemester }}">
                    <button class="btn btn-sm btn-success">
                        <i class="ti ti-checks me-1"></i>Verif semua
                    </button>
                </form>

                {{-- Tolak Semua — buka modal supaya bisa isi catatan --}}
                <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#modalBulkReject">
                    <i class="ti ti-x me-1"></i>Tolak semua
                </button>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Nama guru</th>
                            <th>Tahun ajaran</th>
                            <th>Semester</th>
                            <th>Total nilai</th>
                            <th>Predikat</th>
                            <th>Dikirim pada</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($nilaiGurus as $nilai)
                            <tr>
                                <td>
                                    <span class="fw-medium">{{ $nilai->guru->nama_lengkap }}</span>
                                    <br>
                                    <small class="text-muted">{{ $nilai->guru->jabatan }}</small>
                                </td>
                                <td>{{ $nilai->tahun_ajaran }}</td>
                                <td>{{ ucfirst($nilai->semester) }}</td>
                                <td class="fw-semibold">
                                    {{ number_format($nilai->total_nilai, 2) }}
                                </td>
                                <td>
                                    @php
                                        $colorMap = [
                                            'A' => 'success',
                                            'B' => 'primary',
                                            'C' => 'warning',
                                            'D' => 'danger',
                                        ];
                                        $bc = $colorMap[$nilai->predikat] ?? 'secondary';
                                    @endphp
                                    <span class="badge bg-{{ $bc }}-subtle text-{{ $bc }} border border-{{ $bc }}-subtle">
                                        {{ $nilai->predikat ?? '-' }}
                                    </span>
                                </td>
                                <td>{{ $nilai->dikirim_pada?->format('d M Y, H:i') ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle">
                                        Menunggu
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-1">
                                        {{-- Detail --}}
                                        <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal"
                                            data-bs-target="#modalDetail" data-nama="{{ $nilai->guru->nama_lengkap }}"
                                            data-tahsin="{{ $nilai->nilai_tahsin }}" data-upp="{{ $nilai->nilai_upp }}"
                                            data-ortu="{{ $nilai->nilai_ortu }}" data-teman="{{ $nilai->nilai_teman }}"
                                            data-disiplin="{{ $nilai->nilai_disiplin }}" data-absen="{{ $nilai->nilai_absen }}"
                                            data-ajar="{{ $nilai->nilai_ajar }}" data-supervisi="{{ $nilai->nilai_supervisi }}"
                                            data-total="{{ $nilai->total_nilai }}" data-predikat="{{ $nilai->predikat }}"
                                            title="Lihat detail">
                                            <i class="ti ti-eye"></i>
                                        </button>

                                        {{-- Verifikasi --}}
                                        <form method="POST" action="{{ route('yayasan.verify', $nilai) }}"
                                            onsubmit="return confirm('Verifikasi nilai {{ addslashes($nilai->guru->nama_lengkap) }}?')">
                                            @csrf
                                            <button class="btn btn-sm btn-outline-success" title="Verifikasi">
                                                <i class="ti ti-check"></i>
                                            </button>
                                        </form>

                                        {{-- Tolak --}}
                                        <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal"
                                            data-bs-target="#modalReject" data-action="{{ route('yayasan.reject', $nilai) }}"
                                            data-nama="{{ $nilai->guru->nama_lengkap }}" title="Tolak">
                                            <i class="ti ti-x"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-5">
                                    <i class="ti ti-inbox d-block mb-2" style="font-size: 2rem;"></i>
                                    Tidak ada data yang menunggu verifikasi
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($nilaiGurus->hasPages())
            <div class="card-footer">
                {{ $nilaiGurus->links() }}
            </div>
        @endif
    </div>


    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- Modal: Detail Nilai --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <div class="modal fade" id="modalDetail" tabindex="-1" aria-labelledby="labelModalDetail" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-semibold" id="labelModalDetail">Rincian Nilai</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="fw-medium mb-3" id="detailNama"></p>
                    <table class="table table-sm table-bordered mb-0">
                        <tbody>
                            <tr>
                                <td class="text-muted small">Tahsin</td>
                                <td class="fw-medium text-end" id="dTahsin"></td>
                            </tr>
                            <tr>
                                <td class="text-muted small">UPP</td>
                                <td class="fw-medium text-end" id="dUpp"></td>
                            </tr>
                            <tr>
                                <td class="text-muted small">Orang tua</td>
                                <td class="fw-medium text-end" id="dOrtu"></td>
                            </tr>
                            <tr>
                                <td class="text-muted small">Teman</td>
                                <td class="fw-medium text-end" id="dTeman"></td>
                            </tr>
                            <tr>
                                <td class="text-muted small">Disiplin</td>
                                <td class="fw-medium text-end" id="dDisiplin"></td>
                            </tr>
                            <tr>
                                <td class="text-muted small">Absen</td>
                                <td class="fw-medium text-end" id="dAbsen"></td>
                            </tr>
                            <tr>
                                <td class="text-muted small">Mengajar</td>
                                <td class="fw-medium text-end" id="dAjar"></td>
                            </tr>
                            <tr>
                                <td class="text-muted small">Supervisi</td>
                                <td class="fw-medium text-end" id="dSupervisi"></td>
                            </tr>
                            <tr class="table-light">
                                <td class="fw-semibold small">Total</td>
                                <td class="fw-semibold text-end" id="dTotal"></td>
                            </tr>
                            <tr class="table-light">
                                <td class="fw-semibold small">Predikat</td>
                                <td class="fw-semibold text-end" id="dPredikat"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>


    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- Modal: Tolak satu entri --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <div class="modal fade" id="modalReject" tabindex="-1" aria-labelledby="labelModalReject" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" id="formReject">
                    @csrf
                    <div class="modal-header">
                        <h6 class="modal-title fw-semibold" id="labelModalReject">Tolak Penilaian</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-muted small mb-3">
                            Tolak penilaian <strong id="rejectNama"></strong>?
                        </p>
                        <div>
                            <label class="form-label form-label-sm">
                                Catatan penolakan
                                <span class="text-muted">(opsional)</span>
                            </label>
                            <textarea name="catatan_yayasan" class="form-control form-control-sm" rows="3"
                                placeholder="Alasan penolakan untuk guru..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-sm btn-danger">
                            <i class="ti ti-x me-1"></i>Tolak
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- Modal: Tolak semua (bulk) --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <div class="modal fade" id="modalBulkReject" tabindex="-1" aria-labelledby="labelModalBulkReject" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('yayasan.bulk-reject') }}">
                    @csrf
                    <input type="hidden" name="tahun_ajaran" value="{{ $filterTahun }}">
                    <input type="hidden" name="semester" value="{{ $filterSemester }}">
                    <div class="modal-header">
                        <h6 class="modal-title fw-semibold" id="labelModalBulkReject">Tolak Semua</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-muted small mb-3">
                            Tolak semua penilaian berstatus <strong>menunggu</strong>
                            @if ($filterTahun) untuk tahun ajaran <strong>{{ $filterTahun }}</strong>@endif
                            @if ($filterSemester) semester <strong>{{ ucfirst($filterSemester) }}</strong>@endif?
                        </p>
                        <div>
                            <label class="form-label form-label-sm">
                                Catatan penolakan
                                <span class="text-muted">(opsional, berlaku untuk semua)</span>
                            </label>
                            <textarea name="catatan_yayasan" class="form-control form-control-sm" rows="3"
                                placeholder="Alasan penolakan..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-sm btn-danger">
                            <i class="ti ti-x me-1"></i>Tolak semua
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        document.getElementById('modalDetail').addEventListener('show.bs.modal', function (e) {
            const b = e.relatedTarget;
            document.getElementById('detailNama').textContent = b.dataset.nama;
            document.getElementById('dTahsin').textContent = parseFloat(b.dataset.tahsin).toFixed(2);
            document.getElementById('dUpp').textContent = parseFloat(b.dataset.upp).toFixed(2);
            document.getElementById('dOrtu').textContent = parseFloat(b.dataset.ortu).toFixed(2);
            document.getElementById('dTeman').textContent = parseFloat(b.dataset.teman).toFixed(2);
            document.getElementById('dDisiplin').textContent = parseFloat(b.dataset.disiplin).toFixed(2);
            document.getElementById('dAbsen').textContent = parseFloat(b.dataset.absen).toFixed(2);
            document.getElementById('dAjar').textContent = parseFloat(b.dataset.ajar).toFixed(2);
            document.getElementById('dSupervisi').textContent = parseFloat(b.dataset.supervisi).toFixed(2);
            document.getElementById('dTotal').textContent = parseFloat(b.dataset.total).toFixed(2);
            document.getElementById('dPredikat').textContent = b.dataset.predikat;
        });

        document.getElementById('modalReject').addEventListener('show.bs.modal', function (e) {
            const b = e.relatedTarget;
            document.getElementById('formReject').action = b.dataset.action;
            document.getElementById('rejectNama').textContent = b.dataset.nama;
        });
    </script>
@endpush