@extends('layouts.app')

@section('title', 'Edit Pendaftaran')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-warning">
                <h5 class="mb-0"><i class="bi bi-pencil"></i> Edit Pendaftaran</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('pendaftaran.update', $pendaftaran->id_pendaftaran) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="id_pendaftaran" class="form-label">ID Pendaftaran</label>
                        <input type="text" 
                               class="form-control" 
                               id="id_pendaftaran" 
                               value="{{ $pendaftaran->id_pendaftaran }}"
                               disabled>
                        <small class="text-muted">ID tidak dapat diubah</small>
                    </div>

                    <div class="mb-3">
                        <label for="id_pemilik" class="form-label">Pemilik Hewan <span class="text-danger">*</span></label>
                        <select class="form-select select2-pemilik @error('id_pemilik') is-invalid @enderror" 
                                id="id_pemilik" 
                                name="id_pemilik" 
                                required>
                            <option value="">-- Pilih Pemilik Hewan --</option>
                            @foreach($pemilikHewans as $pemilik)
                                <option value="{{ $pemilik->id_pemilik }}" 
                                        {{ old('id_pemilik', $pendaftaran->id_pemilik) == $pemilik->id_pemilik ? 'selected' : '' }}>
                                    {{ $pemilik->id_pemilik }} - {{ $pemilik->nama_pemilik }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_pemilik')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">
                            <i class="bi bi-info-circle"></i> Ketik untuk mencari pemilik hewan
                        </small>
                    </div>

                    <div class="mb-3">
                        <label for="id_hewan" class="form-label">Hewan <span class="text-danger">*</span></label>
                        <select class="form-select select2-hewan @error('id_hewan') is-invalid @enderror" 
                                id="id_hewan" 
                                name="id_hewan" 
                                required>
                            <option value="">-- Pilih Hewan --</option>
                            @foreach($hewans as $hewan)
                                <option value="{{ $hewan->id_hewan }}" 
                                        {{ old('id_hewan', $pendaftaran->id_hewan) == $hewan->id_hewan ? 'selected' : '' }}>
                                    {{ $hewan->id_hewan }} - {{ $hewan->nama_hewan }} ({{ $hewan->jenis_hewan }})
                                </option>
                            @endforeach
                        </select>
                        @error('id_hewan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">
                            <i class="bi bi-info-circle"></i> Pilih pemilik hewan terlebih dahulu untuk memuat daftar hewan
                        </small>
                    </div>

                    <div class="mb-3">
                        <label for="id_pegawai" class="form-label">Pegawai <span class="text-danger">*</span></label>
                        <select class="form-select @error('id_pegawai') is-invalid @enderror" 
                                id="id_pegawai" 
                                name="id_pegawai" 
                                required>
                            <option value="">-- Pilih Pegawai --</option>
                            @foreach($pegawais as $pegawai)
                                <option value="{{ $pegawai->id_pegawai }}" 
                                        {{ old('id_pegawai', $pendaftaran->id_pegawai) == $pegawai->id_pegawai ? 'selected' : '' }}>
                                    {{ $pegawai->id_pegawai }} - {{ $pegawai->nama_pegawai }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_pegawai')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="tanggal_daftar" class="form-label">Tanggal Daftar <span class="text-danger">*</span></label>
                        <input type="date" 
                               class="form-control @error('tanggal_daftar') is-invalid @enderror" 
                               id="tanggal_daftar" 
                               name="tanggal_daftar" 
                               value="{{ old('tanggal_daftar', $pendaftaran->tanggal_daftar->format('Y-m-d')) }}"
                               required>
                        @error('tanggal_daftar')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-select @error('status') is-invalid @enderror" 
                                id="status" 
                                name="status" 
                                required>
                            <option value="">-- Pilih Status --</option>
                            <option value="menunggu" {{ old('status', $pendaftaran->status) == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                            <option value="selesai" {{ old('status', $pendaftaran->status) == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="keluhan" class="form-label">Keluhan</label>
                        <textarea class="form-control @error('keluhan') is-invalid @enderror" 
                                  id="keluhan" 
                                  name="keluhan" 
                                  rows="3">{{ old('keluhan', $pendaftaran->keluhan) }}</textarea>
                        @error('keluhan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('pendaftaran.index') }}" class="btn btn-secondary">
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

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialize Select2
        $('.select2-pemilik').select2({
            theme: 'bootstrap-5',
            placeholder: '-- Pilih Pemilik Hewan --',
            allowClear: true
        });

        $('.select2-hewan').select2({
            theme: 'bootstrap-5',
            placeholder: '-- Pilih Hewan --',
            allowClear: true
        });

        // AJAX untuk load hewan berdasarkan pemilik
        var pemilikSelect = $('#id_pemilik');
        var hewanSelect = $('#id_hewan');

        pemilikSelect.on('change', function() {
            var pemilikId = $(this).val();
            
            if (pemilikId) {
                $.ajax({
                    url: '/pendaftaran/hewan/' + pemilikId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        hewanSelect.empty().append('<option value=\"\">-- Pilih Hewan --</option>');
                        
                        if (data.length > 0) {
                            $.each(data, function(index, hewan) {
                                var option = new Option(
                                    hewan.id_hewan + ' - ' + hewan.nama_hewan + ' (' + hewan.jenis_hewan + ')',
                                    hewan.id_hewan,
                                    false,
                                    false
                                );
                                hewanSelect.append(option);
                            });
                        } else {
                            hewanSelect.append('<option value=\"\">Tidak ada hewan</option>');
                        }
                        
                        hewanSelect.trigger('change');
                    },
                    error: function() {
                        alert('Gagal memuat data hewan');
                    }
                });
            } else {
                hewanSelect.empty().append('<option value=\"\">-- Pilih Pemilik Hewan Terlebih Dahulu --</option>');
            }
        });

        @if(old('id_hewan'))
            setTimeout(function() {
                $('#id_hewan').val('{{ old("id_hewan") }}').trigger('change');
            }, 500);
        @endif
    });
</script>
@endpush
@endsection
