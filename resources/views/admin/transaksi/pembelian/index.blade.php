@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1 class="mb-3">Daftar Transaksi Pembelian</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('transaksi.pembelian.create') }}" class="btn btn-primary mb-3">
        + Pembelian Baru
    </a>

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>No PO</th>
                        <th>Tanggal</th>
                        <th>Supplier</th>
                        <th>Karyawan</th>
                        <th>Total Biaya</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transaksis as $t)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><strong>{{ $t->no_po }}</strong></td>
                            <td>{{ $t->tanggal->format('d/m/Y') }}</td>
                            <td>{{ $t->supplier?->nama ?? '-' }}</td>
                            <td>{{ $t->karyawan?->nama ?? '-' }}</td>
                            <td><strong>Rp {{ number_format($t->total_biaya, 0, ',', '.') }}</strong></td>
                            <td>
                                <a href="{{ route('transaksi.pembelian.show', $t->id) }}" class="btn btn-info btn-sm">
                                    Lihat
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Belum ada transaksi pembelian.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection