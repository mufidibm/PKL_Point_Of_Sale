@extends('layouts.app')

@section('title', 'Detail Transaksi Penjualan')

@section('content')


<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Invoice #{{$transaksiPenjualan->id }}</h3>
                        <div class="card-tools">
                            <a href="{{ route('penjualan.index') }}" class="btn btn-sm btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                            <a href="{{ route('penjualan.edit',$transaksiPenjualan->id) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <button onclick="window.print()" class="btn btn-sm btn-info">
                                <i class="fas fa-print"></i> Cetak
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Info Transaksi -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h5 class="mb-3"><strong>Informasi Transaksi</strong></h5>
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <td width="150"><strong>No. Transaksi</strong></td>
                                        <td>: {{$transaksiPenjualan->id }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Tanggal</strong></td>
                                        <td>: {{ \Carbon\Carbon::parse($transaksiPenjualan->tanggal)->format('d/m/Y H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Kasir</strong></td>
                                        <td>: {{$transaksiPenjualan->karyawan->nama ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Metode Bayar</strong></td>
                                        <td>: 
                                            <span class="badge badge-{{$transaksiPenjualan->metode_bayar == 'tunai' ? 'success' : ($transaksiPenjualan->metode_bayar == 'kartu' ? 'primary' : 'info') }}">
                                                {{ strtoupper($transaksiPenjualan->metode_bayar) }}
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h5 class="mb-3"><strong>Informasi Pelanggan</strong></h5>
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <td width="150"><strong>Nama</strong></td>
                                        <td>: {{$transaksiPenjualan->pelanggan->nama ?? 'Umum' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>No. Telepon</strong></td>
                                        <td>: {{$transaksiPenjualan->pelanggan->no_telp ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Membership</strong></td>
                                        <td>: 
                                            @if($transaksiPenjualan->pelanggan &&$transaksiPenjualan->pelanggan->membership)
                                                <span class="badge badge-primary">
                                                    {{$transaksiPenjualan->pelanggan->membership->nama_membership }}
                                                </span>
                                            @else
                                                <span class="badge badge-secondary">Non Member</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Diskon Member</strong></td>
                                        <td>: {{$transaksiPenjualan->pelanggan?->membership?->diskon_persen ?? 0 }}%</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <hr>

                        <!-- Tabel Detail Produk -->
                        <h5 class="mb-3"><strong>Detail Produk</strong></h5>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="bg-light">
                                    <tr>
                                        <th width="50" class="text-center">No</th>
                                        <th>Nama Produk</th>
                                        <th class="text-center" width="100">Jumlah</th>
                                        <th class="text-right" width="150">Harga Satuan</th>
                                        <th class="text-right" width="150">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($transaksiPenjualan->detailPenjualan as $index => $detail)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td>{{ $detail->produk->nama_produk ?? '-' }}</td>
                                        <td class="text-center">{{ $detail->jumlah }}</td>
                                        <td class="text-right">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                                        <td class="text-right">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Tidak ada detail produk</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Total -->
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <!-- Kosong atau bisa diisi catatan -->
                            </div>
                            <div class="col-md-6">
                                <table class="table table-sm">
                                    <tr>
                                        <td class="text-right"><strong>Subtotal:</strong></td>
                                        <td class="text-right" width="200">
                                            <strong>Rp {{ number_format($transaksiPenjualan->subtotal, 0, ',', '.') }}</strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-right"><strong>Diskon ({{$transaksiPenjualan->pelanggan?->membership?->diskon_persen ?? 0 }}%):</strong></td>
                                        <td class="text-right text-danger">
                                            <strong>- Rp {{ number_format($transaksiPenjualan->diskon, 0, ',', '.') }}</strong>
                                        </td>
                                    </tr>
                                    <tr class="bg-light">
                                        <td class="text-right"><h5><strong>Total Bayar:</strong></h5></td>
                                        <td class="text-right">
                                            <h5><strong>Rp {{ number_format($transaksiPenjualan->total_bayar, 0, ',', '.') }}</strong></h5>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
@media print {
    .content-header,
    .card-tools,
    .breadcrumb,
    .main-sidebar,
    .main-header {
        display: none !important;
    }
    .card {
        border: none !important;
        box-shadow: none !important;
    }
}
</style>
@endsection