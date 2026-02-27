@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="row mb-4">
    <div class="col">
        <h2><i class="bi bi-speedometer2"></i> Dashboard Admin</h2>
        <p class="text-muted">Selamat datang, {{ auth()->user()->name }}</p>
    </div>
</div>

<div class="row g-3">
    <!-- Card Pemilik Hewan -->
    <div class="col-md-3">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Pemilik Hewan</h6>
                        <h2 class="mb-0">{{ \App\Models\PemilikHewan::count() }}</h2>
                    </div>
                    <i class="bi bi-people fs-1 opacity-50"></i>
                </div>
            </div>
            <div class="card-footer bg-primary bg-opacity-75">
                <a href="{{ route('pemilik-hewan.index') }}" class="text-white text-decoration-none small">
                    Lihat Detail <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Card Hewan -->
    <div class="col-md-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Hewan</h6>
                        <h2 class="mb-0">{{ \App\Models\Hewan::count() }}</h2>
                    </div>
                    <i class="bi bi-award fs-1 opacity-50"></i>
                </div>
            </div>
            <div class="card-footer bg-success bg-opacity-75">
                <a href="{{ route('hewan.index') }}" class="text-white text-decoration-none small">
                    Lihat Detail <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Card Pendaftaran -->
    <div class="col-md-3">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Pendaftaran</h6>
                        <h2 class="mb-0">{{ \App\Models\Pendaftaran::count() }}</h2>
                    </div>
                    <i class="bi bi-clipboard-check fs-1 opacity-50"></i>
                </div>
            </div>
            <div class="card-footer bg-warning bg-opacity-75">
                <a href="{{ route('pendaftaran.index') }}" class="text-white text-decoration-none small">
                    Lihat Detail <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Card Pemeriksaan -->
    <div class="col-md-3">
        <div class="card text-white bg-info">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Pemeriksaan</h6>
                        <h2 class="mb-0">{{ \App\Models\Pemeriksaan::count() }}</h2>
                    </div>
                    <i class="bi bi-clipboard2-pulse fs-1 opacity-50"></i>
                </div>
            </div>
            <div class="card-footer bg-info bg-opacity-75">
                <a href="{{ route('pemeriksaan.index') }}" class="text-white text-decoration-none small">
                    Lihat Detail <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Pendaftaran Hari Ini -->
<div class="row mt-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-calendar-check"></i> Pendaftaran Hari Ini</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Pemilik</th>
                                <th>Status</th>
                                <th>Keluhan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $pendaftaranHariIni = \App\Models\Pendaftaran::whereDate('tanggal_daftar', today())->latest()->limit(5)->get();
                            @endphp
                            @forelse($pendaftaranHariIni as $p)
                                <tr>
                                    <td>{{ $p->id_pendaftaran }}</td>
                                    <td>{{ $p->pemilikHewan->nama_pemilik ?? '-' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $p->status == 'selesai' ? 'success' : 'warning' }}">
                                            {{ $p->status }}
                                        </span>
                                    </td>
                                    <td>{{ Str::limit($p->keluhan ?? '-', 40) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">Belum ada pendaftaran hari ini</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-info-circle"></i> Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('pemilik-hewan.create') }}" class="btn btn-outline-primary">
                        <i class="bi bi-plus-circle"></i> Tambah Pemilik Hewan
                    </a>
                    <a href="{{ route('pendaftaran.create') }}" class="btn btn-outline-warning">
                        <i class="bi bi-plus-circle"></i> Tambah Pendaftaran
                    </a>
                    <a href="{{ route('pemeriksaan.create') }}" class="btn btn-outline-info">
                        <i class="bi bi-plus-circle"></i> Tambah Pemeriksaan
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
