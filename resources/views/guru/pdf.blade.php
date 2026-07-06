<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Rapor Penilaian Guru</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            color: #1a1a1a;
            line-height: 1.5;
        }

        .page {
            padding: 40px 48px;
        }

        /* ── Header ── */
        .header {
            text-align: center;
            border-bottom: 2px solid #1a1a1a;
            padding-bottom: 16px;
            margin-bottom: 24px;
        }

        .header h1 {
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .header p {
            font-size: 11px;
            color: #555;
            margin-top: 2px;
        }

        /* ── Info Section ── */
        .info-table {
            width: 100%;
            margin-bottom: 24px;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 4px 8px;
            vertical-align: top;
            font-size: 11.5px;
        }

        .info-table td:first-child {
            width: 140px;
            color: #555;
        }

        .info-table td:nth-child(2) {
            width: 12px;
            color: #555;
        }

        .info-table td:last-child {
            font-weight: bold;
        }

        /* ── Nilai Table ── */
        .section-title {
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            margin-bottom: 8px;
            color: #333;
            border-left: 3px solid #1a1a1a;
            padding-left: 8px;
        }

        .nilai-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }

        .nilai-table th {
            background: #f0f0f0;
            padding: 8px 10px;
            text-align: left;
            font-size: 11px;
            font-weight: bold;
            border: 1px solid #ccc;
        }

        .nilai-table td {
            padding: 8px 10px;
            border: 1px solid #ddd;
            font-size: 11.5px;
        }

        .nilai-table tr:nth-child(even) td {
            background: #fafafa;
        }

        .nilai-table td.text-right {
            text-align: right;
        }

        .nilai-table tfoot td {
            background: #1a1a1a;
            color: #fff;
            font-weight: bold;
            padding: 10px;
            border: 1px solid #1a1a1a;
        }

        .nilai-table tfoot td.text-right {
            text-align: right;
        }

        /* ── Predikat Box ── */
        .predikat-box {
            display: inline-block;
            padding: 2px 12px;
            border: 2px solid #1a1a1a;
            border-radius: 4px;
            font-size: 20px;
            font-weight: bold;
        }

        /* ── Summary Row ── */
        .summary-row {
            width: 100%;
            margin-bottom: 32px;
        }

        .summary-cell {
            display: inline-block;
            width: 48%;
            background: #f8f8f8;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 12px 16px;
            text-align: center;
        }

        .summary-cell .label {
            font-size: 10px;
            color: #777;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .summary-cell .value {
            font-size: 24px;
            font-weight: bold;
            margin-top: 4px;
        }

        /* ── Catatan ── */
        .catatan-box {
            background: #f8f8f8;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 12px;
            font-size: 11px;
            color: #555;
            margin-bottom: 32px;
        }

        /* ── TTD Section ── */
        .ttd-section {
            width: 100%;
            margin-top: 16px;
        }

        .ttd-cell {
            width: 33%;
            display: inline-block;
            text-align: center;
            vertical-align: top;
            padding: 0 8px;
        }

        .ttd-cell .role {
            font-size: 11px;
            color: #555;
            margin-bottom: 60px;
        }

        .ttd-cell .name {
            border-top: 1px solid #333;
            padding-top: 6px;
            font-size: 11.5px;
            font-weight: bold;
        }

        .ttd-cell .nip {
            font-size: 10px;
            color: #777;
        }

        /* ── Footer ── */
        .footer {
            margin-top: 32px;
            border-top: 1px solid #ddd;
            padding-top: 8px;
            font-size: 10px;
            color: #999;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="page">

        {{-- ── Header ── --}}
        <div class="header">
            <h1>Rapor Penilaian Guru</h1>
            <p>Yayasan Al-Huda &nbsp;·&nbsp; Dokumen Resmi</p>
        </div>

        {{-- ── Info Guru ── --}}
        <div class="section-title">Informasi Guru</div>
        <table class="info-table">
            <tr>
                <td>Nama lengkap</td>
                <td>:</td>
                <td>{{ $nilaiGuru->guru->nama_lengkap }}</td>
            </tr>
            <tr>
                <td>NIPY</td>
                <td>:</td>
                <td>{{ $nilaiGuru->guru->nipy }}</td>
            </tr>
            <tr>
                <td>Jabatan</td>
                <td>:</td>
                <td>{{ $nilaiGuru->guru->jabatan }}</td>
            </tr>
            <tr>
                <td>Tahun ajaran</td>
                <td>:</td>
                <td>{{ $nilaiGuru->tahun_ajaran }}</td>
            </tr>
            <tr>
                <td>Semester</td>
                <td>:</td>
                <td>{{ ucfirst($nilaiGuru->semester) }}</td>
            </tr>
            <tr>
                <td>Tanggal cetak</td>
                <td>:</td>
                <td>{{ now()->translatedFormat('d F Y') }}</td>
            </tr>
        </table>

        {{-- ── Rincian Nilai ── --}}
        <div class="section-title">Rincian Nilai</div>
        <table class="nilai-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Komponen penilaian</th>
                    <th class="text-right" style="width: 100px;">Nilai</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $komponen = [
                        'Tahsin' => $nilaiGuru->nilai_tahsin,
                        'UPP' => $nilaiGuru->nilai_upp,
                        'Orang Tua' => $nilaiGuru->nilai_ortu,
                        'Teman' => $nilaiGuru->nilai_teman,
                        'Disiplin' => $nilaiGuru->nilai_disiplin,
                        'Absen' => $nilaiGuru->nilai_absen,
                        'Mengajar' => $nilaiGuru->nilai_ajar,
                        'Supervisi' => $nilaiGuru->nilai_supervisi,
                    ];
                @endphp
                @foreach ($komponen as $nama => $nilai)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $nama }}</td>
                        <td class="text-right">{{ number_format($nilai, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2">Total nilai (rata-rata)</td>
                    <td class="text-right">{{ number_format($nilaiGuru->total_nilai, 2) }}</td>
                </tr>
            </tfoot>
        </table>

        {{-- ── Predikat ── --}}
        <table style="width:100%; margin-bottom: 28px;">
            <tr>
                <td style="width: 50%; padding-right: 12px;">
                    <div
                        style="background:#f8f8f8; border:1px solid #ddd; border-radius:4px; padding:12px 16px; text-align:center;">
                        <div class="label"
                            style="font-size:10px; color:#777; text-transform:uppercase; letter-spacing:0.3px;">Total
                            nilai</div>
                        <div style="font-size:28px; font-weight:bold; margin-top:4px;">
                            {{ number_format($nilaiGuru->total_nilai, 2) }}</div>
                    </div>
                </td>
                <td style="width: 50%; padding-left: 12px;">
                    <div
                        style="background:#f8f8f8; border:1px solid #ddd; border-radius:4px; padding:12px 16px; text-align:center;">
                        <div class="label"
                            style="font-size:10px; color:#777; text-transform:uppercase; letter-spacing:0.3px;">Predikat
                        </div>
                        <div style="font-size:28px; font-weight:bold; margin-top:4px;">{{ $nilaiGuru->predikat }}</div>
                    </div>
                </td>
            </tr>
        </table>

        {{-- ── Catatan Admin ── --}}
        @if ($nilaiGuru->catatan_admin)
            <div class="section-title">Catatan Admin</div>
            <div class="catatan-box">{{ $nilaiGuru->catatan_admin }}</div>
        @endif

        {{-- ── TTD ── --}}
        <table style="width:100%; margin-top: 8px;">
            <tr>
                <td style="width:33%; text-align:center; vertical-align:top; padding: 0 8px;">
                    <p style="font-size:11px; color:#555; margin-bottom:56px;">Guru yang bersangkutan,</p>
                    <div style="border-top:1px solid #333; padding-top:6px;">
                        <p style="font-size:11.5px; font-weight:bold;">{{ $nilaiGuru->guru->nama_lengkap }}</p>
                        <p style="font-size:10px; color:#777;">NIPY: {{ $nilaiGuru->guru->nipy }}</p>
                    </div>
                </td>
                <td style="width:33%; text-align:center; vertical-align:top; padding: 0 8px;">
                    <p style="font-size:11px; color:#555; margin-bottom:56px;">Mengetahui, Admin</p>
                    <div style="border-top:1px solid #333; padding-top:6px;">
                        <p style="font-size:11.5px; font-weight:bold;">( _________________________ )</p>
                        <p style="font-size:10px; color:#777;">Admin</p>
                    </div>
                </td>
                <td style="width:33%; text-align:center; vertical-align:top; padding: 0 8px;">
                    <p style="font-size:11px; color:#555; margin-bottom:8px;">Diverifikasi oleh,</p>
                    <p style="font-size:10px; color:#777; margin-bottom:40px;">
                        {{ $nilaiGuru->diverifikasi_pada?->format('d M Y') }}
                    </p>
                    <div style="border-top:1px solid #333; padding-top:6px;">
                        <p style="font-size:11.5px; font-weight:bold;">
                            {{ $nilaiGuru->verifikator?->nama_lengkap ?? '—' }}
                        </p>
                        <p style="font-size:10px; color:#777;">Yayasan Al-Huda</p>
                    </div>
                </td>
            </tr>
        </table>

        {{-- ── Footer ── --}}
        <div class="footer">
            Dokumen ini dicetak otomatis oleh sistem E-Rapor Al-Huda pada {{ now()->format('d/m/Y H:i') }}
            &nbsp;·&nbsp; Sah tanpa tanda tangan basah jika diverifikasi secara digital
        </div>

    </div>
</body>

</html>