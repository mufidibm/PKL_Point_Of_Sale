@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h1 class="mb-3">Tambah Gudang</h1>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('gudang.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Nama Gudang</label>
                    <input type="text" name="nama_gudang" class="form-control @error('nama_gudang') is-invalid @enderror" value="{{ old('nama_gudang') }}">
                    @error('nama_gudang') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label>Lokasi</label>
                    <textarea name="lokasi" class="form-control @error('lokasi') is-invalid @enderror">{{ old('lokasi') }}</textarea>
                    @error('lokasi') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('gudang.index') }}" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
</div>
@endsection