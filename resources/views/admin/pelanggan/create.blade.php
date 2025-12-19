@extends('layouts.app')
@section('title', 'Tambah Pelanggan')
@section('content')
<div class="container-fluid">

    <div class="card">
        <div class="card-body">
            <form action="{{ route('pelanggan.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label>Nama</label>
                    <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}">
                    @error('nama') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label>No. Telepon</label>
                    <input type="text" name="no_telepon" class="form-control @error('no_telepon') is-invalid @enderror" value="{{ old('no_telepon') }}">
                    @error('no_telepon') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label>Alamat</label>
                    <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror">{{ old('alamat') }}</textarea>
                    @error('alamat') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label>Membership</label>
                    <select name="membership_id" class="form-control">
                        <option value="">-- Tidak Ada --</option>
                        @foreach($memberships as $membership)
                            <option value="{{ $membership->id }}" {{ old('membership_id') == $membership->id ? 'selected' : '' }}>
                                {{ $membership->nama_membership }} ({{ $membership->diskon_persen }}%)
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('pelanggan.index') }}" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
</div>
@endsection