@extends('layouts.app')

@section('title', 'Detail Pegawai')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="bi bi-person-badge"></i> Detail Pegawai</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="200">ID Pegawai</th>
                        <td>: {{ $pegawai->id_pegawai }}</td>
                    </tr>
                    <tr>
                        <th>Nama Pegawai</th>
                        <td>: {{ $pegawai->nama_pegawai }}</td>
                    </tr>
                    <tr>
                        <th>Jenis Kelamin</th>
                        <td>: 
                            @if($pegawai->jenis_kelamin == 'laki-laki')
                                <span class="badge bg-primary">Laki-laki</span>
                            @else
                                <span class="badge bg-info">Perempuan</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>No. Telepon</th>
                        <td>: {{ $pegawai->no_telepon_pegawai ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Terdaftar Pada</th>
                        <td>: {{ $pegawai->created_at->format('d M Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Riwayat Pendaftaran -->
        <div class="card mt-4">
            <div class="card-header bg-secondary text-white">
                <h6 class="mb-0"><i class="bi bi-clipboard-check"></i> Riwayat Pendaftaran yang Ditangani</h6>
            </div>
            <div class="card-body">
                @if($pegawai->pendaftaran->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>ID Pendaftaran</th>
                                    <th>Tanggal</th>
                                    <th>Keluhan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pegawai->pendaftaran->take(10) as $pendaftaran)
                                    <tr>
                                        <td>{{ $pendaftaran->id_pendaftaran }}</td>
                                        <td>{{ $pendaftaran->tanggal_pendaftaran ? \Carbon\Carbon::parse($pendaftaran->tanggal_pendaftaran)->format('d M Y') : '-' }}</td>
                                        <td>{{ Str::limit($pendaftaran->keluhan ?? '-', 50) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($pegawai->pendaftaran->count() > 10)
                        <p class="text-muted small">Menampilkan 10 dari {{ $pegawai->pendaftaran->count() }} pendaftaran</p>
                    @endif
                @else
                    <p class="text-muted text-center py-3">
                        <i class="bi bi-inbox fs-3"></i><br>
                        Belum ada pendaftaran yang ditangani
                    </p>
                @endif
            </div>
        </div>

        <div class="mt-4">
            <a href="{{ route('pegawai.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
            <a href="{{ route('pegawai.edit', $pegawai->id_pegawai) }}" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Edit
            </a>
        </div>
    </div>
</div>
@endsection
