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

    .metric-card { background: #F9FAFB; border-radius: 10px; padding: 14px 16px; cursor: pointer; transition: box-shadow .15s; }
    .metric-card:hover { box-shadow: 0 0 0 2px #E5E7EB; }
    .metric-card.active-metric { box-shadow: 0 0 0 2px #6366F1; }
    .metric-label { font-size: 12px; color: #6B7280; margin-bottom: 6px; display: flex; align-items: center; gap: 5px; }
    .metric-num { font-size: 26px; font-weight: 600; }

    .tab-bar { display: flex; gap: 4px; border-bottom: 1px solid #F3F4F6; margin-bottom: 1rem; }
    .tab-btn { border: none; background: none; padding: 9px 18px; font-size: 13px; font-weight: 500;
               cursor: pointer; color: #6B7280; border-bottom: 2px solid transparent; transition: color .15s; }
    .tab-btn.active { color: #6366F1; border-bottom-color: #6366F1; }
    .tab-btn:hover:not(.active) { color: #111827; }

    .tab-panel { display: none; }
    .tab-panel.active { display: block; }

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
    .b-a    { background: #DCFCE7; color: #15803D; border: 0.5px solid #86EFAC; }
    .b-b    { background: #EEF2FF; color: #4338CA; border: 0.5px solid #A5B4FC; }
    .b-c    { background: #FEF3C7; color: #854F0B; border: 0.5px solid #FDE68A; }
    .b-d    { background: #FEE2E2; color: #B91C1C; border: 0.5px solid #FCA5A5; }
    .b-wait { background: #FEF3C7; color: #854F0B; border: 0.5px solid #FDE68A; }
    .b-ok   { background: #DCFCE7; color: #15803D; border: 0.5px solid #86EFAC; }
    .b-err  { background: #FEE2E2; color: #B91C1C; border: 0.5px solid #FCA5A5; }

    .btn-act {
        display: inline-flex; align-items: center; justify-content: center;
        width: 30px; height: 30px; border-radius: 6px; border: 0.5px solid;
        cursor: pointer; font-size: 15px; background: none;
    }
    .btn-detail { border-color: #E5E7EB; background: #fff;     color: #6B7280; }
    .btn-detail:hover { background: #F9FAFB; }
    .btn-verif  { border-color: #86EFAC; background: #DCFCE7; color: #15803D; }
    .btn-verif:hover  { background: #BBF7D0; }
    .btn-tolak  { border-color: #FCA5A5; background: #FEE2E2; color: #B91C1C; }
    .btn-tolak:hover  { background: #FECACA; }
</style>

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

{{-- Metric cards — klik untuk pindah tab --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="metric-card active-metric" id="mc-menunggu" onclick="switchTab('menunggu')">
            <div class="metric-label"><i class="bi bi-clock-history"></i> Menunggu</div>
            <div class="metric-num" style="color:#854F0B">{{ $menunggu->total() }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="metric-card" id="mc-disetujui" onclick="switchTab('disetujui')">
            <div class="metric-label"><i class="bi bi-check-circle"></i> Disetujui</div>
            <div class="metric-num" style="color:#15803D">{{ $disetujui->total() }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="metric-card" id="mc-ditolak" onclick="switchTab('ditolak')">
            <div class="metric-label"><i class="bi bi-x-circle"></i> Ditolak</div>
            <div class="metric-num" style="color:#B91C1C">{{ $ditolak->total() }}</div>
        </div>
    </div>
</div>

{{-- Tab bar --}}
<div class="tab-bar">
    <button class="tab-btn active" id="tab-btn-menunggu" onclick="switchTab('menunggu')">
        Menunggu ({{ $menunggu->total() }})
    </button>
    <button class="tab-btn" id="tab-btn-disetujui" onclick="switchTab('disetujui')">
        Disetujui ({{ $disetujui->total() }})
    </button>
    <button class="tab-btn" id="tab-btn-ditolak" onclick="switchTab('ditolak')">
        Ditolak ({{ $ditolak->total() }})
    </button>
</div>

{{-- ═══════════════ TAB: MENUNGGU ═══════════════ --}}
<div class="tab-panel active" id="panel-menunggu">
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
                        style="background:#DCFCE7; color:#15803D; border:0.5px solid #86EFAC;">
                        <i class="bi bi-check2-all"></i> Verif semua
                    </button>
                </form>
                <button class="btn btn-sm d-flex align-items-center gap-1"
                    style="background:#FEE2E2; color:#B91C1C; border:0.5px solid #FCA5A5;"
                    data-bs-toggle="modal" data-bs-target="#modalBulkReject">
                    <i class="bi bi-x-lg"></i> Tolak semua
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            @include('yayasan.partials.tabel-nilai', [
                'rows'       => $menunggu,
                'emptyMsg'   => 'Tidak ada data yang menunggu verifikasi.',
                'showVerif'  => true,
                'showTolak'  => true,
            ])
        </div>
        @if($menunggu->hasPages())
            <div class="card-footer bg-white">{{ $menunggu->links() }}</div>
        @endif
    </div>
</div>

{{-- ═══════════════ TAB: DISETUJUI ═══════════════ --}}
<div class="tab-panel" id="panel-disetujui">
    <div class="card border-0 shadow-sm tbl-card">
        <div class="card-header bg-white">
            <span class="fw-semibold">Nilai yang disetujui</span>
        </div>
        <div class="card-body p-0">
            @include('yayasan.partials.tabel-nilai', [
                'rows'       => $disetujui,
                'emptyMsg'   => 'Belum ada nilai yang disetujui.',
                'showVerif'  => false,
                'showTolak'  => false,
            ])
        </div>
        @if($disetujui->hasPages())
            <div class="card-footer bg-white">{{ $disetujui->appends(['tahun_ajaran' => $filterTahun, 'semester' => $filterSemester])->links() }}</div>
        @endif
    </div>
</div>

{{-- ═══════════════ TAB: DITOLAK ═══════════════ --}}
<div class="tab-panel" id="panel-ditolak">
    <div class="card border-0 shadow-sm tbl-card">
        <div class="card-header bg-white">
            <span class="fw-semibold">Nilai yang ditolak</span>
        </div>
        <div class="card-body p-0">
            @include('yayasan.partials.tabel-nilai', [
                'rows'       => $ditolak,
                'emptyMsg'   => 'Belum ada nilai yang ditolak.',
                'showVerif'  => true,
                'showTolak'  => false,
            ])
        </div>
        @if($ditolak->hasPages())
            <div class="card-footer bg-white">{{ $ditolak->appends(['tahun_ajaran' => $filterTahun, 'semester' => $filterSemester])->links() }}</div>
        @endif
    </div>
</div>

{{-- ═══════════════ MODAL: Detail Nilai ═══════════════ --}}
<div class="modal fade" id="modalDetail" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom-0 pb-0">
                <h6 class="modal-title fw-semibold">Rincian nilai — <span id="detailNama"></span></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-2">
                <table class="table table-sm table-bordered mb-3" style="font-size:13px;">
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
                <div id="catatanAdminWrap">
                    <p class="text-muted small mb-1">Catatan admin</p>
                    <p class="mb-0" id="detailCatatanAdmin" style="font-size:13px;"></p>
                </div>
            </div>
            <div class="modal-footer border-top-0 pt-0">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

{{-- ═══════════════ MODAL: Tolak satu ═══════════════ --}}
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

{{-- ═══════════════ MODAL: Tolak semua ═══════════════ --}}
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
function switchTab(name) {
    ['menunggu','disetujui','ditolak'].forEach(function(t) {
        document.getElementById('tab-btn-' + t).classList.toggle('active', t === name);
        document.getElementById('panel-' + t).classList.toggle('active', t === name);
        document.getElementById('mc-' + t).classList.toggle('active-metric', t === name);
    });
}

document.getElementById('modalDetail').addEventListener('show.bs.modal', function(e) {
    var b = e.relatedTarget;
    document.getElementById('detailNama').textContent         = b.dataset.nama;
    document.getElementById('dTahsin').textContent            = parseFloat(b.dataset.tahsin).toFixed(2);
    document.getElementById('dUpp').textContent               = parseFloat(b.dataset.upp).toFixed(2);
    document.getElementById('dOrtu').textContent              = parseFloat(b.dataset.ortu).toFixed(2);
    document.getElementById('dTeman').textContent             = parseFloat(b.dataset.teman).toFixed(2);
    document.getElementById('dDisiplin').textContent          = parseFloat(b.dataset.disiplin).toFixed(2);
    document.getElementById('dAbsen').textContent             = parseFloat(b.dataset.absen).toFixed(2);
    document.getElementById('dAjar').textContent              = parseFloat(b.dataset.ajar).toFixed(2);
    document.getElementById('dSupervisi').textContent         = parseFloat(b.dataset.supervisi).toFixed(2);
    document.getElementById('dTotal').textContent             = parseFloat(b.dataset.total).toFixed(2);
    document.getElementById('dPredikat').textContent          = b.dataset.predikat;
    var catatan = b.dataset.catatanadmin || '';
    document.getElementById('detailCatatanAdmin').textContent = catatan || '-';
    document.getElementById('catatanAdminWrap').style.display = 'block';
});

document.getElementById('modalReject').addEventListener('show.bs.modal', function(e) {
    var b = e.relatedTarget;
    document.getElementById('formReject').action          = b.dataset.action;
    document.getElementById('rejectNama').textContent     = b.dataset.nama;
});
</script>
@endpush