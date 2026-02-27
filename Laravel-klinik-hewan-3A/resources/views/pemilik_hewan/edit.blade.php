@extends('layouts.app')

@section('title', 'Edit Pemilik Hewan')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-warning">
                <h5 class="mb-0"><i class="bi bi-pencil"></i> Edit Pemilik Hewan</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('pemilik-hewan.update', $pemilikHewan->id_pemilik) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="id_pemilik" class="form-label">ID Pemilik Hewan</label>
                        <input type="text" 
                               class="form-control" 
                               id="id_pemilik" 
                               value="{{ $pemilikHewan->id_pemilik }}"
                               disabled>
                        <small class="text-muted">ID tidak dapat diubah</small>
                    </div>

                    <div class="mb-3">
                        <label for="nama_pemilik" class="form-label">Nama Pemilik <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('nama_pemilik') is-invalid @enderror" 
                               id="nama_pemilik" 
                               name="nama_pemilik" 
                               value="{{ old('nama_pemilik', $pemilikHewan->nama_pemilik) }}"
                               maxlength="25"
                               required>
                        @error('nama_pemilik')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="no_tlp" class="form-label">No. Telepon</label>
                        <input type="text" 
                               class="form-control @error('no_tlp') is-invalid @enderror" 
                               id="no_tlp" 
                               name="no_tlp" 
                               value="{{ old('no_tlp', $pemilikHewan->no_tlp) }}"
                               maxlength="15">
                        @error('no_tlp')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat</label>
                        <textarea class="form-control @error('alamat') is-invalid @enderror" 
                                  id="alamat" 
                                  name="alamat" 
                                  rows="3">{{ old('alamat', $pemilikHewan->alamat) }}</textarea>
                        @error('alamat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('pemilik-hewan.index') }}" class="btn btn-secondary">
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
