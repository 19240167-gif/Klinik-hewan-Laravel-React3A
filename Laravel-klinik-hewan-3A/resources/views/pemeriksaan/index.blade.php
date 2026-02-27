@extends('layouts.app')

@section('title', 'Daftar Pendaftaran yang Menunggu')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-clipboard2-pulse"></i> Daftar Pendaftaran yang Menunggu Pemeriksaan</h2>
</div>

<div class="card">
    <div class="card-body">
        @if($pendaftarans->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID Pendaftaran</th>
                            <th>Tanggal Daftar</th>
                            <th>Pemilik Hewan</th>
                            <th>Nama Hewan</th>
                            <th>Keluhan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendaftarans as $pendaftaran)
                            <tr>
                                <td>{{ $pendaftaran->id_pendaftaran }}</td>
                                <td>{{ $pendaftaran->tanggal_daftar->format('d/m/Y') }}</td>
                                <td>{{ $pendaftaran->pemilikHewan->nama_pemilik ?? '-' }}</td>
                                <td>{{ $pendaftaran->hewan->nama_hewan ?? '-' }}</td>
                                <td>{{ Str::limit($pendaftaran->keluhan ?? '-', 50) }}</td>
                                <td>
                                    <span class="badge bg-warning text-dark">
                                        <i class="bi bi-clock"></i> Menunggu
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        @if(in_array(auth()->user()->role, ['admin', 'dokter']))
                                            <a href="{{ route('pemeriksaan.create', ['pendaftaran' => $pendaftaran->id_pendaftaran]) }}" 
                                               class="btn btn-success" 
                                               title="Periksa">
                                                <i class="bi bi-clipboard2-pulse"></i> Periksa
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-inbox fs-1 text-muted"></i>
                <p class="text-muted mt-3">Tidak ada pendaftaran yang menunggu pemeriksaan</p>
            </div>
        @endif
    </div>
</div>

<div class="mt-3">
    {{ $pendaftarans->links() }}
</div>
@endsection
