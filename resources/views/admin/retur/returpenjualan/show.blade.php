@extends('layouts.app')

@section('title', 'Detail Retur Penjualan')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Detail Retur Penjualan</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('returpenjualan.index') }}">Retur Penjualan</a></li>
                    <li class="breadcrumb-item active">Detail</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Informasi Retur Penjualan</h3>
                <div class="card-tools">
                    <a href="{{ route('returpenjualan.edit', $retur->id) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">ID Retur</th>
                                <td>: {{ $retur->id }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal Retur</th>
                                <td>: {{ \Carbon\Carbon::parse($retur->tanggal_retur)->format('d F Y') }}</td>
                            </tr>
                            <tr>
                                <th>No. Transaksi</th>
                                <td>: {{ $retur->transaksi->nomor_transaksi ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal Transaksi</th>
                                <td>: {{ $retur->transaksi ? \Carbon\Carbon::parse($retur->transaksi->tanggal_transaksi)->format('d F Y') : '-' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">Nama Produk</th>
                                <td>: {{ $retur->produk->nama_produk ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Kode Produk</th>
                                <td>: {{ $retur->produk->kode_produk ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Jumlah Retur</th>
                                <td>: {{ $retur->jumlah_retur }} unit</td>
                            </tr>
                            <tr>
                                <th>Nilai Retur</th>
                                <td>: <strong class="text-danger">Rp {{ number_format($retur->nilai_retur, 0, ',', '.') }}</strong></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-12">
                        <h5>Alasan Retur</h5>
                        <div class="alert alert-info">
                            {{ $retur->alasan }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('returpenjualan.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
                <a href="{{ route('returpenjualan.edit', $retur->id) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <form action="{{ route('returpenjualan.destroy', $retur->id) }}" 
                      method="POST" 
                      style="display: inline;"
                      onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection