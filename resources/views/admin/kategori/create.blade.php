@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1 class="mb-3">Tambah Kategori</h1>

    <form action="{{ route('kategori.store') }}" method="POST">
        @csrf
        <div class="form-group mb-3">
            <label for="nama_kategori">Nama Kategori</label>
            <input type="text" name="nama_kategori" class="form-control" value="{{ old('nama_kategori') }}" required>
            @error('nama_kategori')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="{{ route('kategori.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
