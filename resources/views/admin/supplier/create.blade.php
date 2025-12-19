@extends('layouts.app')
@section('title', 'Tambah Supplier')
@section('content')
<div class="container-fluid">

    <div class="card">
        <div class="card-body">
            <form action="{{ route('supplier.store') }}" method="POST">
                @csrf

                <div class="form-group mb-3">
                    <label>Nama Supplier</label>
                    <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}" required>
                    @error('nama')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label>No Telepon</label>
                    <input type="text" name="no_telepon" class="form-control @error('no_telepon') is-invalid @enderror" value="{{ old('no_telepon') }}">
                    @error('no_telepon')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label>Alamat</label>
                    <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror" rows="3">{{ old('alamat') }}</textarea>
                    @error('alamat')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mt-4">
                    <a href="{{ route('supplier.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
