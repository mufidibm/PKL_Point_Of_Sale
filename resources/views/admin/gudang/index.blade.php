@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1 class="mb-3">Data Gudang</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('gudang.create') }}" class="btn btn-primary mb-3">+ Tambah Gudang</a>

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th>Nama Gudang</th>
                        <th>Lokasi</th>
                        <th width="20%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($gudangs as $gudang)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $gudang->nama_gudang }}</td>
                            <td>{{ $gudang->lokasi }}</td>
                            <td>
                                <a href="{{ route('gudang.edit', $gudang->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('gudang.destroy', $gudang->id) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Yakin hapus gudang ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center">Tidak ada data gudang.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection