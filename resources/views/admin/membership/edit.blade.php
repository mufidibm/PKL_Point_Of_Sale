@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h1 class="mb-3">Edit Membership</h1>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('membership.update', $membership->id) }}" method="POST">
                @csrf @method('PUT')

                <div class="form-group">
                    <label>Nama Membership</label>
                    <input type="text" name="nama_membership" class="form-control @error('nama_membership') is-invalid @enderror" value="{{ old('nama_membership', $membership->nama_membership) }}">
                    @error('nama_membership') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label>Diskon (%)</label>
                    <input type="number" name="diskon_persen" min="0" max="100" step="0.01" class="form-control @error('diskon_persen') is-invalid @enderror" value="{{ old('diskon_persen', $membership->diskon_persen) }}">
                    @error('diskon_persen') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <button type="submit" class="btn btn-warning">Update</button>
                <a href="{{ route('membership.index') }}" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
</div>
@endsection