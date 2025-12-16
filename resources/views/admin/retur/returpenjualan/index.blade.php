@extends('layouts.app')

@section('title', 'Retur Penjualan')

@section('content')

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data Retur Penjualan</h3>
                <div class="card-tools">
                    <a href="{{ route('returpenjualan.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Tambah Retur
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal Retur</th>
                                <th>No. Transaksi</th>
                                <th>Produk</th>
                                <th>Jumlah</th>
                                <th>Nilai Retur</th>
                                <th>Alasan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($returs as $retur)
                                <tr>
                                    <td>{{ $loop->iteration + ($returs->currentPage() - 1) * $returs->perPage() }}</td>
                                    <td>{{ \Carbon\Carbon::parse($retur->tanggal_retur)->format('d/m/Y') }}</td>
                                    <td>{{ $retur->transaksi->nomor_transaksi ?? '-' }}</td>
                                    <td>{{ $retur->produk->nama_produk ?? '-' }}</td>
                                    <td>{{ $retur->jumlah_retur }}</td>
                                    <td>Rp {{ number_format($retur->nilai_retur, 0, ',', '.') }}</td>
                                    <td>{{ $retur->alasan }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('returpenjualan.show', $retur->id) }}" 
                                               class="btn btn-info btn-sm" title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('returpenjualan.edit', $retur->id) }}" 
                                               class="btn btn-warning btn-sm" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('returpenjualan.destroy', $retur->id) }}" 
                                                  method="POST" 
                                                  style="display: inline;"
                                                  onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">Tidak ada data retur penjualan</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer clearfix">
                {{ $returs->links() }}
            </div>
        </div>
    </div>
</section>
@endsection