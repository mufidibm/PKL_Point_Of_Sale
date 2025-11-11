@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1 class="mb-3">Edit Stok Gudang</h1>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('stokgudang.update', $stokgudang->id) }}" method="POST">
                @csrf @method('PUT')

                <div class="form-group">
                    <label>Produk</label>
                    <input type="text" class="form-control" value="{{ $stokgudang->produk->nama_produk }}" disabled>
                </div>

                <div class="form-group">
                    <label>Gudang</label>
                    <input type="text" class="form-control" value="{{ $stokgudang->gudang->nama_gudang }}" disabled>
                </div>

                <div class="form-group">
                    <label>Jumlah Stok</label>
                    <input type="number" name="jumlah_stok" min="0" class="form-control @error('jumlah_stok') is-invalid @enderror" value="{{ old('jumlah_stok', $stokgudang->jumlah_stok) }}">
                    @error('jumlah_stok') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <button type="submit" class="btn btn-warning">Update</button>
                <a href="{{ route('stokgudang.index') }}" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
</div>
@endsection