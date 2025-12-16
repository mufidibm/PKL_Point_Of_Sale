<div class="card mt-3">
    <div class="card-header d-flex justify-content-between">
        <h5>Laporan Penjualan</h5>
        <div>
            <a href="{{ route('laporan.export', ['type' => 'penjualan', 'tanggal_mulai' => $mulai, 'tanggal_selesai' => $selesai, 'format' => 'pdf']) }}"
               class="btn btn-sm btn-danger">PDF</a>
            <a href="{{ route('laporan.export', ['type' => 'penjualan', 'tanggal_mulai' => $mulai, 'tanggal_selesai' => $selesai, 'format' => 'excel']) }}"
               class="btn btn-sm btn-success">Excel</a>
        </div>
    </div>
    <div class="card-body">
        <canvas id="chartPenjualan"
                height="100"></canvas>
        <hr>
        <table class="table table-sm table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Invoice</th>
                    <th>Tanggal</th>
                    <th>Pelanggan</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse($penjualan as $t)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $t->no_invoice }}</td>
                        <td>{{ $t->tanggal->format('d/m/Y') }}</td>
                        <td>{{ $t->pelanggan?->nama ?? '-' }}</td>
                        <td>Rp {{ number_format($t->total_bayar, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5"
                            class="text-center">Tidak ada data</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="d-flex mt-4 justify-content-center">
            {{ $penjualan->links() }}
        </div>
    </div>
</div>