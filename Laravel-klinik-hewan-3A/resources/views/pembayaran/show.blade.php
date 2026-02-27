@extends('layouts.app')

@section('title', 'Detail Pembayaran')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-receipt-cutoff"></i> Detail Pembayaran</h2>
    <div>
        <a href="{{ route('pembayaran.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
        <button class="btn btn-primary" onclick="window.print()">
            <i class="bi bi-printer"></i> Cetak
        </button>
    </div>
</div>

<div class="row">
    <!-- Info Pembayaran -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="bi bi-credit-card-2-front"></i> Informasi Pembayaran</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="40%">ID Pembayaran</th>
                        <td><strong>{{ $pembayaran->id_pembayaran }}</strong></td>
                    </tr>
                    <tr>
                        <th>Tanggal Bayar</th>
                        <td>{{ $pembayaran->tanggal_bayar ? $pembayaran->tanggal_bayar->format('d/m/Y H:i') : '-' }}</td>
                    </tr>
                    <tr>
                        <th>Metode Pembayaran</th>
                        <td>
                            <span class="badge bg-{{ $pembayaran->metode_bayar == 'Cash' ? 'success' : ($pembayaran->metode_bayar == 'Transfer' ? 'primary' : 'info') }} fs-6">
                                {{ $pembayaran->metode_bayar }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Total Bayar</th>
                        <td class="fs-4 text-success fw-bold">Rp {{ number_format($pembayaran->total_bayar, 0, ',', '.') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Info Pemeriksaan -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="bi bi-clipboard2-pulse"></i> Informasi Pemeriksaan</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="40%">ID Pemeriksaan</th>
                        <td>{{ $pembayaran->pemeriksaan->id_pemeriksaan }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Periksa</th>
                        <td>{{ $pembayaran->pemeriksaan->tanggal_periksa ? $pembayaran->pemeriksaan->tanggal_periksa->format('d/m/Y') : '-' }}</td>
                    </tr>
                    <tr>
                        <th>Pemilik Hewan</th>
                        <td>{{ $pembayaran->pemeriksaan->pendaftaran->pemilikHewan->nama_pemilik ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Nama Hewan</th>
                        <td>{{ $pembayaran->pemeriksaan->pendaftaran->hewan->nama_hewan ?? '-' }} ({{ $pembayaran->pemeriksaan->pendaftaran->hewan->jenis_hewan ?? '-' }})</td>
                    </tr>
                    <tr>
                        <th>Dokter</th>
                        <td>{{ $pembayaran->pemeriksaan->dokterHewan->nama_dokter ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Diagnosa</th>
                        <td>{{ $pembayaran->pemeriksaan->diagnosa ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Tindakan</th>
                        <td>{{ $pembayaran->pemeriksaan->tindakan ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Biaya Tindakan</th>
                        <td>Rp {{ number_format($pembayaran->pemeriksaan->biaya_tindakan ?? 0, 0, ',', '.') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Rincian Biaya -->
<div class="card">
    <div class="card-header bg-warning">
        <h5 class="mb-0"><i class="bi bi-calculator"></i> Rincian Biaya</h5>
    </div>
    <div class="card-body">
        @php
            $biayaPeriksa = $pembayaran->pemeriksaan->dokterHewan->biaya_periksa ?? 0;
            $biayaTindakan = $pembayaran->pemeriksaan->biaya_tindakan ?? 0;
            $biayaObat = 0;
            foreach($pembayaran->detailPembayaranObat as $detail) {
                $biayaObat += $detail->subtotal;
            }
        @endphp
        <table class="table table-bordered">
            <!-- Biaya Pemeriksaan Dokter -->
            <tr class="table-light">
                <th colspan="4"><i class="bi bi-hospital"></i> Biaya Jasa Pemeriksaan Dokter</th>
            </tr>
            <tr>
                <td>{{ $pembayaran->pemeriksaan->dokterHewan->nama_dokter ?? '-' }}</td>
                <td class="text-center">1</td>
                <td class="text-end">Rp {{ number_format($biayaPeriksa, 0, ',', '.') }}</td>
                <td class="text-end"><strong>Rp {{ number_format($biayaPeriksa, 0, ',', '.') }}</strong></td>
            </tr>

            <!-- Biaya Tindakan Medis -->
            @if($biayaTindakan > 0)
            <tr class="table-light">
                <th colspan="4"><i class="bi bi-bandaid"></i> Biaya Tindakan Medis</th>
            </tr>
            <tr>
                <td colspan="3">{{ $pembayaran->pemeriksaan->tindakan ?? 'Tindakan Medis' }}</td>
                <td class="text-end"><strong>Rp {{ number_format($biayaTindakan, 0, ',', '.') }}</strong></td>
            </tr>
            @endif

            <!-- Biaya Obat -->
            @if($pembayaran->detailPembayaranObat->count() > 0)
            <tr class="table-light">
                <th colspan="4"><i class="bi bi-capsule"></i> Biaya Obat</th>
            </tr>
            @foreach($pembayaran->detailPembayaranObat as $detail)
                <tr>
                    <td>{{ $detail->obat->nama_obat ?? '-' }}</td>
                    <td class="text-center">{{ $detail->jumlah }}</td>
                    <td class="text-end">Rp {{ number_format($detail->subtotal / $detail->jumlah, 0, ',', '.') }}</td>
                    <td class="text-end">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
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
                <th class="text-end fs-5">Rp {{ number_format($pembayaran->total_bayar, 0, ',', '.') }}</th>
            </tr>
        </table>
    </div>
</div>

<style>
    @media print {
        /* Page setup untuk A4 */
        @page {
            size: A4;
            margin: 1cm;
        }

        /* Hide button dan elemen UI */
        .btn, .btn-group {
            display: none !important;
        }

        /* Adjust margins dan padding */
        .container-fluid {
            padding: 0 !important;
        }

        h2 {
            font-size: 18pt;
            margin-bottom: 10px !important;
        }

        .row {
            margin-left: -5px !important;
            margin-right: -5px !important;
        }

        .col-lg-6 {
            padding-left: 5px !important;
            padding-right: 5px !important;
        }

        .mb-4 {
            margin-bottom: 10px !important;
        }

        /* Card styling untuk print */
        .card {
            border: 1px solid #000 !important;
            margin-bottom: 10px !important;
            page-break-inside: avoid;
        }

        .card-header {
            padding: 8px 12px !important;
            background-color: #e9ecef !important;
            color: #000 !important;
            border-bottom: 1px solid #000 !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .card-header h5 {
            font-size: 12pt !important;
            margin: 0 !important;
        }

        .card-body {
            padding: 10px 12px !important;
        }

        /* Table styling */
        .table {
            font-size: 11pt;
            margin-bottom: 5px !important;
        }

        .table th,
        .table td {
            padding: 4px 8px !important;
        }

        .table-bordered {
            border: 1px solid #000 !important;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #000 !important;
        }

        /* Adjust font sizes */
        .fs-4 {
            font-size: 14pt !important;
        }

        .fs-5 {
            font-size: 12pt !important;
        }

        .fs-6 {
            font-size: 11pt !important;
        }

        /* Badge styling */
        .badge {
            border: 1px solid #000 !important;
            padding: 3px 6px !important;
        }

        /* Prevent page breaks */
        .card, .row {
            page-break-inside: avoid;
        }
    }
</style>
@endsection
