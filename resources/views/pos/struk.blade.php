<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pembayaran - {{ $transaksi->no_invoice }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            line-height: 1.4;
            padding: 20px;
            background-color: #f5f5f5;
        }

        .struk-container {
            width: 80mm;
            margin: 0 auto;
            background: white;
            padding: 10mm;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 2px dashed #000;
            padding-bottom: 10px;
        }

        .header h2 {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .header p {
            font-size: 11px;
            margin: 2px 0;
        }

        .info-transaksi {
            margin-bottom: 15px;
            font-size: 11px;
        }

        .info-transaksi table {
            width: 100%;
        }

        .info-transaksi td {
            padding: 2px 0;
        }

        .info-transaksi td:first-child {
            width: 40%;
        }

        .items {
            margin-bottom: 15px;
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
        }

        .item {
            margin-bottom: 8px;
        }

        .item-name {
            font-weight: bold;
            margin-bottom: 2px;
        }

        .item-detail {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
        }

        .totals {
            margin-bottom: 15px;
        }

        .totals table {
            width: 100%;
            font-size: 12px;
        }

        .totals td {
            padding: 3px 0;
        }

        .totals td:last-child {
            text-align: right;
        }

        .total-row {
            font-weight: bold;
            font-size: 14px;
            border-top: 1px solid #000;
            padding-top: 5px !important;
        }

        .payment-info {
            margin-bottom: 15px;
            border-top: 1px dashed #000;
            padding-top: 10px;
        }

        .payment-info table {
            width: 100%;
            font-size: 12px;
        }

        .payment-info td {
            padding: 3px 0;
        }

        .payment-info td:last-child {
            text-align: right;
            font-weight: bold;
        }

        .kembalian-row td {
            font-size: 16px;
            font-weight: bold;
            padding-top: 8px !important;
        }

        .footer {
            text-align: center;
            font-size: 11px;
            margin-top: 15px;
            border-top: 2px dashed #000;
            padding-top: 10px;
        }

        .footer p {
            margin: 3px 0;
        }

        .thank-you {
            font-weight: bold;
            font-size: 13px;
            margin-top: 10px;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }

            .struk-container {
                width: 80mm;
                box-shadow: none;
                padding: 5mm;
            }

            .no-print {
                display: none;
            }
        }

        .btn-container {
            text-align: center;
            margin-top: 20px;
        }

        .btn {
            padding: 10px 20px;
            margin: 0 5px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            font-family: Arial, sans-serif;
        }

        .btn-print {
            background-color: #28a745;
            color: white;
        }

        .btn-close {
            background-color: #dc3545;
            color: white;
        }

        .btn:hover {
            opacity: 0.8;
        }
    </style>
</head>
<body onload="window.print()">
    <div class="struk-container">
        <!-- Header Toko -->
        <div class="header">
            <h2>NAMA TOKO ANDA</h2>
            <p>Jl. Alamat Toko No. 123</p>
            <p>Telp: (021) 1234-5678</p>
            <p>Email: toko@email.com</p>
        </div>

        <!-- Info Transaksi -->
        <div class="info-transaksi">
            <table>
                <tr>
                    <td>No Invoice</td>
                    <td>: <strong>{{ $transaksi->no_invoice }}</strong></td>
                </tr>
                <tr>
                    <td>Tanggal</td>
                    <td>: {{ \Carbon\Carbon::parse($transaksi->tanggal)->format('d/m/Y H:i') }}</td>
                </tr>
                <tr>
                    <td>Kasir</td>
                    <td>: {{ auth()->user()->name ?? 'Admin' }}</td>
                </tr>
                @if($transaksi->membership)
                <tr>
                    <td>Member</td>
                    <td>: {{ $transaksi->membership->nama_membership }}</td>
                </tr>
                @endif
            </table>
        </div>

        <!-- Daftar Item -->
        <div class="items">
            @foreach($transaksi->detailPenjualans as $detail)
            <div class="item">
                <div class="item-name">{{ $detail->produk->nama_produk }}</div>
                <div class="item-detail">
                    <span>{{ $detail->jumlah }} x Rp {{ number_format($detail->harga_jual, 0, ',', '.') }}</span>
                    <span>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</span>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Total -->
        <div class="totals">
            <table>
                <tr>
                    <td>Subtotal</td>
                    <td>Rp {{ number_format($transaksi->subtotal, 0, ',', '.') }}</td>
                </tr>
                @if($transaksi->diskon > 0)
                <tr>
                    <td>Diskon Member</td>
                    <td>(Rp {{ number_format($transaksi->diskon, 0, ',', '.') }})</td>
                </tr>
                @endif
                <tr class="total-row">
                    <td>TOTAL</td>
                    <td>Rp {{ number_format($transaksi->total_bayar, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>

        <!-- Info Pembayaran -->
        <div class="payment-info">
            <table>
                <tr>
                    <td>Metode Bayar</td>
                    <td>{{ strtoupper($transaksi->metode_bayar ?? 'TUNAI') }}</td>
                </tr>
                <tr>
                    <td>Uang Dibayar</td>
                    <td>Rp {{ number_format($transaksi->total_bayar, 0, ',', '.') }}</td>
                </tr>
                <tr class="kembalian-row">
                    <td>KEMBALIAN</td>
                    <td>Rp {{ number_format(0, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>{{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}</p>
            <p>Barang yang sudah dibeli tidak dapat dikembalikan</p>
            <p class="thank-you">*** TERIMA KASIH ***</p>
            <p>Selamat Berbelanja Kembali</p>
        </div>
    </div>

    <!-- Tombol Action (tidak tercetak) -->
    <div class="btn-container no-print">
        <button class="btn btn-print" onclick="window.print()">
            🖨️ Cetak Ulang
        </button>
        <button class="btn btn-close" onclick="window.close()">
            ✖️ Tutup
        </button>
    </div>

    <script>
        // Auto close setelah print (opsional)
        window.onafterprint = function() {
            // window.close(); // Uncomment jika ingin auto close
        }
    </script>
</body>
</html>