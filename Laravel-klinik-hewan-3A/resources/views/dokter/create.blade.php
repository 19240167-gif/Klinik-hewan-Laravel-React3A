@extends('layouts.app')

@section('title', 'Tambah Dokter Hewan')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-plus-circle"></i> Tambah Dokter Hewan Baru</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('dokter-hewan.store') }}" method="POST">
                    @csrf
                    
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> 
                        Dokter baru akan dibuatkan akun login dengan role <strong>Dokter</strong>
                    </div>

                    <h6 class="text-muted mb-3"><i class="bi bi-hospital"></i> Data Dokter Hewan</h6>
                    
                    <div class="mb-3">
                        <label for="nama_dokter" class="form-label">Nama Dokter <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('nama_dokter') is-invalid @enderror" 
                               id="nama_dokter" 
                               name="nama_dokter" 
                               value="{{ old('nama_dokter') }}"
                               maxlength="25"
                               placeholder="drh. Nama Lengkap"
                               required>
                        @error('nama_dokter')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="no_sip" class="form-label">No. SIP (Surat Izin Praktik)</label>
                        <input type="text" 
                               class="form-control @error('no_sip') is-invalid @enderror" 
                               id="no_sip" 
                               name="no_sip" 
                               value="{{ old('no_sip') }}"
                               maxlength="20"
                               placeholder="Contoh: SIP-DRH-2024-001">
                        @error('no_sip')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr>
                    <h6 class="text-muted mb-3"><i class="bi bi-key"></i> Data Akun Login</h6>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}"
                               placeholder="dokter@email.com"
                               required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   id="password" 
                                   name="password" 
                                   minlength="6"
                                   required>
                            <small class="text-muted">Minimal 6 karakter</small>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                            <input type="password" 
                                   class="form-control" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   minlength="6"
                                   required>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('dokter-hewan.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
