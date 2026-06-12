@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')

    @section('breadcrumb')

        <li class="breadcrumb-item">
            Home
        </li>

        <li class="breadcrumb-item active">
            Dashboard
        </li>

    @endsection

    <div class="mb-4">

        <h2 class="fw-bold">
            Dashboard Admin
        </h2>

        <p class="text-muted">
            Selamat datang kembali, <strong>{{ auth()->user()->nama_lengkap }}</strong> 👋
        </p>

    </div>

    <div class="row g-4">

        <div class="col-md-3">

            <div class="card dashboard-card">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <p class="text-muted mb-1">
                                Total Guru
                            </p>

                            <h2 class="fw-bold">
                                0
                            </h2>

                        </div>

                        <div class="fs-1 text-primary">
                            <i class="bi bi-person-workspace"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card dashboard-card">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <p class="text-muted mb-1">
                                Menunggu Verifikasi
                            </p>

                            <h2 class="fw-bold">
                                0
                            </h2>

                        </div>

                        <div class="fs-1 text-warning">
                            <i class="bi bi-clock-history"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

@endsection