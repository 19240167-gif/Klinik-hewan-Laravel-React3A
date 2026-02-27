@extends('layouts.app')

@section('title', 'Form Pemeriksaan')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-clipboard-plus"></i> Form Pemeriksaan</h5>
            </div>
            <div class="card-body">
                <!-- Info Pendaftaran -->
                <div class="alert alert-info mb-4">
                    <h6 class="alert-heading"><i class="bi bi-info-circle"></i> Informasi Pendaftaran</h6>
                    <hr>
                    <table class="table table-sm mb-0">
                        <tr>
                            <th width="30%">ID Pendaftaran</th>
                            <td>{{ $pendaftaran->id_pendaftaran }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Daftar</th>
                            <td>{{ $pendaftaran->tanggal_daftar->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <th>Pemilik Hewan</th>
                            <td>{{ $pendaftaran->pemilikHewan->nama_pemilik ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Keluhan</th>
                            <td>{{ $pendaftaran->keluhan ?? '-' }}</td>
                        </tr>
                    </table>
                </div>

                <form action="{{ route('pemeriksaan.store') }}" method="POST">
                    @csrf
                    
                    <!-- Hidden field untuk id_pendaftaran -->
                    <input type="hidden" name="id_pendaftaran" value="{{ $pendaftaran->id_pendaftaran }}">
                    
                    <div class="mb-3">
                        <label for="id_dokter" class="form-label">Dokter Hewan <span class="text-danger">*</span></label>
                        @if($dokterLogin)
                            <input type="text" 
                                   class="form-control" 
                                   value="{{ $dokterLogin->nama_dokter }} (SIP: {{ $dokterLogin->no_sip }})" 
                                   disabled>
                            <input type="hidden" name="id_dokter" value="{{ $dokterLogin->id_dokter }}">
                            <small class="text-muted">
                                <i class="bi bi-info-circle"></i> Dokter otomatis terisi berdasarkan akun yang login
                            </small>
                        @else
                            <select class="form-select select2-dokter @error('id_dokter') is-invalid @enderror" 
                                    id="id_dokter" 
                                    name="id_dokter" 
                                    required>
                                <option value="">-- Pilih Dokter Hewan --</option>
                                @foreach($dokterHewans as $dokter)
                                    <option value="{{ $dokter->id_dokter }}" 
                                            {{ old('id_dokter') == $dokter->id_dokter ? 'selected' : '' }}>
                                        {{ $dokter->nama_dokter }} (SIP: {{ $dokter->no_sip }})
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">
                                <i class="bi bi-info-circle"></i> Ketik untuk mencari dokter berdasarkan nama atau SIP
                            </small>
                        @endif
                        @error('id_dokter')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="tanggal_periksa" class="form-label">Tanggal Periksa <span class="text-danger">*</span></label>
                        <input type="date" 
                               class="form-control @error('tanggal_periksa') is-invalid @enderror" 
                               id="tanggal_periksa" 
                               name="tanggal_periksa" 
                               value="{{ old('tanggal_periksa', date('Y-m-d')) }}"
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
                                  rows="3">{{ old('diagnosa') }}</textarea>
                        @error('diagnosa')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="tindakan" class="form-label">Tindakan</label>
                        <textarea class="form-control @error('tindakan') is-invalid @enderror" 
                                  id="tindakan" 
                                  name="tindakan" 
                                  rows="3"
                                  placeholder="Masukkan tindakan medis yang dilakukan (jika ada)">{{ old('tindakan') }}</textarea>
                        @error('tindakan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="biaya_tindakan" class="form-label">Biaya Tindakan Medis <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" 
                                   class="form-control @error('biaya_tindakan') is-invalid @enderror" 
                                   id="biaya_tindakan" 
                                   name="biaya_tindakan" 
                                   value="{{ old('biaya_tindakan', 0) }}"
                                   min="0"
                                   step="100"
                                   required>
                            @error('biaya_tindakan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <small class="text-muted">Biaya pemeriksaan dan tindakan medis dokter hewan</small>
                    </div>

                    <!-- Obat Section -->
                    <div class="card mb-3">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <h6 class="mb-0"><i class="bi bi-capsule"></i> Obat yang Diberikan</h6>
                            <button type="button" class="btn btn-sm btn-success" id="addObat">
                                <i class="bi bi-plus"></i> Tambah Obat
                            </button>
                        </div>
                        <div class="card-body">
                            <div id="obatContainer">
                                <!-- Obat rows will be added here -->
                            </div>
                            <p class="text-muted small mb-0" id="noObatText">
                                <i class="bi bi-info-circle"></i> Klik "Tambah Obat" untuk menambahkan obat
                            </p>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('pemeriksaan.index') }}" class="btn btn-secondary">
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

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    // Initialize Select2 untuk dokter hewan
    $('.select2-dokter').select2({
        theme: 'bootstrap-5',
        placeholder: '-- Pilih Dokter Hewan --',
        allowClear: true,
        width: '100%'
    });

    // Data obat dari server
    var obatData = @json($obats);
    var obatIndex = 0;

    // Tambah obat row
    $('#addObat').click(function() {
        $('#noObatText').hide();
        
        var options = '<option value="">-- Pilih Obat --</option>';
        obatData.forEach(function(obat) {
            var stokBadge = obat.stok <= 10 ? 
                '<span class="badge bg-warning text-dark">Stok: ' + obat.stok + '</span>' : 
                '<span class="badge bg-success">Stok: ' + obat.stok + '</span>';
            
            options += '<option value="' + obat.id_obat + '" data-harga="' + obat.harga_obat + '" data-stok="' + obat.stok + '">' + 
                       obat.nama_obat + ' (' + obat.jenis_obat + ') - ' + stokBadge + '</option>';
        });

        var row = `
            <div class="row mb-2 obat-row" data-index="${obatIndex}">
                <div class="col-md-5">
                    <select class="form-select obat-select" name="obat[${obatIndex}][id_obat]" required>
                        ${options}
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="number" class="form-control obat-jumlah" name="obat[${obatIndex}][jumlah]" 
                           placeholder="Jumlah" min="1" value="1" max="999" required>
                </div>
                <div class="col-md-3">
                    <small class="text-muted stok-info">Pilih obat dulu</small>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-danger btn-sm btn-remove-obat">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
        `;
        
        $('#obatContainer').append(row);
        obatIndex++;
    });

    // Hapus obat row
    $(document).on('click', '.btn-remove-obat', function() {
        $(this).closest('.obat-row').remove();
        if ($('.obat-row').length === 0) {
            $('#noObatText').show();
        }
    });

    // Update info stok saat obat dipilih
    $(document).on('change', '.obat-select', function() {
        var selectedOption = $(this).find('option:selected');
        var stok = selectedOption.data('stok');
        var row = $(this).closest('.obat-row');
        var stokInfo = row.find('.stok-info');
        var jumlahInput = row.find('.obat-jumlah');
        
        if (stok !== undefined) {
            jumlahInput.attr('max', stok);
            if (stok <= 0) {
                stokInfo.html('<span class="text-danger">Stok Habis!</span>');
                jumlahInput.val(0).attr('disabled', true);
            } else if (stok <= 10) {
                stokInfo.html('<span class="text-warning">Stok: ' + stok + '</span>');
                jumlahInput.attr('disabled', false);
            } else {
                stokInfo.html('<span class="text-success">Stok: ' + stok + '</span>');
                jumlahInput.attr('disabled', false);
            }
        } else {
            stokInfo.text('Pilih obat dulu');
            jumlahInput.attr('disabled', false);
        }
    });

    // Validasi jumlah tidak melebihi stok
    $(document).on('input', '.obat-jumlah', function() {
        var maxStok = parseInt($(this).attr('max'));
        var currentVal = parseInt($(this).val());
        
        if (currentVal > maxStok) {
            $(this).val(maxStok);
            alert('Jumlah tidak boleh melebihi stok yang tersedia (' + maxStok + ')');
        }
    });
});
</script>
@endpush
@endsection
