@extends('layouts.app')

@section('title', 'Tambah Obat')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-plus-circle"></i> Tambah Obat</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('obat.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="nama_obat" class="form-label">Nama Obat <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('nama_obat') is-invalid @enderror" 
                               id="nama_obat" 
                               name="nama_obat" 
                               value="{{ old('nama_obat') }}"
                               maxlength="20"
                               required>
                        @error('nama_obat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="jenis_obat" class="form-label">Jenis Obat</label>
                        <input type="text" 
                               class="form-control @error('jenis_obat') is-invalid @enderror" 
                               id="jenis_obat" 
                               name="jenis_obat" 
                               value="{{ old('jenis_obat') }}"
                               maxlength="15">
                        @error('jenis_obat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="harga_obat" class="form-label">Harga Obat <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" 
                                   class="form-control @error('harga_obat') is-invalid @enderror" 
                                   id="harga_obat" 
                                   name="harga_obat" 
                                   value="{{ old('harga_obat') }}"
                                   min="0"
                                   required>
                            @error('harga_obat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="stok" class="form-label">Stok <span class="text-danger">*</span></label>
                        <input type="number" 
                               class="form-control @error('stok') is-invalid @enderror" 
                               id="stok" 
                               name="stok" 
                               value="{{ old('stok', 0) }}"
                               min="0"
                               required>
                        @error('stok')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Jumlah stok obat yang tersedia</small>
                    </div>

                    <div class="mb-3">
                        <label for="tanggal_kadaluarsa" class="form-label">Tanggal Kadaluarsa</label>
                        <input type="date" 
                               class="form-control @error('tanggal_kadaluarsa') is-invalid @enderror" 
                               id="tanggal_kadaluarsa" 
                               name="tanggal_kadaluarsa" 
                               value="{{ old('tanggal_kadaluarsa') }}">
                        @error('tanggal_kadaluarsa')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('obat.index') }}" class="btn btn-secondary">
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
