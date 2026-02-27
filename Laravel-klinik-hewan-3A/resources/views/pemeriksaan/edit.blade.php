@extends('layouts.app')

@section('title', 'Edit Pemeriksaan')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-warning">
                <h5 class="mb-0"><i class="bi bi-pencil"></i> Edit Pemeriksaan</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('pemeriksaan.update', $pemeriksaan->id_pemeriksaan) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="id_pemeriksaan" class="form-label">ID Pemeriksaan</label>
                        <input type="text" 
                               class="form-control" 
                               value="{{ $pemeriksaan->id_pemeriksaan }}"
                               disabled>
                        <small class="text-muted">ID tidak dapat diubah</small>
                    </div>

                    <div class="mb-3">
                        <label for="id_pendaftaran" class="form-label">Pendaftaran</label>
                        <input type="text" 
                               class="form-control" 
                               value="{{ $pemeriksaan->pendaftaran->id_pendaftaran }} - {{ $pemeriksaan->pendaftaran->pemilikHewan->nama_pemilik ?? '-' }}"
                               disabled>
                        <small class="text-muted">Pendaftaran tidak dapat diubah</small>
                    </div>

                    <div class="mb-3">
                        <label for="id_dokter" class="form-label">Dokter Hewan</label>
                        <input type="text" 
                               class="form-control" 
                               value="{{ $pemeriksaan->dokterHewan->nama_dokter }} (SIP: {{ $pemeriksaan->dokterHewan->no_sip }})"
                               disabled>
                        <small class="text-muted">Dokter tidak dapat diubah</small>
                    </div>

                    <div class="mb-3">
                        <label for="tanggal_periksa" class="form-label">Tanggal Periksa <span class="text-danger">*</span></label>
                        <input type="date" 
                               class="form-control @error('tanggal_periksa') is-invalid @enderror" 
                               id="tanggal_periksa" 
                               name="tanggal_periksa" 
                               value="{{ old('tanggal_periksa', $pemeriksaan->tanggal_periksa->format('Y-m-d')) }}"
                               required>
                        @error('tanggal_periksa')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="diagnosa" class="form-label">Diagnosa</label>
                        <textarea class="form-control @error('diagnosa') is-invalid @enderror" 
                                  id="diagnosa" 
                                  name="diagnosa" 
                                  rows="3">{{ old('diagnosa', $pemeriksaan->diagnosa) }}</textarea>
                        @error('diagnosa')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="tindakan" class="form-label">Tindakan</label>
                        <textarea class="form-control @error('tindakan') is-invalid @enderror" 
                                  id="tindakan" 
                                  name="tindakan" 
                                  rows="3">{{ old('tindakan', $pemeriksaan->tindakan) }}</textarea>
                        @error('tindakan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('pemeriksaan.riwayat') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-save"></i> Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
