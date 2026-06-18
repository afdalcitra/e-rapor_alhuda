@extends('layouts.app')

@section('title', 'Edit Nilai Guru')

@section('content')

    <div class="d-flex align-items-center gap-3 mb-4">
        <div>
            <h4 class="mb-0 fw-semibold">Edit Nilai Guru</h4>
            <small class="text-muted">Perbarui nilai untuk {{ $nilaiGuru->guru->nama_lengkap }}</small>
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

    <form action="{{ route('admin.nilai-guru.update', $nilaiGuru) }}" method="POST">
        @csrf
        @method('PUT')
        @include('admin.nilai-guru.form')
    </form>

@endsection