@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1 class="mb-3">Laporan Transaksi</h1>

    <!-- Filter -->
    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('laporan.index') }}">
                <div class="row">
                    <div class="col-md-3">
                        <label>Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" class="form-control" value="{{ $mulai }}">
                    </div>
                    <div class="col-md-3">
                        <label>Tanggal Selesai</label>
                        <input type="date" name="tanggal_selesai" class="form-control" value="{{ $selesai }}">
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">Filter</button>
                        <a href="{{ route('laporan.index') }}" class="btn btn-secondary">Reset</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabs -->
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a href="#penjualan" class="nav-link {{ $type === 'penjualan' ? 'active' : '' }}" data-toggle="tab">Penjualan</a>
            </li>
            <li class="nav-item">
                <a href="#pembelian" class="nav-link {{ $type === 'pembelian' ? 'active' : '' }}" data-toggle="tab">Pembelian</a>
            </li>
        </ul>

        <div class="tab-content">
            <!-- Penjualan Tab -->
            <div class="tab-pane {{ $type === 'penjualan' ? 'active' : '' }}" id="penjualan">
                @include('admin.laporan.partials.penjualan')
            </div>

            <!-- Pembelian Tab -->
            <div class="tab-pane {{ $type === 'pembelian' ? 'active' : '' }}" id="pembelian">
                @include('admin.laporan.partials.pembelian')
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx1 = document.getElementById('chartPenjualan')?.getContext('2d');
    if (ctx1) {
        new Chart(ctx1, {
            type: 'line',
            data: {
                labels: @json($chartPenjualan->pluck('date')),
                datasets: [{
                    label: 'Total Penjualan',
                    data: @json($chartPenjualan->pluck('total')),
                    borderColor: 'rgb(54, 162, 235)',
                    tension: 0.1
                }]
            },
            options: { responsive: true, scales: { y: { beginAtZero: true } } }
        });
    }

    const ctx2 = document.getElementById('chartPembelian')?.getContext('2d');
    if (ctx2) {
        new Chart(ctx2, {
            type: 'line',
            data: {
                labels: @json($chartPembelian->pluck('date')),
                datasets: [{
                    label: 'Total Pembelian',
                    data: @json($chartPembelian->pluck('total')),
                    borderColor: 'rgb(255, 99, 132)',
                    tension: 0.1
                }]
            },
            options: { responsive: true, scales: { y: { beginAtZero: true } } }
        });
    }
</script>
@endpush