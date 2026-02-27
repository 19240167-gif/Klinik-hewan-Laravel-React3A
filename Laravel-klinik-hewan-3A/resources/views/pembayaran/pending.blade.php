@extends('layouts.app')

@section('title', 'Pemeriksaan Belum Dibayar')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-clock-history"></i> Pemeriksaan Belum Dibayar</h2>
    <a href="{{ route('pembayaran.index') }}" class="btn btn-secondary">
        <i class="bi bi-list"></i> Riwayat Pembayaran
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
                        <th>ID Pemeriksaan</th>
                        <th>Tanggal Periksa</th>
                        <th>Pemilik Hewan</th>
                        <th>Nama Hewan</th>
                        <th>Dokter</th>
                        <th>Jumlah Obat</th>
                        <th>Total Biaya</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pemeriksaans as $pemeriksaan)
                        @php
                            $biayaTindakan = $pemeriksaan->biaya_tindakan ?? 0;
                            $biayaObat = 0;
                            foreach($pemeriksaan->obats as $obat) {
                                $biayaObat += $obat->harga_obat * $obat->pivot->jumlah;
                            }
                            $totalBiaya = $biayaTindakan + $biayaObat;
                        @endphp
                        <tr>
                            <td>{{ $pemeriksaan->id_pemeriksaan }}</td>
                            <td>{{ $pemeriksaan->tanggal_periksa ? $pemeriksaan->tanggal_periksa->format('d/m/Y') : '-' }}</td>
                            <td>{{ $pemeriksaan->pendaftaran->pemilikHewan->nama_pemilik ?? '-' }}</td>
                            <td>{{ $pemeriksaan->pendaftaran->hewan->nama_hewan ?? '-' }}</td>
                            <td>{{ $pemeriksaan->dokterHewan->nama_dokter ?? '-' }}</td>
                            <td>{{ $pemeriksaan->obats->count() }} jenis</td>
                            <td><strong>Rp {{ number_format($totalBiaya, 0, ',', '.') }}</strong></td>
                            <td>
                                <a href="{{ route('pembayaran.create', ['pemeriksaan' => $pemeriksaan->id_pemeriksaan]) }}" 
                                   class="btn btn-success btn-sm">
                                    <i class="bi bi-cash"></i> Proses Bayar
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <i class="bi bi-check-circle fs-1 text-success"></i>
                                <p class="text-muted">Semua pemeriksaan sudah dibayar</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">
    {{ $pemeriksaans->links() }}
</div>
@endsection
