@extends('layouts.app')
@section('title', 'Tambah Karyawan')
@section('content')
<div class="container-fluid">

    <div class="card">
        <div class="card-body">
            <form action="{{ route('karyawan.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label>Nama</label>
                    <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}">
                    @error('nama') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label>Jabatan</label>
                    <input type="text" name="jabatan" class="form-control @error('jabatan') is-invalid @enderror" value="{{ old('jabatan') }}">
                    @error('jabatan') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label>No. Telepon</label>
                    <input type="text" name="no_telepon" class="form-control @error('no_telepon') is-invalid @enderror" value="{{ old('no_telepon') }}">
                    @error('no_telepon') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                {{-- <div class="form-group">
                    <label>User (Opsional)</label>
                    <select name="user_id" class="form-control">
                        <option value="">-- Tidak Ada --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->email }})
                            </option>
                        @endforeach
                    </select>
                </div> --}}

                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('karyawan.index') }}" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
</div>
@endsection