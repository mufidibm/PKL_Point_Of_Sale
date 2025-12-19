@extends('layouts.app')
@section('title', 'Daftar Transaksi Penjualan')

@section('content')
<div class="container-fluid">

    <a href="{{ route('penjualan.create') }}" class="btn btn-primary mb-3">
        + Transaksi Baru
    </a>

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>No Invoice</th>
                        <th>Tanggal</th>
                        <th>Pelanggan</th>
                        <th>Karyawan</th>
                        <th>Subtotal</th>
                        <th>Diskon</th> 
                        <th>Total</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transaksis as $t)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><strong>{{ $t->no_invoice }}</strong></td>
                            <td>{{ $t->tanggal->format('d/m/Y') }}</td>
                            <td>
                                {{ $t->pelanggan?->nama ?? 'Umum' }}
                                @if($t->pelanggan?->membership)
                                    <small class="badge badge-info">
                                        {{ $t->pelanggan->membership->nama_membership }}
                                    </small>
                                @endif
                            </td>
                            <td>{{ $t->karyawan?->nama ?? '-' }}</td>
                            <td>Rp {{ number_format($t->subtotal, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($t->diskon, 0, ',', '.') }}</td>
                            <td><strong>Rp {{ number_format($t->total_bayar, 0, ',', '.') }}</strong></td>
                            <td>
                                <a href="{{ route('penjualan.show', $t->id) }}" class="btn btn-info btn-sm">
                                    Lihat
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">Belum ada transaksi penjualan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection