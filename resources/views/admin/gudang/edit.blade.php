@extends('layouts.app')
@section('title', 'Edit Gudang')
@section('content')
<div class="container-fluid">

    <div class="card">
        <div class="card-body">
            <form action="{{ route('gudang.update', $gudang->id) }}" method="POST">
                @csrf @method('PUT')
                <div class="form-group">
                    <label>Nama Gudang</label>
                    <input type="text" name="nama_gudang" class="form-control @error('nama_gudang') is-invalid @enderror" value="{{ old('nama_gudang', $gudang->nama_gudang) }}">
                    @error('nama_gudang') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label>Lokasi</label>
                    <textarea name="lokasi" class="form-control @error('lokasi') is-invalid @enderror">{{ old('lokasi', $gudang->lokasi) }}</textarea>
                    @error('lokasi') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <button type="submit" class="btn btn-warning">Update</button>
                <a href="{{ route('gudang.index') }}" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
</div>
@endsection