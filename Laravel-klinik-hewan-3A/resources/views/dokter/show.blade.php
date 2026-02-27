@extends('layouts.app')

@section('title', 'Detail Dokter Hewan')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="bi bi-hospital"></i> Detail Dokter Hewan</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="200">ID Dokter</th>
                        <td>: {{ $dokter->id_dokter }}</td>
                    </tr>
                    <tr>
                        <th>Nama Dokter</th>
                        <td>: {{ $dokter->nama_dokter }}</td>
                    </tr>
                    <tr>
                        <th>No. SIP</th>
                        <td>: 
                            @if($dokter->no_sip)
                                <span class="badge bg-success">{{ $dokter->no_sip }}</span>
                            @else
                                <span class="text-muted">Belum diisi</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Terdaftar Pada</th>
                        <td>: {{ $dokter->created_at->format('d M Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Riwayat Pemeriksaan -->
        <div class="card mt-4">
            <div class="card-header bg-secondary text-white">
                <h6 class="mb-0"><i class="bi bi-clipboard2-pulse"></i> Riwayat Pemeriksaan</h6>
            </div>
            <div class="card-body">
                @if($dokter->pemeriksaan->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>ID Pemeriksaan</th>
                                    <th>Tanggal</th>
                                    <th>Diagnosa</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dokter->pemeriksaan->take(10) as $pemeriksaan)
                                    <tr>
                                        <td>{{ $pemeriksaan->id_pemeriksaan }}</td>
                                        <td>{{ $pemeriksaan->tanggal_pemeriksaan ? \Carbon\Carbon::parse($pemeriksaan->tanggal_pemeriksaan)->format('d M Y') : '-' }}</td>
                                        <td>{{ Str::limit($pemeriksaan->diagnosa ?? '-', 50) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($dokter->pemeriksaan->count() > 10)
                        <p class="text-muted small">Menampilkan 10 dari {{ $dokter->pemeriksaan->count() }} pemeriksaan</p>
                    @endif
                @else
                    <p class="text-muted text-center py-3">
                        <i class="bi bi-inbox fs-3"></i><br>
                        Belum ada pemeriksaan yang dilakukan
                    </p>
                @endif
            </div>
        </div>

        <div class="mt-4">
            <a href="{{ route('dokter-hewan.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
            <a href="{{ route('dokter-hewan.edit', $dokter->id_dokter) }}" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Edit
            </a>
        </div>
    </div>
</div>
@endsection
