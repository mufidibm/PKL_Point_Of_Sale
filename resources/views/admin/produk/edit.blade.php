@extends('layouts.app')
@section('title', 'Edit Produk')
@section('content')
<div class="container-fluid">

    <div class="card">
        <div class="card-body">
            <form action="{{ route('produk.update', $produk->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group mb-3">
                    <label>Nama Produk</label>
                    <input type="text" name="nama_produk" class="form-control @error('nama_produk') is-invalid @enderror" value="{{ old('nama_produk', $produk->nama_produk) }}">
                    @error('nama_produk')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label>Barcode</label>
                    <input type="text" name="barcode" class="form-control @error('barcode') is-invalid @enderror" value="{{ old('barcode', $produk->barcode) }}">
                    @error('barcode')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Harga Beli</label>
                        <input type="number" step="0.01" name="harga_beli" class="form-control @error('harga_beli') is-invalid @enderror" value="{{ old('harga_beli', $produk->harga_beli) }}">
                        @error('harga_beli')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Harga Jual</label>
                        <input type="number" step="0.01" name="harga_jual" class="form-control @error('harga_jual') is-invalid @enderror" value="{{ old('harga_jual', $produk->harga_jual) }}">
                        @error('harga_jual')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label>Satuan</label>
                    <input type="text" name="satuan" class="form-control @error('satuan') is-invalid @enderror" value="{{ old('satuan', $produk->satuan) }}">
                    @error('satuan')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label>Kategori</label>
                    <select name="kategori_id" class="form-control @error('kategori_id') is-invalid @enderror">
                        @foreach ($kategori as $k)
                            <option value="{{ $k->id }}" {{ old('kategori_id', $produk->kategori_id) == $k->id ? 'selected' : '' }}>
                                {{ $k->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                    @error('kategori_id')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mt-4">
                    <a href="{{ route('produk.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
