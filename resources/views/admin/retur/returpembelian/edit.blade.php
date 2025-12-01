@extends('layouts.admin')

@section('title', 'Edit Retur Pembelian')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Edit Retur Pembelian</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('returpembelian.index') }}">Retur Pembelian</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Form Edit Retur Pembelian</h3>
            </div>
            <form action="{{ route('returpembelian.update', $retur->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <!-- Info yang tidak bisa diubah -->
                    <div class="alert alert-info">
                        <h5><i class="icon fas fa-info-circle"></i> Informasi Transaksi</h5>
                        <strong>No. Transaksi:</strong> {{ $retur->transaksi->nomor_transaksi ?? '-' }}<br>
                        <strong>Supplier:</strong> {{ $retur->transaksi->supplier->nama_supplier ?? '-' }}<br>
                        <strong>Tanggal Transaksi:</strong> {{ $retur->transaksi ? \Carbon\Carbon::parse($retur->transaksi->tanggal_transaksi)->format('d F Y') : '-' }}
                    </div>

                    <div class="form-group">
                        <label>Produk</label>
                        <input type="text" 
                               class="form-control" 
                               value="{{ $retur->produk->nama_produk ?? '-' }} ({{ $retur->produk->kode_produk ?? '-' }})" 
                               readonly>
                        <small class="form-text text-muted">Transaksi dan produk tidak dapat diubah</small>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tanggal_retur">Tanggal Retur <span class="text-danger">*</span></label>
                                <input type="date" 
                                       class="form-control @error('tanggal_retur') is-invalid @enderror" 
                                       id="tanggal_retur" 
                                       name="tanggal_retur" 
                                       value="{{ old('tanggal_retur', $retur->tanggal_retur->format('Y-m-d')) }}" 
                                       required>
                                @error('tanggal_retur')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="jumlah_retur">Jumlah Retur <span class="text-danger">*</span></label>
                                <input type="number" 
                                       class="form-control @error('jumlah_retur') is-invalid @enderror" 
                                       id="jumlah_retur" 
                                       name="jumlah_retur" 
                                       value="{{ old('jumlah_retur', $retur->jumlah_retur) }}" 
                                       min="1"
                                       step="1"
                                       required>
                                <small class="form-text text-muted">Jumlah saat ini: {{ $retur->jumlah_retur }} unit</small>
                                @error('jumlah_retur')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

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
                                   value="{{ old('nilai_retur', $retur->nilai_retur) }}" 
                                   min="0"
                                   step="0.01"
                                   required>
                        </div>
                        <small class="form-text text-muted">Nilai saat ini: Rp {{ number_format($retur->nilai_retur, 0, ',', '.') }}</small>
                        @error('nilai_retur')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="alasan">Alasan Retur <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('alasan') is-invalid @enderror" 
                                  id="alasan" 
                                  name="alasan" 
                                  rows="4" 
                                  required>{{ old('alasan', $retur->alasan) }}</textarea>
                        @error('alasan')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="alert alert-warning">
                        <i class="icon fas fa-exclamation-triangle"></i>
                        <strong>Perhatian!</strong> 
                        Perubahan jumlah retur akan mempengaruhi stok produk.
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update
                    </button>
                    <a href="{{ route('returpembelian.show', $retur->id) }}" class="btn btn-info">
                        <i class="fas fa-eye"></i> Lihat Detail
                    </a>
                    <a href="{{ route('returpembelian.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection