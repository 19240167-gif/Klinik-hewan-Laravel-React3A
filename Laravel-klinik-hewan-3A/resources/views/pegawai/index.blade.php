@extends('layouts.app')

@section('title', 'Data Pegawai')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-person-badge"></i> Data Pegawai</h2>
    <a href="{{ route('pegawai.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Tambah Pegawai
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Nama Pegawai</th>
                        <th>Jenis Kelamin</th>
                        <th>No. Telepon</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pegawais as $pegawai)
                        <tr>
                            <td>{{ $pegawai->id_pegawai }}</td>
                            <td>{{ $pegawai->nama_pegawai }}</td>
                            <td>
                                @if($pegawai->jenis_kelamin == 'laki-laki')
                                    <span class="badge bg-primary">Laki-laki</span>
                                @else
                                    <span class="badge bg-info">Perempuan</span>
                                @endif
                            </td>
                            <td>{{ $pegawai->no_telepon_pegawai ?? '-' }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('pegawai.show', $pegawai->id_pegawai) }}" 
                                       class="btn btn-info" title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('pegawai.edit', $pegawai->id_pegawai) }}" 
                                       class="btn btn-warning" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('pegawai.destroy', $pegawai->id_pegawai) }}" 
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Yakin ingin menghapus data pegawai ini?')">
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
                            <td colspan="5" class="text-center py-4">
                                <i class="bi bi-inbox fs-1 text-muted"></i>
                                <p class="text-muted">Belum ada data pegawai</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">
    {{ $pegawais->links() }}
</div>
@endsection
