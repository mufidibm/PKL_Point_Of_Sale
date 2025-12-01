@extends('layouts.admin')

@section('title', 'Detail Retur Pembelian')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Detail Retur Pembelian</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('returpembelian.index') }}">Retur Pembelian</a></li>
                    <li class="breadcrumb-item active">Detail</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- Info Retur -->
            <div class="col-md-8">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-undo-alt"></i> Informasi Retur
                        </h3>
                        <div class="card-tools">
                            <a href="{{ route('returpembelian.edit', $retur->id) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <th width="40%">ID Retur</th>
                                        <td>: <span class="badge badge-secondary">{{ $retur->id }}</span></td>
                                    </tr>
                                    <tr>
                                        <th>Tanggal Retur</th>
                                        <td>: {{ \Carbon\Carbon::parse($retur->tanggal_retur)->format('d F Y') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Jumlah Retur</th>
                                        <td>: <strong>{{ $retur->jumlah_retur }} unit</strong></td>
                                    </tr>
                                    <tr>
                                        <th>Nilai Retur</th>
                                        <td>: <span class="text-danger font-weight-bold">Rp {{ number_format($retur->nilai_retur, 0, ',', '.') }}</span></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <th width="40%">No. Transaksi</th>
                                        <td>: <span class="badge badge-info">{{ $retur->transaksi->nomor_transaksi ?? '-' }}</span></td>
                                    </tr>
                                    <tr>
                                        <th>Tanggal Transaksi</th>
                                        <td>: {{ $retur->transaksi ? \Carbon\Carbon::parse($retur->transaksi->tanggal_transaksi)->format('d F Y') : '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Supplier</th>
                                        <td>: {{ $retur->transaksi->supplier->nama_supplier ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Telepon Supplier</th>
                                        <td>: {{ $retur->transaksi->supplier->telepon ?? '-' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <hr>

                        <h5><i class="fas fa-box"></i> Detail Produk</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Kode Produk</th>
                                        <th>Nama Produk</th>
                                        <th>Kategori</th>
                                        <th>Barcode</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><strong>{{ $retur->produk->kode_produk ?? '-' }}</strong></td>
                                        <td>{{ $retur->produk->nama_produk ?? '-' }}</td>
                                        <td>
                                            @if($retur->produk && $retur->produk->kategori)
                                                <span class="badge badge-primary">{{ $retur->produk->kategori->nama_kategori }}</span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ $retur->produk->barcode ?? '-' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <hr>

                        <h5><i class="fas fa-comment-alt"></i> Alasan Retur</h5>
                        <div class="alert alert-warning">
                            <p class="mb-0">{{ $retur->alasan }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Summary & Actions -->
            <div class="col-md-4">
                <!-- Summary Card -->
                <div class="card card-widget widget-user-2">
                    <div class="widget-user-header bg-warning">
                        <h3 class="widget-user-username">Summary</h3>
                        <h5 class="widget-user-desc">Ringkasan Retur</h5>
                    </div>
                    <div class="card-footer p-0">
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <span class="nav-link">
                                    Jumlah Diretur
                                    <span class="float-right badge badge-warning">{{ $retur->jumlah_retur }} unit</span>
                                </span>
                            </li>
                            <li class="nav-item">
                                <span class="nav-link">
                                    Total Nilai
                                    <span class="float-right badge badge-danger">Rp {{ number_format($retur->nilai_retur, 0, ',', '.') }}</span>
                                </span>
                            </li>
                            <li class="nav-item">
                                <span class="nav-link">
                                    Nilai Per Unit
                                    <span class="float-right badge badge-info">Rp {{ number_format($retur->nilai_retur / $retur->jumlah_retur, 0, ',', '.') }}</span>
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Actions Card -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Aksi</h3>
                    </div>
                    <div class="card-body">
                        <a href="{{ route('returpembelian.index') }}" class="btn btn-secondary btn-block">
                            <i class="fas fa-arrow-left"></i> Kembali ke Daftar
                        </a>
                        <a href="{{ route('returpembelian.edit', $retur->id) }}" class="btn btn-warning btn-block">
                            <i class="fas fa-edit"></i> Edit Retur
                        </a>
                        <form action="{{ route('returpembelian.destroy', $retur->id) }}" 
                              method="POST" 
                              onsubmit="return confirm('Yakin ingin menghapus data retur ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-block">
                                <i class="fas fa-trash"></i> Hapus Retur
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Info Card -->
                <div class="info-box bg-info">
                    <span class="info-box-icon"><i class="fas fa-info-circle"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Informasi</span>
                        <span class="info-box-number">Stok telah dikurangi</span>
                        <div class="progress">
                            <div class="progress-bar" style="width: 100%"></div>
                        </div>
                        <span class="progress-description">
                            Barang dikembalikan ke supplier
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection