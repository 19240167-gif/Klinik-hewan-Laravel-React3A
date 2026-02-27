@extends('layouts.app')

@section('title', 'Detail Obat')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="bi bi-capsule"></i> Detail Obat</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th width="30%">ID Obat</th>
                        <td>{{ $obat->id_obat }}</td>
                    </tr>
                    <tr>
                        <th>Nama Obat</th>
                        <td>{{ $obat->nama_obat }}</td>
                    </tr>
                    <tr>
                        <th>Jenis Obat</th>
                        <td>{{ $obat->jenis_obat ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Harga Obat</th>
                        <td>Rp {{ number_format($obat->harga_obat, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th>Stok</th>
                        <td>
                            @if($obat->stok <= 0)
                                <span class="badge bg-danger fs-6">Habis</span>
                            @elseif($obat->stok <= 10)
                                <span class="badge bg-warning text-dark fs-6">{{ $obat->stok }} (Stok Menipis)</span>
                            @else
                                <span class="badge bg-success fs-6">{{ $obat->stok }}</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Tanggal Kadaluarsa</th>
                        <td>{{ $obat->tanggal_kadaluarsa ? $obat->tanggal_kadaluarsa->format('d/m/Y') : '-' }}</td>
                    </tr>
                    <tr>
                        <th>Dibuat</th>
                        <td>{{ $obat->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Terakhir Diupdate</th>
                        <td>{{ $obat->updated_at->format('d/m/Y H:i') }}</td>
                    </tr>
                </table>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('obat.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                    <div>
                        <a href="{{ route('obat.edit', $obat->id_obat) }}" class="btn btn-warning">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
