@extends('layouts.app')

@section('title', 'Detail Pendaftaran')

@section('content')
<div class="card">
    <div class="card-header bg-info text-white">
        <h5 class="mb-0"><i class="bi bi-clipboard-check"></i> Detail Pendaftaran</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h6 class="text-muted">Informasi Pendaftaran</h6>
                <table class="table table-borderless">
                    <tr>
                        <th width="180">ID Pendaftaran</th>
                        <td>: {{ $pendaftaran->id_pendaftaran }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Daftar</th>
                        <td>: {{ $pendaftaran->tanggal_daftar->format('d M Y') }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>: 
                            <span class="badge bg-{{ $pendaftaran->status == 'selesai' ? 'success' : 'warning' }}">
                                {{ ucfirst($pendaftaran->status) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Keluhan</th>
                        <td>: {{ $pendaftaran->keluhan ?? '-' }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <h6 class="text-muted">Informasi Terkait</h6>
                <table class="table table-borderless">
                    <tr>
                        <th width="180">Pemilik Hewan</th>
                        <td>: {{ $pendaftaran->pemilikHewan->nama_pemilik ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>No. Telepon</th>
                        <td>: {{ $pendaftaran->pemilikHewan->no_tlp ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Pegawai</th>
                        <td>: {{ $pendaftaran->pegawai->nama_pegawai ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Dibuat</th>
                        <td>: {{ $pendaftaran->created_at->format('d M Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        @if($pendaftaran->pemeriksaan)
        <hr>
        <h6 class="text-muted">Hasil Pemeriksaan</h6>
        <div class="card bg-light">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p class="mb-1"><strong>Dokter:</strong> {{ $pendaftaran->pemeriksaan->dokterHewan->nama_dokter ?? '-' }}</p>
                        <p class="mb-1"><strong>Tanggal Periksa:</strong> {{ $pendaftaran->pemeriksaan->tanggal_periksa->format('d M Y') }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1"><strong>Diagnosa:</strong> {{ $pendaftaran->pemeriksaan->diagnosa ?? '-' }}</p>
                        <p class="mb-1"><strong>Tindakan:</strong> {{ $pendaftaran->pemeriksaan->tindakan ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>
        @else
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> Belum ada pemeriksaan untuk pendaftaran ini
        </div>
        @endif

        <div class="d-flex gap-2 mt-3">
            <a href="{{ route('pendaftaran.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
            <a href="{{ route('pendaftaran.edit', $pendaftaran->id_pendaftaran) }}" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Edit
            </a>
        </div>
    </div>
</div>
@endsection
