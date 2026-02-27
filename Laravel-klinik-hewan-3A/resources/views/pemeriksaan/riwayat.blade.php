@extends('layouts.app')

@section('title', 'Riwayat Pemeriksaan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-clock-history"></i> Riwayat Pemeriksaan</h2>
    <a href="{{ route('pemeriksaan.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali ke Daftar Tunggu
    </a>
</div>

<div class="card">
    <div class="card-body">
        @if($pemeriksaans->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID Pemeriksaan</th>
                            <th>Tanggal Periksa</th>
                            <th>Pemilik Hewan</th>
                            <th>Nama Hewan</th>
                            <th>Diagnosa</th>
                            <th>Dokter</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pemeriksaans as $pemeriksaan)
                            <tr>
                                <td>{{ $pemeriksaan->id_pemeriksaan }}</td>
                                <td>{{ $pemeriksaan->tanggal_periksa->format('d/m/Y') }}</td>
                                <td>{{ $pemeriksaan->pendaftaran->pemilikHewan->nama_pemilik ?? '-' }}</td>
                                <td>{{ $pemeriksaan->pendaftaran->hewan->nama_hewan ?? '-' }}</td>
                                <td>{{ Str::limit($pemeriksaan->diagnosa ?? '-', 50) }}</td>
                                <td>{{ $pemeriksaan->dokterHewan->nama_dokter ?? '-' }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('pemeriksaan.show', $pemeriksaan->id_pemeriksaan) }}" 
                                           class="btn btn-info" 
                                           title="Lihat Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        @if(auth()->user()->role === 'dokter' && $pemeriksaan->dokterHewan->nama_dokter === auth()->user()->name || auth()->user()->role === 'admin')
                                            <a href="{{ route('pemeriksaan.edit', $pemeriksaan->id_pemeriksaan) }}" 
                                               class="btn btn-warning" 
                                               title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button type="button" class="btn btn-danger" title="Hapus"
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#deleteModal{{ $pemeriksaan->id_pemeriksaan }}">
                                                <i class="bi bi-trash"></i>
                                            </button>
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
                <p class="text-muted mt-3">Belum ada riwayat pemeriksaan</p>
            </div>
        @endif
    </div>
</div>

<div class="mt-3">
    {{ $pemeriksaans->links() }}
</div>

<!-- Modal Hapus untuk setiap pemeriksaan -->
@if($pemeriksaans->count() > 0)
    @foreach($pemeriksaans as $pemeriksaan)
        @if(auth()->user()->role === 'admin' || (auth()->user()->role === 'dokter' && optional($pemeriksaan->dokterHewan)->nama_dokter === auth()->user()->name))
        <div class="modal fade" id="deleteModal{{ $pemeriksaan->id_pemeriksaan }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title">
                            <i class="bi bi-exclamation-triangle"></i> Hapus Pemeriksaan {{ $pemeriksaan->id_pemeriksaan }}
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        @if($pemeriksaan->pembayaran)
                            <div class="alert alert-danger mb-0">
                                <i class="bi bi-x-circle"></i> <strong>Tidak dapat dihapus!</strong><br>
                                Pemeriksaan ini sudah memiliki data pembayaran 
                                <strong>({{ $pemeriksaan->pembayaran->id_pembayaran }})</strong>. 
                                Hapus pembayaran terlebih dahulu sebelum menghapus pemeriksaan.
                            </div>
                        @else
                            <p>Pilih tindakan untuk pemeriksaan <strong>{{ $pemeriksaan->id_pemeriksaan }}</strong>:</p>

                            <div class="d-grid gap-2">
                                <!-- Opsi 1: Kembalikan ke pendaftaran -->
                                <form action="{{ route('pemeriksaan.destroy', $pemeriksaan->id_pemeriksaan) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="aksi" value="kembalikan">
                                    <button type="submit" class="btn btn-warning w-100 text-start">
                                        <i class="bi bi-arrow-counterclockwise"></i> 
                                        <strong>Kembalikan ke Pendaftaran</strong>
                                        <br>
                                        <small class="text-dark">Hapus pemeriksaan, status pendaftaran kembali ke "menunggu"</small>
                                    </button>
                                </form>

                                <!-- Opsi 2: Hapus semua -->
                                <form action="{{ route('pemeriksaan.destroy', $pemeriksaan->id_pemeriksaan) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="aksi" value="hapus_semua">
                                    <button type="submit" class="btn btn-danger w-100 text-start">
                                        <i class="bi bi-trash"></i> 
                                        <strong>Hapus Semua</strong>
                                        <br>
                                        <small>Hapus pemeriksaan beserta data pendaftarannya</small>
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
        @endif
    @endforeach
@endif
@endsection
