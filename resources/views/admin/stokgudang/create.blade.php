@extends('layouts.app')
@section('title', 'Tambah Stok Gudang')
@section('content')
<div class="container-fluid">

    <div class="card">
        <div class="card-body">
            <form action="{{ route('stokgudang.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label>Produk</label>
                    <select name="produk_id" class="form-control @error('produk_id') is-invalid @enderror">
                        <option value="">-- Pilih Produk --</option>
                        @foreach($produks as $produk)
                            <option value="{{ $produk->id }}" {{ old('produk_id') == $produk->id ? 'selected' : '' }}>
                                {{ $produk->nama_produk }}
                            </option>
                        @endforeach
                    </select>
                    @error('produk_id') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label>Gudang</label>
                    <select name="gudang_id" class="form-control @error('gudang_id') is-invalid @enderror">
                        <option value="">-- Pilih Gudang --</option>
                        @foreach($gudangs as $gudang)
                            <option value="{{ $gudang->id }}" {{ old('gudang_id') == $gudang->id ? 'selected' : '' }}>
                                {{ $gudang->nama_gudang }}
                            </option>
                        @endforeach
                    </select>
                    @error('gudang_id') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label>Jumlah Stok</label>
                    <input type="number" name="jumlah_stok" min="0" class="form-control @error('jumlah_stok') is-invalid @enderror" value="{{ old('jumlah_stok') }}">
                    @error('jumlah_stok') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('stokgudang.index') }}" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
</div>
@endsection