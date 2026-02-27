@extends('layouts.app')

@section('title', 'Data Pemilik Hewan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-people"></i> Data Pemilik Hewan (Offline)</h2>
    <a href="{{ route('pemilik-hewan.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Tambah Pemilik Hewan
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Nama Pemilik</th>
                        <th>No. Telepon</th>
                        <th>Alamat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pemilikHewans as $pemilik)
                        <tr>
                            <td>{{ $pemilik->id_pemilik }}</td>
                            <td>{{ $pemilik->nama_pemilik }}</td>
                            <td>{{ $pemilik->no_tlp ?? '-' }}</td>
                            <td>{{ Str::limit($pemilik->alamat ?? '-', 50) }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('pemilik-hewan.show', $pemilik->id_pemilik) }}" 
                                       class="btn btn-info" title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('pemilik-hewan.edit', $pemilik->id_pemilik) }}" 
                                       class="btn btn-warning" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('pemilik-hewan.destroy', $pemilik->id_pemilik) }}" 
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
                            <td colspan="5" class="text-center py-4">
                                <i class="bi bi-inbox fs-1 text-muted"></i>
                                <p class="text-muted">Belum ada data pemilik hewan</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">
    {{ $pemilikHewans->links() }}
</div>
@endsection
