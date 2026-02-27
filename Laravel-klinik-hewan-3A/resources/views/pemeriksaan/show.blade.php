@extends('layouts.app')

@section('title', 'Detail Pemeriksaan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-clipboard2-pulse"></i> Detail Pemeriksaan</h2>
    <a href="{{ route('pemeriksaan.riwayat') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="row">
    <!-- Informasi Pemeriksaan -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-info-circle"></i> Informasi Pemeriksaan</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="40%">ID Pemeriksaan</th>
                        <td>: {{ $pemeriksaan->id_pemeriksaan }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Periksa</th>
                        <td>: {{ $pemeriksaan->tanggal_periksa ? $pemeriksaan->tanggal_periksa->format('d M Y') : '-' }}</td>
                    </tr>
                    <tr>
                        <th>Dokter Pemeriksa</th>
                        <td>: {{ $pemeriksaan->dokterHewan->nama_dokter ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Status Pembayaran</th>
                        <td>: 
                            @if($pemeriksaan->pembayaran)
                                <span class="badge bg-success">
                                    <i class="bi bi-check-circle"></i> Sudah Dibayar
                                </span>
                            @else
                                <span class="badge bg-warning">
                                    <i class="bi bi-clock-history"></i> Belum Dibayar
                                </span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Data Pasien -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="bi bi-person"></i> Data Pasien</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="40%">Nama Pemilik</th>
                        <td>: {{ $pemeriksaan->pendaftaran->pemilikHewan->nama_pemilik ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>No. Telepon</th>
                        <td>: {{ $pemeriksaan->pendaftaran->pemilikHewan->no_tlp ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Nama Hewan</th>
                        <td>: <strong>{{ $pemeriksaan->pendaftaran->hewan->nama_hewan ?? '-' }}</strong></td>
                    </tr>
                    <tr>
                        <th>Jenis Hewan</th>
                        <td>: {{ $pemeriksaan->pendaftaran->hewan->jenis ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Keluhan</th>
                        <td>: {{ $pemeriksaan->pendaftaran->keluhan ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Hasil Pemeriksaan -->
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="bi bi-clipboard-check"></i> Hasil Pemeriksaan</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">Diagnosa:</label>
                    <p class="border rounded p-3 bg-light">{{ $pemeriksaan->diagnosa ?? '-' }}</p>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Tindakan:</label>
                    <p class="border rounded p-3 bg-light">{{ $pemeriksaan->tindakan ?? '-' }}</p>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Biaya Tindakan Medis:</label>
                    <p class="fs-5">
                        <span class="badge bg-primary">
                            Rp {{ number_format($pemeriksaan->biaya_tindakan ?? 0, 0, ',', '.') }}
                        </span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Obat yang Diberikan -->
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header bg-warning">
                <h5 class="mb-0"><i class="bi bi-capsule"></i> Obat yang Diberikan</h5>
            </div>
            <div class="card-body">
                @if($pemeriksaan->obats->count() > 0)
                    <div class="list-group">
                        @foreach($pemeriksaan->obats as $obat)
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between">
                                    <strong>{{ $obat->nama_obat }}</strong>
                                    <span class="badge bg-info">{{ $obat->pivot->jumlah }}x</span>
                                </div>
                                <small class="text-muted">@ Rp {{ number_format($obat->harga_obat, 0, ',', '.') }}</small>
                                <div class="text-end mt-1">
                                    <small class="text-primary fw-bold">
                                        Subtotal: Rp {{ number_format($obat->harga_obat * $obat->pivot->jumlah, 0, ',', '.') }}
                                    </small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    @php
                        $totalObat = 0;
                        foreach($pemeriksaan->obats as $obat) {
                            $totalObat += $obat->harga_obat * $obat->pivot->jumlah;
                        }
                    @endphp
                    <div class="mt-3 p-2 bg-light rounded">
                        <strong>Total Biaya Obat: </strong>
                        <span class="float-end text-primary fw-bold">
                            Rp {{ number_format($totalObat, 0, ',', '.') }}
                        </span>
                    </div>
                @else
                    <p class="text-muted text-center my-3">Tidak ada obat yang diberikan</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Total Biaya -->
    <div class="col-12 mb-4">
        <div class="card border-primary">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h5 class="mb-0"><i class="bi bi-calculator"></i> Total Biaya Pemeriksaan</h5>
                    </div>
                    <div class="col-md-6 text-end">
                        @php
                            $biayaTindakan = $pemeriksaan->biaya_tindakan ?? 0;
                            $biayaObat = 0;
                            foreach($pemeriksaan->obats as $obat) {
                                $biayaObat += $obat->harga_obat * $obat->pivot->jumlah;
                            }
                            $totalBiaya = $biayaTindakan + $biayaObat;
                        @endphp
                        <h3 class="text-primary mb-0">
                            Rp {{ number_format($totalBiaya, 0, ',', '.') }}
                        </h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Informasi Pembayaran (jika sudah dibayar) -->
    @if($pemeriksaan->pembayaran)
    <div class="col-12 mb-4">
        <div class="card border-success">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="bi bi-check-circle"></i> Informasi Pembayaran</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <strong>ID Pembayaran:</strong><br>
                        {{ $pemeriksaan->pembayaran->id_pembayaran }}
                    </div>
                    <div class="col-md-3">
                        <strong>Tanggal Bayar:</strong><br>
                        {{ $pemeriksaan->pembayaran->tanggal_bayar->format('d M Y H:i') }}
                    </div>
                    <div class="col-md-3">
                        <strong>Metode Bayar:</strong><br>
                        <span class="badge bg-info">{{ strtoupper($pemeriksaan->pembayaran->metode_bayar) }}</span>
                    </div>
                    <div class="col-md-3">
                        <strong>Total Dibayar:</strong><br>
                        <span class="text-success fw-bold fs-5">
                            Rp {{ number_format($pemeriksaan->pembayaran->total_bayar, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Action Buttons -->
    <div class="col-12">
        <div class="d-flex justify-content-between">
            <a href="{{ route('pemeriksaan.riwayat') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali ke Riwayat
            </a>
            
            <div>
                @if(!$pemeriksaan->pembayaran)
                    <a href="{{ route('pembayaran.create', ['pemeriksaan' => $pemeriksaan->id_pemeriksaan]) }}" 
                       class="btn btn-success">
                        <i class="bi bi-cash"></i> Proses Pembayaran
                    </a>
                @endif
                
                @if(auth()->user()->role == 'admin' || (auth()->user()->role == 'dokter' && $pemeriksaan->id_dokter == auth()->user()->dokterHewan->id_dokter))
                    <a href="{{ route('pemeriksaan.edit', $pemeriksaan->id_pemeriksaan) }}" 
                       class="btn btn-warning">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
