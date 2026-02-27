@extends('layouts.app')

@section('title', 'Tambah Pegawai')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-plus-circle"></i> Tambah Pegawai Baru</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('pegawai.store') }}" method="POST">
                    @csrf
                    
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> 
                        Pegawai baru akan dibuatkan akun login dengan role <strong>Pegawai</strong>
                    </div>

                    <h6 class="text-muted mb-3"><i class="bi bi-person"></i> Data Pegawai</h6>
                    
                    <div class="mb-3">
                        <label for="nama_pegawai" class="form-label">Nama Pegawai <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('nama_pegawai') is-invalid @enderror" 
                               id="nama_pegawai" 
                               name="nama_pegawai" 
                               value="{{ old('nama_pegawai') }}"
                               maxlength="25"
                               required>
                        @error('nama_pegawai')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="jenis_kelamin" class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                        <select class="form-select @error('jenis_kelamin') is-invalid @enderror" 
                                id="jenis_kelamin" 
                                name="jenis_kelamin" 
                                required>
                            <option value="">-- Pilih Jenis Kelamin --</option>
                            <option value="laki-laki" {{ old('jenis_kelamin') == 'laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="perempuan" {{ old('jenis_kelamin') == 'perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('jenis_kelamin')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="no_telepon_pegawai" class="form-label">No. Telepon</label>
                        <input type="text" 
                               class="form-control @error('no_telepon_pegawai') is-invalid @enderror" 
                               id="no_telepon_pegawai" 
                               name="no_telepon_pegawai" 
                               value="{{ old('no_telepon_pegawai') }}"
                               maxlength="13"
                               placeholder="Contoh: 081234567890">
                        @error('no_telepon_pegawai')
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
                               placeholder="contoh@email.com"
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
                        <a href="{{ route('pegawai.index') }}" class="btn btn-secondary">
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
