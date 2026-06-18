<style>
    .section-card {
        background: #fff;
        border: 1px solid #F3F4F6;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 16px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, .05);
    }

    .section-title {
        font-size: 11px;
        font-weight: 600;
        color: #9CA3AF;
        text-transform: uppercase;
        letter-spacing: .06em;
        margin-bottom: 16px;
    }

    .total-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #EEF2FF;
        border-radius: 8px;
        padding: 12px 16px;
        margin-top: 12px;
    }

    .total-bar .lbl {
        font-size: 13px;
        color: #4F46E5;
    }

    .total-bar .val {
        font-size: 22px;
        font-weight: 600;
        color: #4F46E5;
    }

    .predikat-pill {
        font-size: 12px;
        font-weight: 500;
        padding: 4px 12px;
        border-radius: 999px;
    }
</style>

{{-- Informasi periode --}}
<div class="section-card">
    <div class="section-title">Informasi periode</div>

    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Guru</label>
            <select name="guru_id" class="form-select" required>
                <option value="">Pilih Guru</option>
                @foreach($gurus as $guru)
                    <option value="{{ $guru->id }}" @selected(old('guru_id', $nilaiGuru->guru_id ?? request('guru_id')) == $guru->id)>
                        {{ $guru->nama_lengkap }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">
            <label class="form-label">Tahun Ajaran</label>
            <input type="text" name="tahun_ajaran" class="form-control"
                value="{{ old('tahun_ajaran', $nilaiGuru->tahun_ajaran ?? request('tahun_ajaran')) }}" required>
        </div>

        <div class="col-md-3">
            <label class="form-label">Semester</label>
            <select name="semester" class="form-select" required>
                <option value="ganjil" @selected(old('semester', $nilaiGuru->semester ?? request('semester')) == 'ganjil')>
                    Ganjil
                </option>
                <option value="genap" @selected(old('semester', $nilaiGuru->semester ?? request('semester')) == 'genap')>
                    Genap
                </option>
            </select>
        </div>
    </div>
</div>

{{-- Komponen nilai --}}
<div class="section-card">
    <div class="section-title">Komponen nilai (skala 0–100)</div>

    @php
        $fields = [
            'nilai_tahsin' => 'Tahsin',
            'nilai_upp' => 'UPP',
            'nilai_ortu' => 'Ortu',
            'nilai_teman' => 'Teman',
            'nilai_disiplin' => 'Disiplin',
            'nilai_absen' => 'Absen',
            'nilai_ajar' => 'Ajar',
            'nilai_supervisi' => 'Supervisi',
        ];
    @endphp

    <div class="row g-3">
        @foreach($fields as $field => $label)
            <div class="col-md-3">
                <label class="form-label">{{ $label }}</label>
                <input type="number" min="0" max="100" step="0.01" name="{{ $field }}" class="form-control nilai-input"
                    value="{{ old($field, $nilaiGuru->$field ?? '') }}" required>
            </div>
        @endforeach
    </div>

    {{-- Preview total & predikat (live, dihitung ulang oleh server saat submit) --}}
    <div class="total-bar">
        <div>
            <div class="lbl">Rata-rata total (preview)</div>
            <div class="val" id="totalPreview">0.00</div>
        </div>
        <span class="predikat-pill" id="predikatPreview" style="background:#F1EFE8; color:#444441;">-</span>
    </div>
    <p class="text-muted mt-2 mb-0" style="font-size:11px;">
        *Preview dihitung di browser. Nilai final tetap dihitung ulang oleh server saat disimpan.
    </p>
</div>

{{-- Catatan admin --}}
<div class="section-card">
    <div class="section-title">Catatan admin</div>
    <textarea name="catatan_admin" rows="3" class="form-control"
        placeholder="Catatan tambahan (opsional)">{{ old('catatan_admin', $nilaiGuru->catatan_admin ?? '') }}</textarea>
</div>

<div class="d-flex gap-2">
    <a href="{{ route('admin.nilai-guru.index') }}" class="btn btn-secondary">Kembali</a>
    <button type="submit" class="btn btn-primary px-4">Simpan</button>
</div>

<script>
    function recalcPreview() {
        var inputs = document.querySelectorAll('.nilai-input');
        var sum = 0, count = 0;
        inputs.forEach(function (i) {
            var v = parseFloat(i.value);
            if (!isNaN(v)) { sum += v; count++; }
        });
        var avg = count > 0 ? sum / count : 0;

        document.getElementById('totalPreview').textContent = avg.toFixed(2);

        var pill = document.getElementById('predikatPreview');
        var label, bg, color;
        if (avg >= 85) { label = 'Sangat Baik'; bg = '#DCFCE7'; color = '#15803D'; }
        else if (avg >= 70) { label = 'Baik'; bg = '#EEF2FF'; color = '#4F46E5'; }
        else if (avg >= 55) { label = 'Cukup'; bg = '#FEF3C7'; color = '#854F0B'; }
        else if (count > 0) { label = 'Kurang'; bg = '#FEE2E2'; color = '#B91C1C'; }
        else { label = '-'; bg = '#F1EFE8'; color = '#444441'; }

        pill.textContent = label;
        pill.style.background = bg;
        pill.style.color = color;
    }

    document.querySelectorAll('.nilai-input').forEach(function (input) {
        input.addEventListener('input', recalcPreview);
    });
    recalcPreview();
</script>