<div class="card mt-3">
    <div class="card-header d-flex justify-content-between">
        <h5>Laporan Pembelian</h5>
        <div>
            <a href="{{ route('laporan.export', [
                    'type' => 'pembelian',
                    'tanggal_mulai' => $mulai,
                    'tanggal_selesai' => $selesai,
                    'format' => 'pdf'
                ]) }}" class="btn btn-sm btn-danger">PDF</a>
            <a href="{{ route('laporan.export', [
                    'type' => 'pembelian',
                    'tanggal_mulai' => $mulai,
                    'tanggal_selesai' => $selesai,
                    'format' => 'excel'
                ]) }}" class="btn btn-sm btn-success">Excel</a>
        </div>
    </div>
    <div class="card-body">
        <canvas id="chartPembelian" height="100"></canvas>
        <hr>
        <table class="table table-sm table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>No PO</th>
                    <th>Tanggal</th>
                    <th>Supplier</th>
                    <th>Total Bayar</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pembelian as $t)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $t->no_po }}</td>
                        <td>{{ $t->tanggal->format('d/m/Y') }}</td>
                        <td>{{ $t->supplier?->nama ?? '-' }}</td>
                        <td>Rp {{ number_format($t->total_bayar, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center">Tidak ada data</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>