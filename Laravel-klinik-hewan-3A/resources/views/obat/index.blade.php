@extends('layouts.app')

@section('title', 'Data Obat')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-capsule"></i> Data Obat</h2>
    <a href="{{ route('obat.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Tambah Obat
    </a>
</div>

<div class="alert alert-info">
    <i class="bi bi-info-circle"></i> <strong>Info:</strong> Stok obat akan berkurang otomatis saat digunakan dalam pemeriksaan. 
    <span class="badge bg-danger">Merah</span> = Stok habis, 
    <span class="badge bg-warning text-dark">Kuning</span> = Stok ≤ 10, 
    <span class="badge bg-success">Hijau</span> = Stok cukup
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>ID Obat</th>
                        <th>Nama Obat</th>
                        <th>Jenis</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Tanggal Kadaluarsa</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($obats as $obat)
                        <tr class="{{ $obat->stok <= 0 ? 'table-danger' : ($obat->stok <= 10 ? 'table-warning' : '') }}">
                            <td>{{ $obat->id_obat }}</td>
                            <td>{{ $obat->nama_obat }}</td>
                            <td>{{ $obat->jenis_obat ?? '-' }}</td>
                            <td>Rp {{ number_format($obat->harga_obat, 0, ',', '.') }}</td>
                            <td>
                                @if($obat->stok <= 0)
                                    <span class="badge bg-danger">Habis</span>
                                @elseif($obat->stok <= 10)
                                    <span class="badge bg-warning text-dark">{{ $obat->stok }}</span>
                                @else
                                    <span class="badge bg-success">{{ $obat->stok }}</span>
                                @endif
                            </td>
                            <td>{{ $obat->tanggal_kadaluarsa ? $obat->tanggal_kadaluarsa->format('d/m/Y') : '-' }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('obat.show', $obat->id_obat) }}" 
                                       class="btn btn-info" title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('obat.edit', $obat->id_obat) }}" 
                                       class="btn btn-warning" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('obat.destroy', $obat->id_obat) }}" 
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
                            <td colspan="7" class="text-center py-4">
                                <i class="bi bi-inbox fs-1 text-muted"></i>
                                <p class="text-muted">Belum ada data obat</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">
    {{ $obats->links() }}
</div>
@endsection
