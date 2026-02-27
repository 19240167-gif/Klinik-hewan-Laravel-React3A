@extends('layouts.app')

@section('title', 'Riwayat Pembayaran')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-receipt"></i> Riwayat Pembayaran</h2>
    <a href="{{ route('pembayaran.pending') }}" class="btn btn-primary">
        <i class="bi bi-clock-history"></i> Lihat Belum Dibayar
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>ID Pembayaran</th>
                        <th>Tanggal Bayar</th>
                        <th>ID Pemeriksaan</th>
                        <th>Pemilik Hewan</th>
                        <th>Metode Bayar</th>
                        <th>Total Bayar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pembayarans as $pembayaran)
                        <tr>
                            <td>{{ $pembayaran->id_pembayaran }}</td>
                            <td>{{ $pembayaran->tanggal_bayar ? $pembayaran->tanggal_bayar->format('d/m/Y H:i') : '-' }}</td>
                            <td>{{ $pembayaran->id_pemeriksaan }}</td>
                            <td>{{ $pembayaran->pemeriksaan->pendaftaran->pemilikHewan->nama_pemilik ?? '-' }}</td>
                            <td>
                                <span class="badge bg-{{ $pembayaran->metode_bayar == 'Cash' ? 'success' : ($pembayaran->metode_bayar == 'Transfer' ? 'primary' : 'info') }}">
                                    {{ $pembayaran->metode_bayar }}
                                </span>
                            </td>
                            <td><strong>Rp {{ number_format($pembayaran->total_bayar, 0, ',', '.') }}</strong></td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('pembayaran.show', $pembayaran->id_pembayaran) }}" 
                                       class="btn btn-info" title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if(auth()->user()->role == 'admin')
                                        <form action="{{ route('pembayaran.destroy', $pembayaran->id_pembayaran) }}" 
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Yakin ingin menghapus data pembayaran ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="bi bi-inbox fs-1 text-muted"></i>
                                <p class="text-muted">Belum ada data pembayaran</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">
    {{ $pembayarans->links() }}
</div>
@endsection
