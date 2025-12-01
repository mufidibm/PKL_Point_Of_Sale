@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h1 class="mb-3">Daftar Supplier</h1>
    <a href="{{ route('supplier.create') }}" class="btn btn-primary mb-3">
        <i class="fas fa-plus"></i> Tambah Supplier
    </a>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Nama Supplier</th>
                <th>No Telepon</th>
                <th>Alamat</th>
                <th width="20%">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($suppliers as $supplier)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $supplier->nama }}</td>
                    <td>{{ $supplier->no_telepon ?? '-' }}</td>
                    <td>{{ $supplier->alamat ?? '-' }}</td>
                    <td>
                        <a href="{{ route('supplier.edit', $supplier->id) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('supplier.destroy', $supplier->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus supplier ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
