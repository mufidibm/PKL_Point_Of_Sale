@extends('layouts.app')
@section('title', 'Data Membership')

@section('content')
<div class="container-fluid">

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('membership.create') }}" class="btn btn-primary mb-3">+ Tambah Membership</a>

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Membership</th>
                        <th>Diskon (%)</th>
                        <th width="20%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($memberships as $membership)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $membership->nama_membership }}</td>
                            <td>{{ number_format($membership->diskon_persen, 2) }}%</td>
                            <td>
                                <a href="{{ route('membership.edit', $membership->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('membership.destroy', $membership->id) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Yakin hapus membership ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center">Tidak ada data membership.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection