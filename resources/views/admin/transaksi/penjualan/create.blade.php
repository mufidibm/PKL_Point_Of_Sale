@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1 class="mb-3">Transaksi Penjualan Baru</h1>

    <form action="{{ route('transaksi.penjualan.store') }}" method="POST" id="form-penjualan">
        @csrf
        <div class="card">
            <div class="card-body">
                <!-- Header -->
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label class="form-label">Pelanggan</label>
                        <select name="pelanggan_id" class="form-control" id="pelanggan-select">
                            <option value="">-- Umum --</option>
                            @foreach($pelanggans as $p)
                                <option value="{{ $p->id }}"
                                        data-diskon="{{ $p->membership?->diskon_persen ?? 0 }}">
                                    {{ $p->nama }} @if($p->membership) ({{ $p->membership->nama_membership }}) @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Karyawan <span class="text-danger">*</span></label>
                        <select name="karyawan_id" class="form-control @error('karyawan_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Karyawan --</option>
                            @foreach($karyawans as $k)
                                <option value="{{ $k->id }}">{{ $k->nama }}</option>
                            @endforeach
                        </select>
                        @error('karyawan_id') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror"
                               value="{{ old('tanggal', date('Y-m-d')) }}" required>
                        @error('tanggal') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Metode Bayar <span class="text-danger">*</span></label>
                        <select name="metode_bayar" class="form-control @error('metode_bayar') is-invalid @enderror" required>
                            <option value="tunai">Tunai</option>
                            <option value="kartu">Kartu</option>
                            <option value="transfer">Transfer</option>
                        </select>
                        @error('metode_bayar') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                </div>

                <hr>

                <!-- Item Table -->
                <h5>Item Produk</h5>
                <table class="table table-bordered" id="item-table">
                    <thead class="table-light">
                        <tr>
                            <th width="30%">Produk</th>
                            <th width="10%">Stok</th>
                            <th width="15%">Jumlah</th>
                            <th width="20%">Harga Jual</th>
                            <th width="20%">Subtotal</th>
                            <th width="10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="item-row">
                            <td>
                                <select name="items[0][produk_id]" class="form-control produk-select" required>
                                    <option value="">-- Pilih Produk --</option>
                                    @foreach($produks as $p)
                                        <option value="{{ $p->id }}"
                                                data-harga="{{ $p->harga_jual }}"
                                                data-stok="{{ $p->stokGudang->sum('jumlah_stok') ?? 0 }}">
                                            {{ $p->nama_produk }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td><span class="stok-text">0</span></td>
                            <td><input type="number" name="items[0][jumlah]" class="form-control jumlah" min="1" required></td>
                            <td><input type="number" name="items[0][harga_jual]" class="form-control harga" step="0.01" readonly></td>
                            <td><input type="text" class="form-control subtotal" readonly value="0"></td>
                            <td><button type="button" class="btn btn-danger btn-sm remove-row">Hapus</button></td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" class="text-end"><strong>Subtotal</strong></td>
                            <td><strong id="subtotal-display">Rp 0</strong></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-end"><strong>Diskon <span id="diskon-persen">0%</span></strong></td>
                            <td><strong id="diskon-display">Rp 0</strong></td>
                            <td></td>
                        </tr>
                        <tr class="table-info">
                            <td colspan="4" class="text-end"><strong>Total Bayar</strong></td>
                            <td>
                                <strong id="total-display">Rp 0</strong>
                                <input type="hidden" name="total_bayar" id="total-value" value="0">
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>

                <button type="button" class="btn btn-info btn-sm mb-3" id="add-row">
                    + Tambah Item
                </button>

                <hr>

                <!-- Actions -->
                <div class="d-flex justify-content-between">
                    <a href="{{ route('transaksi.penjualan.index') }}" class="btn btn-secondary">
                        Batal
                    </a>
                    <button type="submit" class="btn btn-success btn-lg">
                        Simpan & Cetak Struk
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
let index = 1;
let diskonPersen = 0;

// Pilih pelanggan → diskon
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

// Update harga & stok
document.addEventListener('change', function(e) {
    if (e.target.classList.contains('produk-select')) {
        const opt = e.target.selectedOptions[0];
        const row = e.target.closest('tr');
        row.querySelector('.harga').value = opt.dataset.harga;
        row.querySelector('.stok-text').textContent = opt.dataset.stok;
        hitungSubtotal(row);
    }
});

// Hitung subtotal
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
</script>
@endpush