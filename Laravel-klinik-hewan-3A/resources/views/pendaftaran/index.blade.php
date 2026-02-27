@extends('layouts.app')

@section('title', 'Data Pendaftaran')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-clipboard-check"></i> Data Pendaftaran Pemeriksaan</h2>
    <a href="{{ route('pendaftaran.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Tambah Pendaftaran
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Tanggal</th>
                        <th>Pemilik Hewan</th>
                        <th>Nama Hewan</th>
                        <th>Pegawai</th>
                        <th>Status</th>
                        <th>Keluhan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendaftarans as $pendaftaran)
                        <tr>
                            <td>{{ $pendaftaran->id_pendaftaran }}</td>
                            <td>{{ $pendaftaran->tanggal_daftar->format('d/m/Y') }}</td>
                            <td>{{ $pendaftaran->pemilikHewan->nama_pemilik ?? '-' }}</td>
                            <td>{{ $pendaftaran->hewan->nama_hewan ?? '-' }}</td>
                            <td>{{ $pendaftaran->pegawai->nama_pegawai ?? '-' }}</td>
                            <td>
                                <span class="badge bg-{{ $pendaftaran->status == 'selesai' ? 'success' : 'warning' }}">
                                    {{ ucfirst($pendaftaran->status) }}
                                </span>
                            </td>
                            <td>{{ Str::limit($pendaftaran->keluhan ?? '-', 40) }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('pendaftaran.show', $pendaftaran->id_pendaftaran) }}" 
                                       class="btn btn-info" title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('pendaftaran.edit', $pendaftaran->id_pendaftaran) }}" 
                                       class="btn btn-warning" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('pendaftaran.destroy', $pendaftaran->id_pendaftaran) }}" 
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <i class="bi bi-inbox fs-1 text-muted"></i>
                                <p class="text-muted">Belum ada data pendaftaran</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">
    {{ $pendaftarans->links() }}
</div>
@endsection
