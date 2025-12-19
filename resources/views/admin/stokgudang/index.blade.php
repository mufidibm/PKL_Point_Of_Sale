@extends('layouts.app')
@section('title', 'Data Stok Gudang')

@section('content')
<div class="container-fluid">

    <a href="{{ route('stokgudang.create') }}" class="btn btn-primary mb-3">+ Tambah Stok</a>

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Produk</th>
                        <th>Gudang</th>
                        <th>Jumlah Stok</th>
                        <th width="20%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stoks as $stok)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $stok->produk->nama_produk }}</td>
                            <td>{{ $stok->gudang->nama_gudang }}</td>
                            <td>{{ $stok->jumlah_stok }}</td>
                            <td>
                                <a href="{{ route('stokgudang.edit', $stok->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('stokgudang.destroy', $stok->id) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Yakin hapus stok ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center">Tidak ada data stok.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection