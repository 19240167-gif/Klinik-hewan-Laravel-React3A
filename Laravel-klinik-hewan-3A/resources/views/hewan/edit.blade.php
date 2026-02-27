@extends('layouts.app')

@section('title', 'Edit Hewan')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-warning">
                <h5 class="mb-0"><i class="bi bi-pencil"></i> Edit Data Hewan</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('hewan.update', $hewan->id_hewan) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="id_hewan" class="form-label">ID Hewan</label>
                                <input type="text" 
                                       class="form-control" 
                                       id="id_hewan" 
                                       value="{{ $hewan->id_hewan }}"
                                       disabled>
                                <small class="text-muted">ID tidak dapat diubah</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nama_hewan" class="form-label">Nama Hewan <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('nama_hewan') is-invalid @enderror" 
                                       id="nama_hewan" 
                                       name="nama_hewan" 
                                       value="{{ old('nama_hewan', $hewan->nama_hewan) }}"
                                       maxlength="10"
                                       required>
                                @error('nama_hewan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="jenis_hewan" class="form-label">Jenis Hewan <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('jenis_hewan') is-invalid @enderror" 
                                       id="jenis_hewan" 
                                       name="jenis_hewan" 
                                       value="{{ old('jenis_hewan', $hewan->jenis_hewan) }}"
                                       maxlength="10"
                                       required>
                                @error('jenis_hewan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="jenis_kelamin" class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                                <select class="form-select @error('jenis_kelamin') is-invalid @enderror" 
                                        id="jenis_kelamin" 
                                        name="jenis_kelamin" 
                                        required>
                                    <option value="">-- Pilih --</option>
                                    <option value="jantan" {{ old('jenis_kelamin', $hewan->jenis_kelamin) == 'jantan' ? 'selected' : '' }}>
                                        Jantan
                                    </option>
                                    <option value="betina" {{ old('jenis_kelamin', $hewan->jenis_kelamin) == 'betina' ? 'selected' : '' }}>
                                        Betina
                                    </option>
                                </select>
                                @error('jenis_kelamin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="umur" class="form-label">Umur (tahun)</label>
                        <input type="number" 
                               class="form-control @error('umur') is-invalid @enderror" 
                               id="umur" 
                               name="umur" 
                               value="{{ old('umur', $hewan->umur) }}"
                               min="0"
                               max="99">
                        @error('umur')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="id_pemilik" class="form-label">Pemilik Hewan <span class="text-danger">*</span></label>
                        <select class="form-select select2-pemilik @error('id_pemilik') is-invalid @enderror" 
                                id="id_pemilik" 
                                name="id_pemilik" 
                                required>
                            <option value="">-- Pilih Pemilik --</option>
                            @foreach($pemilikHewans as $pemilik)
                                <option value="{{ $pemilik->id_pemilik }}" 
                                        {{ old('id_pemilik', $hewan->id_pemilik) == $pemilik->id_pemilik ? 'selected' : '' }}>
                                    {{ $pemilik->id_pemilik }} - {{ $pemilik->nama_pemilik }}
                                    @if($pemilik->no_tlp)
                                        ({{ $pemilik->no_tlp }})
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        @error('id_pemilik')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">
                            <i class="bi bi-info-circle"></i> Ketik untuk mencari pemilik berdasarkan ID, nama, atau nomor telepon
                        </small>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('hewan.index') }}" class="btn btn-secondary">
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

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize Select2 untuk pemilihan pemilik hewan
    $('.select2-pemilik').select2({
        theme: 'bootstrap-5',
        placeholder: '-- Pilih Pemilik --',
        allowClear: true,
        width: '100%'
    });
});
</script>
@endpush
