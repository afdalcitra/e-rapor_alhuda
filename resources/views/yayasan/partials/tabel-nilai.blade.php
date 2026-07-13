{{--
    Props:
      $rows       — paginator (menunggu / disetujui / ditolak)
      $emptyMsg   — string pesan saat kosong
      $showVerif  — bool, tampilkan tombol verifikasi
      $showTolak  — bool, tampilkan tombol tolak
--}}
<div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
            <tr>
                <th>Nama guru</th>
                <th>Tahun ajaran</th>
                <th>Semester</th>
                <th>Total</th>
                <th>Predikat</th>
                <th>Diperbarui</th>
                <th>Status</th>
                <th class="text-end">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rows as $nilai)
                @php
                    $inisial = strtoupper(substr($nilai->guru->nama_lengkap, 0, 1))
                             . strtoupper(substr(strrchr($nilai->guru->nama_lengkap, ' '), 1, 1));

                    $predClass = match($nilai->predikat) {
                        'A' => 'b-a', 'B' => 'b-b', 'C' => 'b-c', default => 'b-d'
                    };

                    $statusClass = match($nilai->status_verifikasi) {
                        'menunggu'  => 'b-wait',
                        'disetujui' => 'b-ok',
                        'ditolak'   => 'b-err',
                        default     => 'b-wait',
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
                        {{ $nilai->updated_at->format('d M Y, H:i') }}
                    </td>
                    <td>
                        <span class="badge-pill {{ $statusClass }}">
                            {{ ucfirst($nilai->status_verifikasi) }}
                        </span>
                    </td>
                    <td>
                        <div class="d-flex justify-content-end gap-1">

                            {{-- Detail (selalu tampil) --}}
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

                            {{-- Verifikasi (tampil di tab menunggu & ditolak) --}}
                            @if($showVerif)
                                <form method="POST" action="{{ route('yayasan.verify', $nilai) }}"
                                    onsubmit="return confirm('Verifikasi nilai {{ addslashes($nilai->guru->nama_lengkap) }}?')">
                                    @csrf
                                    <button type="submit" class="btn-act btn-verif" title="Verifikasi">
                                        <i class="bi bi-check-lg"></i>
                                    </button>
                                </form>
                            @endif

                            {{-- Tolak (tampil di tab menunggu) --}}
                            @if($showTolak)
                                <button class="btn-act btn-tolak" title="Tolak"
                                    data-bs-toggle="modal" data-bs-target="#modalReject"
                                    data-action="{{ route('yayasan.reject', $nilai) }}"
                                    data-nama="{{ $nilai->guru->nama_lengkap }}">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            @endif

                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-5">
                        <i class="bi bi-inbox d-block mb-2" style="font-size:2rem;"></i>
                        {{ $emptyMsg }}
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>