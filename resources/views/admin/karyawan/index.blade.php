@extends('layouts.app')
@section('title', 'Data Karyawan')

@section('content')
<div class="container-fluid">

    <a href="{{ route('karyawan.create') }}" class="btn btn-primary mb-3">+ Tambah Karyawan</a>

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Jabatan</th>
                        <th>No. Telepon</th>
                        {{-- <th>User</th> --}}
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($karyawans as $karyawan)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $karyawan->nama }}</td>
                            <td>{{ $karyawan->jabatan }}</td>
                            <td>{{ $karyawan->no_telepon }}</td>
                            {{-- <td>{{ $karyawan->user?->name ?? '-' }}</td> --}}
                            <td>
                                <a href="{{ route('karyawan.edit', $karyawan->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('karyawan.destroy', $karyawan->id) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Yakin hapus?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center">Tidak ada data karyawan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection