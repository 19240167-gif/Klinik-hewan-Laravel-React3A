@extends('layouts.app')

@section('title', 'Detail Hewan')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="bi bi-award"></i> Detail Hewan</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="150">ID Hewan</th>
                                <td>: {{ $hewan->id_hewan }}</td>
                            </tr>
                            <tr>
                                <th>Nama Hewan</th>
                                <td>: <strong>{{ $hewan->nama_hewan }}</strong></td>
                            </tr>
                            <tr>
                                <th>Jenis Hewan</th>
                                <td>: {{ $hewan->jenis_hewan }}</td>
                            </tr>
                            <tr>
                                <th>Jenis Kelamin</th>
                                <td>: 
                                    <span class="badge bg-{{ $hewan->jenis_kelamin == 'jantan' ? 'primary' : 'danger' }}">
                                        <i class="bi bi-{{ $hewan->jenis_kelamin == 'jantan' ? 'gender-male' : 'gender-female' }}"></i>
                                        {{ ucfirst($hewan->jenis_kelamin) }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="150">Umur</th>
                                <td>: {{ $hewan->umur ? $hewan->umur . ' tahun' : 'Tidak diketahui' }}</td>
                            </tr>
                            <tr>
                                <th>Pemilik</th>
                                <td>: 
                                    <a href="{{ route('pemilik-hewan.show', $hewan->id_pemilik) }}" class="text-decoration-none">
                                        {{ $hewan->pemilikHewan->nama_pemilik ?? '-' }}
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <th>No. Telepon</th>
                                <td>: {{ $hewan->pemilikHewan->no_tlp ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Terdaftar Sejak</th>
                                <td>: {{ $hewan->created_at->format('d M Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('hewan.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                    <a href="{{ route('hewan.edit', $hewan->id_hewan) }}" class="btn btn-warning">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    <a href="{{ route('pemilik-hewan.show', $hewan->id_pemilik) }}" class="btn btn-outline-primary">
                        <i class="bi bi-person"></i> Lihat Pemilik
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Hewan Lain dari Pemilik yang Sama -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-collection"></i> Hewan Lain dari Pemilik yang Sama</h6>
            </div>
            <div class="card-body">
                @php
                    $hewanLain = $hewan->pemilikHewan->hewan()->where('id_hewan', '!=', $hewan->id_hewan)->get();
                @endphp
                @if($hewanLain->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($hewanLain as $h)
                            <a href="{{ route('hewan.show', $h->id_hewan) }}" 
                               class="list-group-item list-group-item-action">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $h->nama_hewan }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $h->jenis_hewan }} • {{ ucfirst($h->jenis_kelamin) }}</small>
                                    </div>
                                    <i class="bi bi-arrow-right"></i>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted mb-0 small">Ini satu-satunya hewan milik {{ $hewan->pemilikHewan->nama_pemilik }}</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
