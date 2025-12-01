@extends('layouts.admin')

@section('title', 'Tambah Retur Pembelian')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Tambah Retur Pembelian</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('returpembelian.index') }}">Retur Pembelian</a></li>
                    <li class="breadcrumb-item active">Tambah</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Form Tambah Retur Pembelian</h3>
            </div>
            <form action="{{ route('returpembelian.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="transaksi_id">Transaksi Pembelian <span class="text-danger">*</span></label>
                                <select class="form-control select2 @error('transaksi_id') is-invalid @enderror" 
                                        id="transaksi_id" 
                                        name="transaksi_id" 
                                        required>
                                    <option value="">-- Pilih Transaksi --</option>
                                    @foreach($transaksis as $transaksi)
                                        <option value="{{ $transaksi->id_transaksi }}" 
                                                {{ old('transaksi_id') == $transaksi->id_transaksi ? 'selected' : '' }}>
                                            {{ $transaksi->nomor_transaksi }} - {{ $transaksi->supplier->nama_supplier ?? '-' }} 
                                            ({{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d/m/Y') }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('transaksi_id')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tanggal_retur">Tanggal Retur <span class="text-danger">*</span></label>
                                <input type="date" 
                                       class="form-control @error('tanggal_retur') is-invalid @enderror" 
                                       id="tanggal_retur" 
                                       name="tanggal_retur" 
                                       value="{{ old('tanggal_retur', date('Y-m-d')) }}" 
                                       required>
                                @error('tanggal_retur')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Info Supplier -->
                    <div id="supplier-info" class="alert alert-info" style="display: none;">
                        <h5><i class="icon fas fa-info-circle"></i> Informasi Supplier</h5>
                        <strong>Nama:</strong> <span id="supplier-nama">-</span><br>
                        <strong>Telepon:</strong> <span id="supplier-telepon">-</span>
                    </div>

                    <div class="form-group">
                        <label for="produk_id">Produk <span class="text-danger">*</span></label>
                        <select class="form-control select2 @error('produk_id') is-invalid @enderror" 
                                id="produk_id" 
                                name="produk_id" 
                                required 
                                disabled>
                            <option value="">-- Pilih Transaksi Terlebih Dahulu --</option>
                        </select>
                        @error('produk_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="jumlah_retur">Jumlah Retur <span class="text-danger">*</span></label>
                                <input type="number" 
                                       class="form-control @error('jumlah_retur') is-invalid @enderror" 
                                       id="jumlah_retur" 
                                       name="jumlah_retur" 
                                       value="{{ old('jumlah_retur') }}" 
                                       min="1"
                                       step="1"
                                       required>
                                <small class="form-text text-muted">
                                    Maksimal: <span id="max_jumlah" class="font-weight-bold text-primary">-</span>
                                </small>
                                @error('jumlah_retur')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nilai_retur">Nilai Retur <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp</span>
                                    </div>
                                    <input type="number" 
                                           class="form-control @error('nilai_retur') is-invalid @enderror" 
                                           id="nilai_retur" 
                                           name="nilai_retur" 
                                           value="{{ old('nilai_retur') }}" 
                                           min="0"
                                           step="0.01"
                                           required>
                                </div>
                                @error('nilai_retur')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="alasan">Alasan Retur <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('alasan') is-invalid @enderror" 
                                  id="alasan" 
                                  name="alasan" 
                                  rows="4" 
                                  placeholder="Contoh: Barang rusak, tidak sesuai pesanan, expired, dll"
                                  required>{{ old('alasan') }}</textarea>
                        @error('alasan')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                    <a href="{{ route('returpembelian.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize Select2
    $('.select2').select2({
        theme: 'bootstrap4'
    });

    // Ketika transaksi dipilih
    $('#transaksi_id').on('change', function() {
        var transaksiId = $(this).val();
        var produkSelect = $('#produk_id');
        
        if(transaksiId) {
            $.ajax({
                url: '/admin/returpembelian/get-detail/' + transaksiId,
                type: 'GET',
                dataType: 'json',
                beforeSend: function() {
                    produkSelect.prop('disabled', true);
                    produkSelect.html('<option value="">Loading...</option>');
                },
                success: function(response) {
                    // Show supplier info
                    $('#supplier-nama').text(response.supplier.nama);
                    $('#supplier-telepon').text(response.supplier.telepon);
                    $('#supplier-info').slideDown();

                    // Populate products
                    produkSelect.prop('disabled', false);
                    produkSelect.empty();
                    produkSelect.append('<option value="">-- Pilih Produk --</option>');
                    
                    $.each(response.data, function(key, item) {
                        produkSelect.append(
                            '<option value="'+ item.id_produk +'" ' +
                            'data-jumlah="'+ item.jumlah +'" ' +
                            'data-harga="'+ item.harga +'">' + 
                            item.nama_produk + ' - ' + item.kode_produk + ' (Qty: ' + item.jumlah + ')' +
                            '</option>'
                        );
                    });
                },
                error: function() {
                    alert('Gagal mengambil data produk');
                    produkSelect.prop('disabled', true);
                    produkSelect.html('<option value="">-- Error --</option>');
                }
            });
        } else {
            $('#supplier-info').slideUp();
            produkSelect.prop('disabled', true);
            produkSelect.empty();
            produkSelect.append('<option value="">-- Pilih Transaksi Terlebih Dahulu --</option>');
        }
    });

    // Ketika produk dipilih
    $('#produk_id').on('change', function() {
        var selectedOption = $(this).find('option:selected');
        var jumlah = selectedOption.data('jumlah');
        var harga = selectedOption.data('harga');
        
        if(jumlah) {
            $('#max_jumlah').text(jumlah + ' unit');
            $('#jumlah_retur').attr('max', jumlah);
        } else {
            $('#max_jumlah').text('-');
            $('#jumlah_retur').removeAttr('max');
        }

        // Auto set nilai retur if jumlah already entered
        var jumlahRetur = $('#jumlah_retur').val();
        if(jumlahRetur && harga) {
            var nilaiRetur = parseFloat(jumlahRetur) * parseFloat(harga);
            $('#nilai_retur').val(nilaiRetur.toFixed(2));
        }
    });

    // Auto calculate nilai retur
    $('#jumlah_retur').on('input', function() {
        var jumlah = parseFloat($(this).val()) || 0;
        var selectedOption = $('#produk_id').find('option:selected');
        var harga = parseFloat(selectedOption.data('harga')) || 0;
        
        if(jumlah && harga) {
            var nilaiRetur = jumlah * harga;
            $('#nilai_retur').val(nilaiRetur.toFixed(2));
        }
    });
});
</script>
@endpush