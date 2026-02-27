@extends('layouts.app')

@section('title', 'Tambah Hewan')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-plus-circle"></i> Tambah Data Hewan</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('hewan.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nama_hewan" class="form-label">Nama Hewan <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('nama_hewan') is-invalid @enderror" 
                                       id="nama_hewan" 
                                       name="nama_hewan" 
                                       value="{{ old('nama_hewan') }}"
                                       maxlength="10"
                                       required>
                                @error('nama_hewan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="jenis_hewan" class="form-label">Jenis Hewan <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('jenis_hewan') is-invalid @enderror" 
                                       id="jenis_hewan" 
                                       name="jenis_hewan" 
                                       value="{{ old('jenis_hewan') }}"
                                       maxlength="10"
                                       placeholder="Kucing, Anjing, dll"
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
                                    <option value="jantan" {{ old('jenis_kelamin') == 'jantan' ? 'selected' : '' }}>
                                        <i class="bi bi-gender-male"></i> Jantan
                                    </option>
                                    <option value="betina" {{ old('jenis_kelamin') == 'betina' ? 'selected' : '' }}>
                                        <i class="bi bi-gender-female"></i> Betina
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
                               value="{{ old('umur') }}"
                               min="0"
                               max="99"
                               placeholder="Contoh: 3">
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
                                        {{ old('id_pemilik', $selectedPemilik ?? '') == $pemilik->id_pemilik ? 'selected' : '' }}>
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
                        <div class="mt-2">
                            <a href="{{ route('pemilik-hewan.create') }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                <i class="bi bi-plus-circle"></i> Tambah Pemilik Baru
                            </a>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('hewan.index') }}" class="btn btn-secondary">
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

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize Select2 untuk pemilihan pemilik hewan
    $('.select2-pemilik').select2({
        theme: 'bootstrap-5',
        placeholder: '-- Pilih Pemilik --',
        allowClear: true,
        width: '100%',
        language: {
            noResults: function() {
                return "Pemilik tidak ditemukan. <a href='{{ route('pemilik-hewan.create') }}' target='_blank' class='btn btn-sm btn-primary mt-2'><i class='bi bi-plus'></i> Tambah Pemilik Baru</a>";
            },
            searching: function() {
                return "Mencari...";
            },
            inputTooShort: function() {
                return "Ketik minimal 1 karakter untuk mencari";
            }
        },
        escapeMarkup: function(markup) {
            return markup;
        }
    });

    // Auto-refresh dropdown ketika window kembali focus (setelah tambah pemilik baru)
    $(window).focus(function() {
        var currentValue = $('.select2-pemilik').val();
        $.ajax({
            url: '{{ route("hewan.create") }}',
            type: 'GET',
            success: function(response) {
                // Reload page untuk update dropdown
                // Atau bisa pakai AJAX untuk update dropdown saja
                console.log('Window focused - data might have changed');
            }
        });
    });
});
</script>
@endpush
