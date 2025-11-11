<!DOCTYPE html>
<html>
<head><title>Laporan {{ ucfirst($type) }}</title></head>
<body>
    <h3>{{ $title }}</h3>
    <table border="1" cellpadding="5" cellspacing="0" width="100%">
        @if($type == 'penjualan')
            <tr><th>No</th><th>Invoice</th><th>Tanggal</th><th>Pelanggan</th><th>Total</th></tr>
            @foreach($data as $i => $t)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $t->no_invoice }}</td>
                <td>{{ $t->tanggal->format('d/m/Y') }}</td>
                <td>{{ $t->pelanggan?->nama ?? '-' }}</td>
                <td>Rp {{ number_format($t->total_bayar, 0) }}</td>
            </tr>
            @endforeach
        @else
            <!-- Sama untuk pembelian -->
        @endif
    </table>
</body>
</html>