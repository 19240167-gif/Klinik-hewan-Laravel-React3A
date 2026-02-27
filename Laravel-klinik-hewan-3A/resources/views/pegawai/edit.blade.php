@extends('layouts.app')

@section('title', 'Edit Pegawai')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-warning">
                <h5 class="mb-0"><i class="bi bi-pencil"></i> Edit Data Pegawai</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('pegawai.update', $pegawai->id_pegawai) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="id_pegawai" class="form-label">ID Pegawai</label>
                        <input type="text" 
                               class="form-control" 
                               id="id_pegawai" 
                               value="{{ $pegawai->id_pegawai }}"
                               disabled>
                        <small class="text-muted">ID tidak dapat diubah</small>
                    </div>

                    <div class="mb-3">
                        <label for="nama_pegawai" class="form-label">Nama Pegawai <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('nama_pegawai') is-invalid @enderror" 
                               id="nama_pegawai" 
                               name="nama_pegawai" 
                               value="{{ old('nama_pegawai', $pegawai->nama_pegawai) }}"
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
                            <option value="laki-laki" {{ old('jenis_kelamin', $pegawai->jenis_kelamin) == 'laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="perempuan" {{ old('jenis_kelamin', $pegawai->jenis_kelamin) == 'perempuan' ? 'selected' : '' }}>Perempuan</option>
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
                               value="{{ old('no_telepon_pegawai', $pegawai->no_telepon_pegawai) }}"
                               maxlength="13"
                               placeholder="Contoh: 081234567890">
                        @error('no_telepon_pegawai')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('pegawai.index') }}" class="btn btn-secondary">
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
