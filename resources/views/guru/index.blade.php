@extends('layouts.app')

@section('title', 'Dashboard Saya')

@section('breadcrumb')
    <li class="breadcrumb-item">Home</li>
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')

    {{-- ── Header + Filter ─────────────────────────────────────── --}}
    <div class="d-flex flex-wrap align-items-end justify-content-between gap-3 mb-4">
        <div>
            <h5 class="fw-semibold mb-0">Dashboard Saya</h5>
            <small class="text-muted">{{ auth()->user()->nama_lengkap }} — {{ auth()->user()->jabatan }}</small>
        </div>

        <form method="GET" action="{{ route('guru.index') }}" class="d-flex flex-wrap gap-2 align-items-end">
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
            @if ($filterTahun || $filterSemester)
                <a href="{{ route('guru.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="ti ti-x me-1"></i>Reset
                </a>
            @endif
        </form>
    </div>

    {{-- ── Metric Cards ─────────────────────────────────────────── --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card border-0 bg-primary-subtle h-100">
                <div class="card-body">
                    <p class="text-muted small mb-1">
                        <i class="ti ti-chart-line me-1"></i>Rata-rata nilai
                    </p>
                    <h3 class="fw-semibold mb-0 text-primary">
                        {{ $metrics['rata_rata'] ?? '—' }}
                    </h3>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 bg-success-subtle h-100">
                <div class="card-body">
                    <p class="text-muted small mb-1">
                        <i class="ti ti-circle-check me-1"></i>Disetujui
                    </p>
                    <h3 class="fw-semibold mb-0 text-success">{{ $metrics['disetujui'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 bg-warning-subtle h-100">
                <div class="card-body">
                    <p class="text-muted small mb-1">
                        <i class="ti ti-clock me-1"></i>Menunggu
                    </p>
                    <h3 class="fw-semibold mb-0 text-warning">{{ $metrics['menunggu'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 bg-danger-subtle h-100">
                <div class="card-body">
                    <p class="text-muted small mb-1">
                        <i class="ti ti-circle-x me-1"></i>Ditolak
                    </p>
                    <h3 class="fw-semibold mb-0 text-danger">{{ $metrics['ditolak'] }}</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Chart ────────────────────────────────────────────────── --}}
    <div class="card mb-4">
        <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
            <span class="fw-semibold">Tren nilai rata-rata</span>
            <div class="d-flex align-items-center gap-3">
                {{-- Legend --}}
                <div class="d-flex align-items-center gap-3" style="font-size: 12px; color: var(--bs-secondary-color)">
                    <span class="d-flex align-items-center gap-1">
                        <span
                            style="width:12px;height:12px;border-radius:2px;background:#0d6efd;display:inline-block;"></span>
                        Nilai rata-rata
                    </span>
                    <span class="d-flex align-items-center gap-1">
                        <span
                            style="width:12px;height:3px;background:#198754;display:inline-block;border-top:2px dashed #198754;"></span>
                        Target (75)
                    </span>
                </div>
                {{-- Toggle mode --}}
                <select id="chartMode" class="form-select form-select-sm" style="width: auto;">
                    <option value="semester">Per semester</option>
                    <option value="tahun">Per tahun ajaran</option>
                </select>
            </div>
        </div>
        <div class="card-body">
            @if ($chartSemester->isEmpty())
                <div class="text-center text-muted py-5">
                    <i class="ti ti-chart-line d-block mb-2" style="font-size: 2rem;"></i>
                    Belum ada data nilai yang disetujui
                </div>
            @else
                <div style="position: relative; height: 280px;">
                    <canvas id="chartNilai" role="img" aria-label="Grafik tren nilai rata-rata guru per semester">
                        Tren nilai rata-rata dari semester ke semester.
                    </canvas>
                </div>
            @endif
        </div>
    </div>

    {{-- ── Tabel Riwayat ────────────────────────────────────────── --}}
    <div class="card">
        <div class="card-header">
            <span class="fw-semibold">Riwayat penilaian</span>
            <small class="text-muted ms-2">
                — download PDF tersedia setelah status <strong>disetujui</strong>
            </small>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Tahun ajaran</th>
                            <th>Semester</th>
                            <th>Total nilai</th>
                            <th>Predikat</th>
                            <th>Dikirim pada</th>
                            <th>Diverifikasi pada</th>
                            <th>Catatan yayasan</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($riwayat as $nilai)
                            <tr>
                                <td>{{ $nilai->tahun_ajaran }}</td>
                                <td>{{ ucfirst($nilai->semester) }}</td>
                                <td class="fw-semibold">
                                    @if ($nilai->total_nilai)
                                        {{ number_format($nilai->total_nilai, 2) }}
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($nilai->predikat)
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
                                            {{ $nilai->predikat }}
                                        </span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>{{ $nilai->updated_at?->format('d M Y') ?? '—' }}</td>
                                <td>{{ $nilai->diverifikasi_pada?->format('d M Y') ?? '—' }}</td>
                                <td>
                                    @if ($nilai->catatan_yayasan)
                                        <span class="d-inline-block text-truncate" style="max-width: 160px;"
                                            title="{{ $nilai->catatan_yayasan }}">
                                            {{ $nilai->catatan_yayasan }}
                                        </span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $statusMap = [
                                            'draft' => ['secondary', 'Draft'],
                                            'menunggu' => ['warning', 'Menunggu'],
                                            'disetujui' => ['success', 'Disetujui'],
                                            'ditolak' => ['danger', 'Ditolak'],
                                        ];
                                        [$sc, $sl] = $statusMap[$nilai->status_verifikasi] ?? ['secondary', $nilai->status_verifikasi];
                                    @endphp
                                    <span class="badge bg-{{ $sc }}-subtle text-{{ $sc }} border border-{{ $sc }}-subtle">
                                        {{ $sl }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @if ($nilai->status_verifikasi === 'disetujui')
                                        <a href="{{ route('guru.pdf', $nilai) }}" class="btn btn-sm btn-outline-primary"
                                            title="Download PDF">
                                            <i class="ti ti-file-type-pdf me-1"></i>Download
                                        </a>
                                    @else
                                        <button class="btn btn-sm btn-outline-secondary" disabled
                                            title="Tersedia setelah disetujui">
                                            <i class="ti ti-file-type-pdf me-1"></i>Download
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-5">
                                    <i class="ti ti-inbox d-block mb-2" style="font-size: 2rem;"></i>
                                    Belum ada data penilaian
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.js"></script>
    <script>
        @if ($chartSemester->isNotEmpty())
            const dataSemester = {
                labels: @json($chartSemester->pluck('label')),
                values: @json($chartSemester->pluck('value')),
            };
            const dataTahun = {
                labels: @json($chartTahun->pluck('label')),
                values: @json($chartTahun->pluck('value')),
            };

            const targetLine = (labels) => labels.map(() => 75);

            const ctx = document.getElementById('chartNilai').getContext('2d');
            const chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: dataSemester.labels,
                    datasets: [
                        {
                            label: 'Nilai rata-rata',
                            data: dataSemester.values,
                            borderColor: '#0d6efd',
                            backgroundColor: 'rgba(13,110,253,0.07)',
                            borderWidth: 2,
                            pointBackgroundColor: '#0d6efd',
                            pointRadius: 5,
                            pointHoverRadius: 7,
                            fill: true,
                            tension: 0.35,
                        },
                        {
                            label: 'Target (75)',
                            data: targetLine(dataSemester.labels),
                            borderColor: '#198754',
                            borderDash: [6, 4],
                            borderWidth: 1.5,
                            pointRadius: 0,
                            fill: false,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: (ctx) => ctx.datasetIndex === 0
                                    ? ' Nilai: ' + ctx.parsed.y.toFixed(2)
                                    : ' Target: 75'
                            }
                        }
                    },
                    scales: {
                        y: {
                            min: 50,
                            max: 100,
                            ticks: { stepSize: 10, callback: (v) => v },
                            grid: { color: 'rgba(0,0,0,0.05)' }
                        },
                        x: {
                            ticks: { autoSkip: false, maxRotation: 30 },
                            grid: { display: false }
                        }
                    }
                }
            });

            document.getElementById('chartMode').addEventListener('change', function () {
                const d = this.value === 'tahun' ? dataTahun : dataSemester;
                chart.data.labels = d.labels;
                chart.data.datasets[0].data = d.values;
                chart.data.datasets[1].data = targetLine(d.labels);
                chart.update();
            });
        @endif
    </script>
@endpush