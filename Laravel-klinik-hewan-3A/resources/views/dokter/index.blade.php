@extends('layouts.app')

@section('title', 'Data Dokter Hewan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-hospital"></i> Data Dokter Hewan</h2>
    <a href="{{ route('dokter-hewan.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Tambah Dokter
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Nama Dokter</th>
                        <th>No. SIP</th>
                        <th>Terdaftar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dokters as $dokter)
                        <tr>
                            <td>{{ $dokter->id_dokter }}</td>
                            <td>
                                <i class="bi bi-person-circle text-success"></i>
                                {{ $dokter->nama_dokter }}
                            </td>
                            <td>
                                @if($dokter->no_sip)
                                    <span class="badge bg-success">{{ $dokter->no_sip }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ $dokter->created_at->format('d M Y') }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('dokter-hewan.show', $dokter->id_dokter) }}" 
                                       class="btn btn-info" title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('dokter-hewan.edit', $dokter->id_dokter) }}" 
                                       class="btn btn-warning" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('dokter-hewan.destroy', $dokter->id_dokter) }}" 
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Yakin ingin menghapus data dokter ini?')">
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
                            <td colspan="6" class="text-center py-4">
                                <i class="bi bi-inbox fs-1 text-muted"></i>
                                <p class="text-muted">Belum ada data dokter hewan</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">
    {{ $dokters->links() }}
</div>
@endsection
