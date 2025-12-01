
@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h1 class="mb-3">Daftar Produk</h1>
    <a href="{{ route('produk.create') }}" class="btn btn-primary mb-3">
        <i class="fas fa-plus"></i> Tambah Produk
    </a>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Produk</th>
                <th>Barcode</th>
                <th>Harga Beli</th>
                <th>Harga Jual</th>
                <th>Satuan</th>
                <th>Kategori</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($produk as $p)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $p->nama_produk }}</td>
                <td>{{ $p->barcode }}</td>
                <td>Rp {{ number_format($p->harga_beli, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($p->harga_jual, 0, ',', '.') }}</td>
                <td>{{ $p->satuan }}</td>
                <td>{{ $p->kategori->nama_kategori ?? '-' }}</td>
                <td>
                    <a href="{{ route('produk.edit', $p->id) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form action="{{ route('produk.destroy', $p->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button onclick="return confirm('Hapus produk ini?')" class="btn btn-danger btn-sm">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
