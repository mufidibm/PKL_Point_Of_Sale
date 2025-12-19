@extends('layouts.app')
@section('title', 'Edit Transaksi Penjualan')
@section('content')
<div class="container-fluid">
    <h1 class="mb-3">Edit Transaksi Penjualan #{{ $transaksiPenjualan->no_invoice }}</h1>

    <form action="{{ route('penjualan.update', $transaksiPenjualan->id) }}" method="POST" id="form-penjualan">
        @csrf @method('PUT')
        <div class="card">
            <div class="card-body">
                <!-- Header -->
                <div class="row mb-3">
                    <div class="col-md-3">
    <label class="form-label">Pelanggan</label>
    <select name="pelanggan_id" class="form-control" id="pelanggan-select" required>
        <option value="{{ $pelangganUmum->id }}" 
                data-diskon="0" 
                {{ old('pelanggan_id', $transaksiPenjualan->pelanggan_id ?? $pelangganUmum->id) == $pelangganUmum->id ? 'selected' : '' }}>
            Umum (Non Member)
        </option>
        @foreach($pelanggans as $p)
            @if($p->nama !== 'Umum') {{-- agar Umum tidak muncul 2x --}}
                <option value="{{ $p->id }}"
                        data-diskon="{{ $p->membership?->diskon_persen ?? 0 }}"
                        {{ old('pelanggan_id', $transaksiPenjualan->pelanggan_id ?? null) == $p->id ? 'selected' : '' }}>
                    {{ $p->nama }} @if($p->membership) ({{ $p->membership->nama_membership }}) @endif
                </option>
            @endif
        @endforeach
    </select>
