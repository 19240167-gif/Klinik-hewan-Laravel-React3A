@extends('layouts.app')

@section('title', 'Tambah Pendaftaran')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-plus-circle"></i> Tambah Pendaftaran</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('pendaftaran.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="id_pemilik" class="form-label">Pemilik Hewan <span class="text-danger">*</span></label>
                        <select class="form-select select2-pemilik @error('id_pemilik') is-invalid @enderror" 
                                id="id_pemilik" 
                                name="id_pemilik" 
                                required>
                            <option value="">-- Pilih Pemilik Hewan --</option>
                            @foreach($pemilikHewans as $pemilik)
                                <option value="{{ $pemilik->id_pemilik }}" 
                                        {{ old('id_pemilik') == $pemilik->id_pemilik ? 'selected' : '' }}>
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
                            <i class="bi bi-info-circle"></i> Ketik untuk mencari pemilik
                        </small>
                    </div>

                    <div class="mb-3">
                        <label for="id_hewan" class="form-label">Hewan <span class="text-danger">*</span></label>
                        <select class="form-select select2-hewan @error('id_hewan') is-invalid @enderror" 
                                id="id_hewan" 
                                name="id_hewan" 
                                required
                                disabled>
                            <option value="">-- Pilih Pemilik Terlebih Dahulu --</option>
                        </select>
                        @error('id_hewan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">
                            <i class="bi bi-info-circle"></i> Hewan akan dimuat setelah memilih pemilik
                        </small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Pegawai</label>
                        <input type="text" 
                               class="form-control" 
                               value="{{ auth()->user()->name }}" 
                               disabled>
                        <small class="text-muted">
                            <i class="bi bi-info-circle"></i> Pegawai otomatis terisi berdasarkan user yang login
                        </small>
                    </div>

                    <div class="mb-3">
                        <label for="tanggal_daftar" class="form-label">Tanggal Daftar <span class="text-danger">*</span></label>
                        <input type="date" 
                               class="form-control @error('tanggal_daftar') is-invalid @enderror" 
                               id="tanggal_daftar" 
                               name="tanggal_daftar" 
                               value="{{ old('tanggal_daftar', date('Y-m-d')) }}"
                               required>
                        @error('tanggal_daftar')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <input type="text" 
                               class="form-control" 
                               value="Menunggu" 
                               disabled>
                        <small class="text-muted">
                            <i class="bi bi-info-circle"></i> Status otomatis 'Menunggu' dan akan berubah menjadi 'Selesai' setelah dokter melakukan pemeriksaan
                        </small>
                    </div>

                    <div class="mb-3">
                        <label for="keluhan" class="form-label">Keluhan</label>
                        <textarea class="form-control @error('keluhan') is-invalid @enderror" 
                                  id="keluhan" 
                                  name="keluhan" 
                                  rows="3">{{ old('keluhan') }}</textarea>
                        @error('keluhan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('pendaftaran.index') }}" class="btn btn-secondary">
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
    // Initialize Select2 untuk pemilik hewan
    $('.select2-pemilik').select2({
        theme: 'bootstrap-5',
        placeholder: '-- Pilih Pemilik Hewan --',
        allowClear: true,
        width: '100%'
    });

    // Initialize Select2 untuk hewan
    $('.select2-hewan').select2({
        theme: 'bootstrap-5',
        placeholder: '-- Pilih Hewan --',
        allowClear: true,
        width: '100%'
    });

    // Event ketika pemilik dipilih
    $('#id_pemilik').on('change', function() {
        var idPemilik = $(this).val();
        var hewanSelect = $('#id_hewan');
        
        // Reset dropdown hewan
        hewanSelect.html('<option value="">-- Loading... --</option>');
        hewanSelect.prop('disabled', true);
        
        if (idPemilik) {
            // Ambil data hewan via AJAX
            $.ajax({
                url: '{{ url("api/hewan-by-pemilik") }}/' + idPemilik,
                type: 'GET',
                dataType: 'json',
                success: function(hewans) {
                    hewanSelect.html('<option value="">-- Pilih Hewan --</option>');
                    
                    if (hewans.length > 0) {
                        $.each(hewans, function(index, hewan) {
                            hewanSelect.append(
                                $('<option></option>')
                                    .val(hewan.id_hewan)
                                    .text(hewan.id_hewan + ' - ' + hewan.nama_hewan + ' (' + hewan.jenis_hewan + ')')
                            );
                        });
                        hewanSelect.prop('disabled', false);
                    } else {
                        hewanSelect.html('<option value="">-- Pemilik belum memiliki hewan --</option>');
                        
                        // Tambahkan opsi untuk menambah hewan
                        setTimeout(function() {
                            if (confirm('Pemilik ini belum memiliki hewan. Apakah Anda ingin menambahkan hewan sekarang?')) {
                                window.open('{{ route("hewan.create") }}?pemilik=' + idPemilik, '_blank');
                            }
                        }, 500);
                    }
                },
                error: function() {
                    hewanSelect.html('<option value="">-- Error loading data --</option>');
                    alert('Gagal memuat data hewan. Silakan coba lagi.');
                }
            });
        } else {
            hewanSelect.html('<option value="">-- Pilih Pemilik Terlebih Dahulu --</option>');
        }
    });

    // Auto-trigger jika ada old value (dari validation error)
    @if(old('id_pemilik'))
        $('#id_pemilik').trigger('change');
        @if(old('id_hewan'))
            setTimeout(function() {
                $('#id_hewan').val('{{ old("id_hewan") }}').trigger('change');
            }, 500);
        @endif
    @endif
});
</script>
@endpush
