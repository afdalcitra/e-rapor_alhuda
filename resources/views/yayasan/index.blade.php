@extends('layouts.app')

@section('title', 'Dashboard Verifikasi')

@section('breadcrumb')
    <li class="breadcrumb-item">Home</li>
    <li class="breadcrumb-item active">Verifikasi Nilai Guru</li>
@endsection

@section('content')

<style>
    .filter-row { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
    .filter-row label { font-size: 12px; color: #6B7280; }

    .metric-card { background: #F9FAFB; border-radius: 10px; padding: 14px 16px; }
    .metric-label { font-size: 12px; color: #6B7280; margin-bottom: 6px; display: flex; align-items: center; gap: 5px; }
    .metric-num { font-size: 26px; font-weight: 600; }

    .tbl-card { border-radius: 12px; overflow: hidden; }

    .avatar-circle {
        width: 34px; height: 34px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 12px; font-weight: 600; flex-shrink: 0;
        background: #EEF2FF; color: #4F46E5;
    }

    .badge-pill {
        display: inline-flex; align-items: center;
        padding: 3px 9px; border-radius: 999px; font-size: 11px; font-weight: 500;
        white-space: nowrap;
    }
    .b-a { background: #DCFCE7; color: #15803D; border: 0.5px solid #86EFAC; }
    .b-b { background: #EEF2FF; color: #4338CA; border: 0.5px solid #A5B4FC; }
    .b-c { background: #FEF3C7; color: #854F0B; border: 0.5px solid #FDE68A; }
    .b-d { background: #FEE2E2; color: #B91C1C; border: 0.5px solid #FCA5A5; }
    .b-wait { background: #FEF3C7; color: #854F0B; border: 0.5px solid #FDE68A; }

    .btn-act {
        display: inline-flex; align-items: center; justify-content: center;
        width: 30px; height: 30px; border-radius: 6px; border: 0.5px solid;
        cursor: pointer; font-size: 15px; text-decoration: none;
    }
    .btn-detail { border-color: #E5E7EB; background: #fff;         color: #6B7280; }
    .btn-detail:hover { background: #F9FAFB; }
    .btn-verif  { border-color: #86EFAC; background: #DCFCE7; color: #15803D; }
    .btn-verif:hover  { background: #BBF7D0; }
    .btn-tolak  { border-color: #FCA5A5; background: #FEE2E2; color: #B91C1C; }
    .btn-tolak:hover  { background: #FECACA; }
</style>

{{-- Flash --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- Header + filter --}}
<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <div>
        <h4 class="mb-0 fw-semibold">Verifikasi nilai guru</h4>
        <small class="text-muted">Kelola status verifikasi penilaian guru</small>
    </div>
    <form method="GET" action="{{ route('yayasan.index') }}" class="filter-row">
        <label>Tahun ajaran</label>
        <select name="tahun_ajaran" class="form-select form-select-sm w-auto" onchange="this.form.submit()">
            <option value="">Semua</option>
            @foreach($tahunAjaranList as $ta)
                <option value="{{ $ta }}" @selected($filterTahun === $ta)>{{ $ta }}</option>
            @endforeach
        </select>
        <label>Semester</label>
        <select name="semester" class="form-select form-select-sm w-auto" onchange="this.form.submit()">
            <option value="">Semua</option>
            <option value="ganjil" @selected($filterSemester === 'ganjil')>Ganjil</option>
            <option value="genap"  @selected($filterSemester === 'genap')>Genap</option>
        </select>
    </form>
</div>

{{-- Metric cards --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="metric-card">
            <div class="metric-label"><i class="bi bi-clock-history"></i> Menunggu</div>
            <div class="metric-num" style="color:#854F0B">{{ $summary['menunggu'] ?? 0 }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="metric-card">
            <div class="metric-label"><i class="bi bi-check-circle"></i> Disetujui</div>
            <div class="metric-num" style="color:#15803D">{{ $summary['disetujui'] ?? 0 }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="metric-card">
            <div class="metric-label"><i class="bi bi-x-circle"></i> Ditolak</div>
            <div class="metric-num" style="color:#B91C1C">{{ $summary['ditolak'] ?? 0 }}</div>
        </div>
    </div>
</div>

{{-- Tabel --}}
<div class="card border-0 shadow-sm tbl-card">
    <div class="card-header bg-white d-flex flex-wrap align-items-center justify-content-between gap-2">
        <span class="fw-semibold">Menunggu verifikasi</span>
        <div class="d-flex gap-2">
            <form method="POST" action="{{ route('yayasan.bulk-verify') }}"
                onsubmit="return confirm('Verifikasi semua yang menunggu sesuai filter aktif?')">
                @csrf
                <input type="hidden" name="tahun_ajaran" value="{{ $filterTahun }}">
                <input type="hidden" name="semester" value="{{ $filterSemester }}">
                <button class="btn btn-sm d-flex align-items-center gap-1"
                    style="background:#DCFCE7; color:#15803D; border: 0.5px solid #86EFAC;">
                    <i class="bi bi-check2-all"></i> Verif semua
                </button>
            </form>
            <button class="btn btn-sm d-flex align-items-center gap-1"
                style="background:#FEE2E2; color:#B91C1C; border: 0.5px solid #FCA5A5;"
                data-bs-toggle="modal" data-bs-target="#modalBulkReject">
                <i class="bi bi-x-lg"></i> Tolak semua
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
                        <th>Total</th>
                        <th>Predikat</th>
                        <th>Dikirim pada</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($nilaiGurus as $nilai)
                        @php
                            $inisial = strtoupper(substr($nilai->guru->nama_lengkap, 0, 1))
                                . strtoupper(substr(strrchr($nilai->guru->nama_lengkap, ' '), 1, 1));
                            $predClass = match($nilai->predikat) {
                                'A' => 'b-a', 'B' => 'b-b', 'C' => 'b-c', default => 'b-d'
                            };
                        @endphp
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="avatar-circle">{{ $inisial }}</div>
                                    <div>
                                        <div style="font-weight:500; font-size:14px;">{{ $nilai->guru->nama_lengkap }}</div>
                                        <div style="font-size:12px; color:#9CA3AF;">{{ $nilai->guru->jabatan }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $nilai->tahun_ajaran }}</td>
                            <td>{{ ucfirst($nilai->semester) }}</td>
                            <td style="font-weight:600">{{ number_format($nilai->total_nilai, 2) }}</td>
                            <td><span class="badge-pill {{ $predClass }}">{{ $nilai->predikat ?? '-' }}</span></td>
                            <td style="font-size:12px; color:#9CA3AF;">
                                {{ $nilai->updated_at->format('d M Y, H:i') ?? '-' }}
                            </td>
                            <td><span class="badge-pill b-wait">{{ ucfirst($nilai->status_verifikasi) }}</span></td>
                            <td>
                                <div class="d-flex justify-content-end gap-1">
                                    {{-- Detail --}}
                                    <button class="btn-act btn-detail" title="Lihat detail"
                                        data-bs-toggle="modal" data-bs-target="#modalDetail"
                                        data-nama="{{ $nilai->guru->nama_lengkap }}"
                                        data-tahsin="{{ $nilai->nilai_tahsin }}"
                                        data-upp="{{ $nilai->nilai_upp }}"
                                        data-ortu="{{ $nilai->nilai_ortu }}"
                                        data-teman="{{ $nilai->nilai_teman }}"
                                        data-disiplin="{{ $nilai->nilai_disiplin }}"
                                        data-absen="{{ $nilai->nilai_absen }}"
                                        data-ajar="{{ $nilai->nilai_ajar }}"
                                        data-supervisi="{{ $nilai->nilai_supervisi }}"
                                        data-total="{{ $nilai->total_nilai }}"
                                        data-predikat="{{ $nilai->predikat }}"
                                        data-catatanadmin="{{ $nilai->catatan_admin }}">
                                        <i class="bi bi-eye"></i>
                                    </button>

                                    {{-- Verifikasi --}}
                                    <form method="POST" action="{{ route('yayasan.verify', $nilai) }}"
                                        onsubmit="return confirm('Verifikasi nilai {{ addslashes($nilai->guru->nama_lengkap) }}?')">
                                        @csrf
                                        <button type="submit" class="btn-act btn-verif" title="Verifikasi">
                                            <i class="bi bi-check-lg"></i>
                                        </button>
                                    </form>

                                    {{-- Tolak --}}
                                    <button class="btn-act btn-tolak" title="Tolak"
                                        data-bs-toggle="modal" data-bs-target="#modalReject"
                                        data-action="{{ route('yayasan.reject', $nilai) }}"
                                        data-nama="{{ $nilai->guru->nama_lengkap }}">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-5">
                                <i class="bi bi-inbox d-block mb-2" style="font-size:2rem;"></i>
                                Tidak ada data yang menunggu verifikasi
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($nilaiGurus->hasPages())
        <div class="card-footer bg-white">
            {{ $nilaiGurus->links() }}
        </div>
    @endif
</div>

{{-- Modal: Detail Nilai --}}
<div class="modal fade" id="modalDetail" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom-0 pb-0">
                <h6 class="modal-title fw-semibold">Rincian nilai — <span id="detailNama"></span></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-2">
                <table class="table table-sm table-bordered mb-0" style="font-size:13px;">
                    <tbody>
                        <tr><td class="text-muted">Tahsin</td>    <td class="fw-medium text-end" id="dTahsin"></td></tr>
                        <tr><td class="text-muted">UPP</td>       <td class="fw-medium text-end" id="dUpp"></td></tr>
                        <tr><td class="text-muted">Orang tua</td> <td class="fw-medium text-end" id="dOrtu"></td></tr>
                        <tr><td class="text-muted">Teman</td>     <td class="fw-medium text-end" id="dTeman"></td></tr>
                        <tr><td class="text-muted">Disiplin</td>  <td class="fw-medium text-end" id="dDisiplin"></td></tr>
                        <tr><td class="text-muted">Absen</td>     <td class="fw-medium text-end" id="dAbsen"></td></tr>
                        <tr><td class="text-muted">Mengajar</td>  <td class="fw-medium text-end" id="dAjar"></td></tr>
                        <tr><td class="text-muted">Supervisi</td> <td class="fw-medium text-end" id="dSupervisi"></td></tr>
                        <tr class="table-light">
                            <td class="fw-semibold">Total</td>
                            <td class="fw-semibold text-end" id="dTotal"></td>
                        </tr>
                        <tr class="table-light">
                            <td class="fw-semibold">Predikat</td>
                            <td class="fw-semibold text-end" id="dPredikat"></td>
                        </tr>
                    </tbody>
                </table>
                <h6 class="mb-4">Catatan admin : <span id="detailCatatanAdmin"></span></h6>
            </div>
            <div class="modal-footer border-top-0 pt-0">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

{{-- Modal: Tolak satu --}}
<div class="modal fade" id="modalReject" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <form method="POST" id="formReject">
                @csrf
                <div class="modal-header border-bottom-0">
                    <h6 class="modal-title fw-semibold">Tolak penilaian</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body pt-0">
                    <p class="text-muted small mb-3">Tolak penilaian <strong id="rejectNama"></strong>?</p>
                    <label class="form-label form-label-sm">
                        Catatan penolakan <span class="text-muted">(opsional)</span>
                    </label>
                    <textarea name="catatan_yayasan" class="form-control form-control-sm" rows="3"
                        placeholder="Alasan penolakan untuk guru..."></textarea>
                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-danger d-flex align-items-center gap-1">
                        <i class="bi bi-x-lg"></i> Tolak
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal: Tolak semua --}}
<div class="modal fade" id="modalBulkReject" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <form method="POST" action="{{ route('yayasan.bulk-reject') }}">
                @csrf
                <input type="hidden" name="tahun_ajaran" value="{{ $filterTahun }}">
                <input type="hidden" name="semester" value="{{ $filterSemester }}">
                <div class="modal-header border-bottom-0">
                    <h6 class="modal-title fw-semibold">Tolak semua</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body pt-0">
                    <p class="text-muted small mb-3">
                        Tolak semua penilaian berstatus <strong>menunggu</strong>
                        @if($filterTahun) untuk tahun ajaran <strong>{{ $filterTahun }}</strong>@endif
                        @if($filterSemester) semester <strong>{{ ucfirst($filterSemester) }}</strong>@endif?
                    </p>
                    <label class="form-label form-label-sm">
                        Catatan penolakan <span class="text-muted">(opsional, berlaku untuk semua)</span>
                    </label>
                    <textarea name="catatan_yayasan" class="form-control form-control-sm" rows="3"
                        placeholder="Alasan penolakan..."></textarea>
                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-danger d-flex align-items-center gap-1">
                        <i class="bi bi-x-lg"></i> Tolak semua
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.getElementById('modalDetail').addEventListener('show.bs.modal', function(e) {
    var b = e.relatedTarget;

    console.log(b)
    console.log(b.dataset)

    document.getElementById('detailNama').textContent = b.dataset.nama;
    document.getElementById('detailCatatanAdmin').textContent = b.dataset.catatanadmin;
    document.getElementById('dTahsin').textContent   = parseFloat(b.dataset.tahsin).toFixed(2);
    document.getElementById('dUpp').textContent      = parseFloat(b.dataset.upp).toFixed(2);
    document.getElementById('dOrtu').textContent     = parseFloat(b.dataset.ortu).toFixed(2);
    document.getElementById('dTeman').textContent    = parseFloat(b.dataset.teman).toFixed(2);
    document.getElementById('dDisiplin').textContent = parseFloat(b.dataset.disiplin).toFixed(2);
    document.getElementById('dAbsen').textContent    = parseFloat(b.dataset.absen).toFixed(2);
    document.getElementById('dAjar').textContent     = parseFloat(b.dataset.ajar).toFixed(2);
    document.getElementById('dSupervisi').textContent= parseFloat(b.dataset.supervisi).toFixed(2);
    document.getElementById('dTotal').textContent    = parseFloat(b.dataset.total).toFixed(2);
    document.getElementById('dPredikat').textContent = b.dataset.predikat;
});

document.getElementById('modalReject').addEventListener('show.bs.modal', function(e) {
    var b = e.relatedTarget;
    document.getElementById('formReject').action = b.dataset.action;
    document.getElementById('rejectNama').textContent = b.dataset.nama;
});
</script>
@endpush