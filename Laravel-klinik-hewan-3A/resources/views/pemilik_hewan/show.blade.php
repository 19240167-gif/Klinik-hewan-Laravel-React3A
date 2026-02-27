@extends('layouts.app')

@section('title', 'Detail Pemilik Hewan')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="bi bi-person-badge"></i> Detail Pemilik Hewan</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="200">ID Pemilik</th>
                        <td>: {{ $pemilikHewan->id_pemilik }}</td>
                    </tr>
                    <tr>
                        <th>Nama Pemilik</th>
                        <td>: {{ $pemilikHewan->nama_pemilik }}</td>
                    </tr>
                    <tr>
                        <th>No. Telepon</th>
                        <td>: {{ $pemilikHewan->no_tlp ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Alamat</th>
                        <td>: {{ $pemilikHewan->alamat ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Jenis Pendaftaran</th>
                        <td>: 
                            <span class="badge bg-{{ $pemilikHewan->jenis_pendaftaran == 'online' ? 'success' : 'secondary' }}">
                                {{ ucfirst($pemilikHewan->jenis_pendaftaran) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Terdaftar Sejak</th>
                        <td>: {{ $pemilikHewan->created_at->format('d M Y H:i') }}</td>
                    </tr>
                </table>

                <div class="d-flex gap-2">
                    <a href="{{ route('pemilik-hewan.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                    <a href="{{ route('pemilik-hewan.edit', $pemilikHewan->id_pemilik) }}" class="btn btn-warning">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                </div>
            </div>
        </div>

        <!-- Daftar Hewan -->
        <div class="card mt-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="bi bi-award"></i> Daftar Hewan yang Dimiliki</h6>
                <a href="{{ route('hewan.create') }}?pemilik={{ $pemilikHewan->id_pemilik }}" class="btn btn-sm btn-primary">
                    <i class="bi bi-plus"></i> Tambah Hewan
                </a>
            </div>
            <div class="card-body">
                @if($pemilikHewan->hewan->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nama</th>
                                    <th>Jenis</th>
                                    <th>Kelamin</th>
                                    <th>Umur</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pemilikHewan->hewan as $hewan)
                                    <tr>
                                        <td>{{ $hewan->id_hewan }}</td>
                                        <td><strong>{{ $hewan->nama_hewan }}</strong></td>
                                        <td>{{ $hewan->jenis_hewan }}</td>
                                        <td>
                                            <span class="badge bg-{{ $hewan->jenis_kelamin == 'jantan' ? 'primary' : 'danger' }} badge-sm">
                                                <i class="bi bi-{{ $hewan->jenis_kelamin == 'jantan' ? 'gender-male' : 'gender-female' }}"></i>
                                                {{ ucfirst($hewan->jenis_kelamin) }}
                                            </span>
                                        </td>
                                        <td>{{ $hewan->umur ?? '-' }} tahun</td>
                                        <td>
                                            <a href="{{ route('hewan.show', $hewan->id_hewan) }}" class="btn btn-sm btn-info">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-3">
                        <i class="bi bi-inbox fs-1 text-muted"></i>
                        <p class="text-muted mb-2">Belum ada hewan terdaftar</p>
                        <a href="{{ route('hewan.create') }}?pemilik={{ $pemilikHewan->id_pemilik }}" class="btn btn-sm btn-primary">
                            <i class="bi bi-plus-circle"></i> Tambah Hewan Pertama
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Riwayat Pendaftaran -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-clock-history"></i> Riwayat Pendaftaran</h6>
            </div>
            <div class="card-body">
                @if($pemilikHewan->pendaftaran->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($pemilikHewan->pendaftaran as $pendaftaran)
                            <div class="list-group-item px-0">
                                <div class="d-flex justify-content-between">
                                    <small class="text-muted">{{ $pendaftaran->tanggal_daftar->format('d M Y') }}</small>
                                    <span class="badge bg-{{ $pendaftaran->status == 'selesai' ? 'success' : 'warning' }}">
                                        {{ $pendaftaran->status }}
                                    </span>
                                </div>
                                <p class="mb-0 small">{{ Str::limit($pendaftaran->keluhan, 50) }}</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted mb-0 small">Belum ada riwayat pendaftaran</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
