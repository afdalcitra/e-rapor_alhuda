@extends('layouts.app')

@section('title', 'Tambah Nilai Guru')

@section('content')

    <div class="d-flex align-items-center gap-3 mb-4">
        <div>
            <h4 class="mb-0 fw-semibold">Tambah Nilai Guru</h4>
            <small class="text-muted">Isi nilai per komponen untuk periode terkait</small>
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

    <form action="{{ route('admin.nilai-guru.store') }}" method="POST">
        @csrf
        @include('admin.nilai-guru.form')
    </form>

@endsection