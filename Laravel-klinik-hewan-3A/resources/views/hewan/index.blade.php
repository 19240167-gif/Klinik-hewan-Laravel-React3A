@extends('layouts.app')

@section('title', 'Data Hewan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-award"></i> Data Hewan</h2>
    <a href="{{ route('hewan.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Tambah Hewan
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Nama Hewan</th>
                        <th>Jenis</th>
                        <th>Kelamin</th>
                        <th>Umur</th>
                        <th>Pemilik</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($hewans as $hewan)
                        <tr>
                            <td>{{ $hewan->id_hewan }}</td>
                            <td>
                                <strong>{{ $hewan->nama_hewan }}</strong>
                            </td>
                            <td>{{ $hewan->jenis_hewan }}</td>
                            <td>
                                <span class="badge bg-{{ $hewan->jenis_kelamin == 'jantan' ? 'primary' : 'danger' }}">
                                    <i class="bi bi-{{ $hewan->jenis_kelamin == 'jantan' ? 'gender-male' : 'gender-female' }}"></i>
                                    {{ ucfirst($hewan->jenis_kelamin) }}
                                </span>
                            </td>
                            <td>{{ $hewan->umur ? $hewan->umur . ' tahun' : '-' }}</td>
                            <td>
                                <a href="{{ route('pemilik-hewan.show', $hewan->id_pemilik) }}" class="text-decoration-none">
                                    {{ $hewan->pemilikHewan->nama_pemilik ?? '-' }}
                                </a>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('hewan.show', $hewan->id_hewan) }}" 
                                       class="btn btn-info" title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('hewan.edit', $hewan->id_hewan) }}" 
                                       class="btn btn-warning" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('hewan.destroy', $hewan->id_hewan) }}" 
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Yakin ingin menghapus data hewan {{ $hewan->nama_hewan }}?')">
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
                            <td colspan="7" class="text-center py-4">
                                <i class="bi bi-inbox fs-1 text-muted"></i>
                                <p class="text-muted">Belum ada data hewan</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">
    {{ $hewans->links() }}
</div>
@endsection
