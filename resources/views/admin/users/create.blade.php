@extends('layouts.app')

@section('title', 'Tambah User')

@section('content')

    <style>
        .role-card {
            border: 1.5px solid #E5E7EB;
            border-radius: 10px;
            padding: 14px;
            cursor: pointer;
            text-align: center;
            transition: all .15s;
        }

        .role-card:hover {
            border-color: #6366F1;
            background: #F5F3FF;
        }

        .role-card.selected {
            border-color: #6366F1;
            background: #EEF2FF;
        }

        .role-card .role-icon {
            font-size: 24px;
            margin-bottom: 6px;
        }

        .role-card .role-label {
            font-size: 13px;
            font-weight: 500;
            color: #374151;
        }

        .status-toggle {
            display: flex;
            gap: 8px;
        }

        .status-opt {
            flex: 1;
            border: 1.5px solid #E5E7EB;
            border-radius: 8px;
            padding: 9px;
            text-align: center;
            cursor: pointer;
            font-size: 13px;
            font-weight: 500;
            color: #6B7280;
            transition: all .15s;
        }

        .status-opt:hover {
            border-color: #9CA3AF;
        }

        .status-opt.sel-aktif {
            border-color: #10B981;
            background: #F0FDF4;
            color: #15803D;
        }

        .status-opt.sel-nonaktif {
            border-color: #EF4444;
            background: #FEF2F2;
            color: #B91C1C;
        }

        #status-input,
        #role-input {
            display: none;
        }

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

        .pwd-strength {
            height: 3px;
            border-radius: 99px;
            background: #E5E7EB;
            margin-top: 6px;
            overflow: hidden;
        }

        .pwd-bar {
            height: 100%;
            width: 0;
            transition: width .3s, background .3s;
            border-radius: 99px;
        }
    </style>

    @section('breadcrumb')

        <li class="breadcrumb-item">
            Home
        </li>

        <li class="breadcrumb-item">
            User
        </li>

        <li class="breadcrumb-item active">
            Create User
        </li>

    @endsection

    <!-- <a href="{{ route('admin.users.index') }}" class="btn btn-light btn-sm">← Kembali</a> -->
    <div class="d-flex align-items-center gap-3 mb-4">
        <div>
            <h4 class="mb-0 fw-semibold">Tambah User</h4>
            <small class="text-muted">Isi data lengkap untuk membuat akun baru</small>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger mb-4">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.users.store') }}" method="POST" id="mainForm">
        @csrf

        {{-- Hidden inputs yang dikirim ke server --}}
        <input type="hidden" name="role" id="role-input" value="admin">
        <input type="hidden" name="status" id="status-input" value="aktif">

        {{-- Role & Status --}}
        <div class="section-card">
            <div class="section-title">Role & Status</div>

            <label class="form-label">Pilih Role</label>
            <div class="row g-2 mb-3">
                <div class="col-4">
                    <div class="role-card selected" id="r-admin" onclick="selectRole('admin')">
                        <div class="role-icon">🛡️</div>
                        <div class="role-label">Admin</div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="role-card" id="r-yayasan" onclick="selectRole('yayasan')">
                        <div class="role-icon">🏫</div>
                        <div class="role-label">Yayasan</div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="role-card" id="r-guru" onclick="selectRole('guru')">
                        <div class="role-icon">📚</div>
                        <div class="role-label">Guru</div>
                    </div>
                </div>
            </div>

            <label class="form-label">Status Akun</label>
            <div class="status-toggle">
                <div class="status-opt sel-aktif" id="s-aktif" onclick="selectStatus('aktif')">✓ Aktif</div>
                <div class="status-opt" id="s-nonaktif" onclick="selectStatus('tidak_aktif')">Tidak Aktif</div>
            </div>
        </div>

        {{-- Info Personal --}}
        <div class="section-card">
            <div class="section-title">Informasi Personal</div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">NIPY</label>
                    <input type="text" name="nipy" class="form-control" placeholder="cth. 100001" value="{{ old('nipy') }}"
                        required>
                </div>
                <div class="col-md-6" id="jabatan-wrap">
                    <label class="form-label">Jabatan</label>
                    <input type="text" name="jabatan" class="form-control" placeholder="cth. Wali Kelas"
                        value="{{ old('jabatan') }}">
                </div>
            </div>
            <div class="mt-3">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="nama_lengkap" class="form-control" placeholder="Nama sesuai dokumen"
                    value="{{ old('nama_lengkap') }}" required>
            </div>
            <div class="mt-3">
                <label class="form-label">Email</label>
                <input type="text" name="email" class="form-control" placeholder="example@domain.com"
                    value="{{ old('email') }}" required>
            </div>
        </div>

        {{-- Password --}}
        <div class="section-card">
            <div class="section-title">Password</div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" id="pwd" placeholder="Min. 8 karakter"
                        oninput="checkStrength(this.value)" required>
                    <div class="pwd-strength">
                        <div class="pwd-bar" id="pwd-bar"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password"
                        required>
                </div>
            </div>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary px-4">Simpan User</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </form>

    <script>
        function selectRole(r) {
            ['admin', 'yayasan', 'guru'].forEach(x => document.getElementById('r-' + x).classList.remove('selected'));
            document.getElementById('r-' + r).classList.add('selected');
            document.getElementById('role-input').value = r;
            document.getElementById('jabatan-wrap').style.opacity = r === 'guru' ? '1' : '0.4';
        }
        function selectStatus(s) {
            document.getElementById('s-aktif').className = 'status-opt' + (s === 'aktif' ? ' sel-aktif' : '');
            document.getElementById('s-nonaktif').className = 'status-opt' + (s !== 'aktif' ? ' sel-nonaktif' : '');
            document.getElementById('status-input').value = s;
        }
        function checkStrength(v) {
            var score = [v.length >= 8, /[A-Z]/.test(v), /[0-9]/.test(v), /[^a-zA-Z0-9]/.test(v)].filter(Boolean).length;
            var colors = ['#EF4444', '#F59E0B', '#F59E0B', '#10B981', '#10B981'];
            var bar = document.getElementById('pwd-bar');
            bar.style.width = (score * 25) + '%';
            bar.style.background = colors[score];
        }
        selectRole('admin');
    </script>

@endsection