</div>
                    <div class="col-md-3">
                        <label>Karyawan <span class="text-danger">*</span></label>
                        <select name="karyawan_id" class="form-control" required>
                            <option value="">-- Pilih Karyawan --</option>
                            @foreach($karyawans as $k)
                                <option value="{{ $k->id }}" {{ $k->id == $transaksiPenjualan->karyawan_id ? 'selected' : '' }}>
                                    {{ $k->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Tanggal <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal" class="form-control"
                               value="{{ old('tanggal', $transaksiPenjualan->tanggal->format('Y-m-d')) }}" required>
                    </div>
                    <div class="col-md-3">
                        <label>Metode Bayar <span class="text-danger">*</span></label>
                        <select name="metode_bayar" class="form-control" required>
                            <option value="tunai" {{ $transaksiPenjualan->metode_bayar == 'tunai' ? 'selected' : '' }}>Tunai</option>
                            <option value="kartu" {{ $transaksiPenjualan->metode_bayar == 'kartu' ? 'selected' : '' }}>Kartu</option>
                            <option value="transfer" {{ $transaksiPenjualan->metode_bayar == 'transfer' ? 'selected' : '' }}>Transfer</option>
                        </select>
                    </div>
                </div>

                <hr>

                <!-- Item Table -->
                <h5>Item Produk</h5>
                <table class="table table-bordered" id="item-table">
                    <thead class="table-light">
                        <tr>
                            <th>Produk</th>
                            <th>Stok</th>
                            <th>Jumlah</th>
                            <th>Harga Jual</th>
                            <th>Subtotal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transaksiPenjualan->detailPenjualan as $index => $detail)
                            <tr class="item-row">
                                <td>
                                    <select name="items[{{ $index }}][produk_id]" class="form-control produk-select" required>
                                        <option value="">-- Pilih Produk --</option>
                                        @foreach($produks as $p)
                                            <option value="{{ $p->id }}"
                                                    data-harga="{{ $p->harga_jual }}"
                                                    data-stok="{{ $p->stokGudang->sum('jumlah_stok') ?? 0 }}"
                                                    {{ $p->id == $detail->produk_id ? 'selected' : '' }}>
                                                {{ $p->nama_produk }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><span class="stok-text">{{ $p->stokGudang->sum('jumlah_stok') ?? 0 }}</span></td>
                                <td><input type="number" name="items[{{ $index }}][jumlah]" class="form-control jumlah" min="1"
                                           value="{{ $detail->jumlah }}" required></td>
                                <td><input type="number" name="items[{{ $index }}][harga_jual]" class="form-control harga" step="0.01"
                                           value="{{ $detail->harga_satuan }}" readonly></td>
                                <td><input type="text" class="form-control subtotal" readonly
                                           value="{{ number_format($detail->subtotal, 0, ',', '.') }}"></td>
                                <td>
                                    @if($loop->first)
                                        <button type="button" class="btn btn-danger btn-sm" disabled>Hapus</button>
                                    @else
                                        <button type="button" class="btn btn-danger btn-sm remove-row">Hapus</button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" class="text-end"><strong>Subtotal</strong></td>
                            <td><strong id="subtotal-display">Rp {{ number_format($transaksiPenjualan->subtotal, 0, ',', '.') }}</strong></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-end"><strong>Diskon <span id="diskon-persen">0%</span></strong></td>
                            <td><strong id="diskon-display">Rp {{ number_format($transaksiPenjualan->diskon, 0, ',', '.') }}</strong></td>
                            <td></td>
                        </tr>
                        <tr class="table-info">
                            <td colspan="4" class="text-end"><strong>Total Bayar</strong></td>
                            <td>
                                <strong id="total-display">Rp {{ number_format($transaksiPenjualan->total_bayar, 0, ',', '.') }}</strong>
                                <input type="hidden" name="total_bayar" id="total-value" value="{{ $transaksiPenjualan->total_bayar }}">
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>

                <button type="button" class="btn btn-info btn-sm mb-3" id="add-row">+ Tambah Item</button>

                <hr>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('penjualan.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-warning btn-lg">Update Transaksi</button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
let index = {{ $transaksiPenjualan->detailPenjualan->count() }};
let diskonPersen = {{ $transaksiPenjualan->pelanggan?->membership?->diskon_persen ?? 0 }};

// Init diskon
document.getElementById('diskon-persen').textContent = diskonPersen + '%';

// Update diskon saat ganti pelanggan
document.getElementById('pelanggan-select').onchange = function() {
    diskonPersen = this.selectedOptions[0].dataset.diskon || 0;
    document.getElementById('diskon-persen').textContent = diskonPersen + '%';
    hitungTotal();
};

// Tambah baris
document.getElementById('add-row').onclick = function() {
    const table = document.getElementById('item-table').getElementsByTagName('tbody')[0];
    const row = table.insertRow();
    row.className = 'item-row';
    row.innerHTML = `
        <td>
            <select name="items[${index}][produk_id]" class="form-control produk-select" required>
                <option value="">-- Pilih Produk --</option>
                @foreach($produks as $p)
                    <option value="{{ $p->id }}" data-harga="{{ $p->harga_jual }}" data-stok="{{ $p->stokGudang->sum('jumlah_stok') ?? 0 }}">
                        {{ $p->nama_produk }}
                    </option>
                @endforeach
            </select>
        </td>
        <td><span class="stok-text">0</span></td>
        <td><input type="number" name="items[${index}][jumlah]" class="form-control jumlah" min="1" required></td>
        <td><input type="number" name="items[${index}][harga_jual]" class="form-control harga" step="0.01" readonly></td>
        <td><input type="text" class="form-control subtotal" readonly value="0"></td>
        <td><button type="button" class="btn btn-danger btn-sm remove-row">Hapus</button></td>
    `;
    index++;
};

// Event listeners
document.addEventListener('change', function(e) {
    if (e.target.classList.contains('produk-select')) {
        const opt = e.target.selectedOptions[0];
        const row = e.target.closest('tr');
        row.querySelector('.harga').value = opt.dataset.harga;
        row.querySelector('.stok-text').textContent = opt.dataset.stok;
        hitungSubtotal(row);
    }
});

document.addEventListener('input', function(e) {
    if (e.target.classList.contains('jumlah') || e.target.classList.contains('harga')) {
        hitungSubtotal(e.target.closest('tr'));
    }
});

document.addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-row')) {
        e.target.closest('tr').remove();
        hitungTotal();
    }
});

function hitungSubtotal(row) {
    const jumlah = parseFloat(row.querySelector('.jumlah').value) || 0;
    const harga = parseFloat(row.querySelector('.harga').value) || 0;
    const subtotal = jumlah * harga;
    row.querySelector('.subtotal').value = subtotal.toLocaleString('id-ID');
    hitungTotal();
}

function hitungTotal() {
    let subtotal = 0;
    document.querySelectorAll('.subtotal').forEach(el => {
        subtotal += parseFloat(el.value.replace(/[^0-9.-]+/g, '')) || 0;
    });
    const diskon = subtotal * (diskonPersen / 100);
    const total = subtotal - diskon;

    document.getElementById('subtotal-display').textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
    document.getElementById('diskon-display').textContent = 'Rp ' + diskon.toLocaleString('id-ID');
    document.getElementById('total-display').textContent = 'Rp ' + total.toLocaleString('id-ID');
    document.getElementById('total-value').value = total;
}

// Init total
hitungTotal();
</script>
@endpush