@extends('layouts.app')

@section('title', 'Proses Pembayaran')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-cash-coin"></i> Proses Pembayaran</h2>
    <a href="{{ route('pembayaran.pending') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">
    <!-- Info Pemeriksaan -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="bi bi-clipboard2-pulse"></i> Detail Pemeriksaan</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="40%">ID Pemeriksaan</th>
                        <td>{{ $pemeriksaan->id_pemeriksaan }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Periksa</th>
                        <td>{{ $pemeriksaan->tanggal_periksa ? $pemeriksaan->tanggal_periksa->format('d/m/Y') : '-' }}</td>
                    </tr>
                    <tr>
                        <th>Pemilik Hewan</th>
                        <td>{{ $pemeriksaan->pendaftaran->pemilikHewan->nama_pemilik ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Nama Hewan</th>
                        <td>{{ $pemeriksaan->pendaftaran->hewan->nama_hewan ?? '-' }} ({{ $pemeriksaan->pendaftaran->hewan->jenis_hewan ?? '-' }})</td>
                    </tr>
                    <tr>
                        <th>Dokter</th>
                        <td>{{ $pemeriksaan->dokterHewan->nama_dokter ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Diagnosa</th>
                        <td>{{ $pemeriksaan->diagnosa ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Tindakan</th>
                        <td>{{ $pemeriksaan->tindakan ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Rincian Biaya -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header bg-warning">
                <h5 class="mb-0"><i class="bi bi-calculator"></i> Rincian Biaya</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <!-- Biaya Tindakan Medis -->
                    <tr class="table-light">
                        <th colspan="3"><i class="bi bi-hospital"></i> Biaya Tindakan Medis - {{ $pemeriksaan->dokterHewan->nama_dokter ?? '-' }}</th>
                        <th class="text-end">Rp {{ number_format($biayaTindakan, 0, ',', '.') }}</th>
                    </tr>

                    <!-- Biaya Obat -->
                    @if($pemeriksaan->obats->count() > 0)
                    <tr class="table-light">
                        <th colspan="4"><i class="bi bi-capsule"></i> Biaya Obat</th>
                    </tr>
                    @foreach($pemeriksaan->obats as $obat)
                        <tr>
                            <td>{{ $obat->nama_obat }}</td>
                            <td class="text-center">{{ $obat->pivot->jumlah }}</td>
                            <td class="text-end">Rp {{ number_format($obat->harga_obat, 0, ',', '.') }}</td>
                            <td class="text-end">Rp {{ number_format($obat->harga_obat * $obat->pivot->jumlah, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                    <tr class="table-secondary">
                        <th colspan="3" class="text-end">Subtotal Obat:</th>
                        <th class="text-end">Rp {{ number_format($biayaObat, 0, ',', '.') }}</th>
                    </tr>
                    @endif

                    <!-- Total Keseluruhan -->
                    <tr class="table-success">
                        <th colspan="3" class="text-end fs-5">TOTAL PEMBAYARAN:</th>
                        <th class="text-end fs-5">Rp {{ number_format($totalBayar, 0, ',', '.') }}</th>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Form Pembayaran -->
<div class="card">
    <div class="card-header bg-success text-white">
        <h5 class="mb-0"><i class="bi bi-credit-card"></i> Form Pembayaran</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('pembayaran.store') }}" method="POST">
            @csrf
            <input type="hidden" name="id_pemeriksaan" value="{{ $pemeriksaan->id_pemeriksaan }}">

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="metode_bayar" class="form-label">Metode Pembayaran <span class="text-danger">*</span></label>
                    <select class="form-select @error('metode_bayar') is-invalid @enderror" 
                            id="metode_bayar" name="metode_bayar" required>
                        <option value="">-- Pilih Metode --</option>
                        <option value="Cash" {{ old('metode_bayar') == 'Cash' ? 'selected' : '' }}>Cash</option>
                        <option value="Debit" {{ old('metode_bayar') == 'Debit' ? 'selected' : '' }}>Debit</option>
                        <option value="Kredit" {{ old('metode_bayar') == 'Kredit' ? 'selected' : '' }}>Kredit</option>
                        <option value="Transfer" {{ old('metode_bayar') == 'Transfer' ? 'selected' : '' }}>Transfer</option>
                        <option value="QRIS" {{ old('metode_bayar') == 'QRIS' ? 'selected' : '' }}>QRIS</option>
                    </select>
                    @error('metode_bayar')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Total yang Harus Dibayar</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="text" class="form-control bg-warning fw-bold fs-5" 
                               value="{{ number_format($totalBayar, 0, ',', '.') }}" readonly>
                    </div>
                    <small class="text-muted">Jasa Periksa + Tindakan + Obat</small>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('pembayaran.pending') }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Batal
                </a>
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-check-circle"></i> Proses Pembayaran
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
