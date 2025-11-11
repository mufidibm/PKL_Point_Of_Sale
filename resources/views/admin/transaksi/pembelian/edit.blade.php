@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1 class="mb-3">Edit Transaksi Pembelian #{{ $transaksi->no_po }}</h1>

    <form action="{{ route('transaksi.pembelian.update', $transaksi->id) }}" method="POST">
        @csrf @method('PUT')
        <div class="card">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label>Supplier <span class="text-danger">*</span></label>
                        <select name="supplier_id" class="form-control" required>
                            <option value="">-- Pilih Supplier --</option>
                            @foreach($suppliers as $s)
                                <option value="{{ $s->id }}" {{ $s->id == $transaksi->supplier_id ? 'selected' : '' }}>
                                    {{ $s->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>Karyawan <span class="text-danger">*</span></label>
                        <select name="karyawan_id" class="form-control" required>
                            <option value="">-- Pilih Karyawan --</option>
                            @foreach($karyawans as $k)
                                <option value="{{ $k->id }}" {{ $k->id == $transaksi->karyawan_id ? 'selected' : '' }}>
                                    {{ $k->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>Tanggal <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal" class="form-control"
                               value="{{ old('tanggal', $transaksi->tanggal->format('Y-m-d')) }}" required>
                    </div>
                </div>

                <hr>

                <h5>Item Produk</h5>
                <table class="table table-bordered" id="item-table">
                    <thead class="table-light">
                        <tr>
                            <th>Produk</th>
                            <th>Jumlah</th>
                            <th>Harga Beli</th>
                            <th>Subtotal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transaksi->detailPembelian as $index => $detail)
                            <tr class="item-row">
                                <td>
                                    <select name="items[{{ $index }}][produk_id]" class="form-control produk-select" required>
                                        <option value="">-- Pilih Produk --</option>
                                        @foreach($produks as $p)
                                            <option value="{{ $p->id }}"
                                                    data-harga="{{ $p->harga_beli ?? 0 }}"
                                                    {{ $p->id == $detail->produk_id ? 'selected' : '' }}>
                                                {{ $p->nama_produk }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><input type="number" name="items[{{ $index }}][jumlah]" class="form-control jumlah" min="1"
                                           value="{{ $detail->jumlah }}" required></td>
                                <td><input type="number" name="items[{{ $index }}][harga_beli]" class="form-control harga" step="0.01"
                                           value="{{ $detail->harga_beli }}" required></td>
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
                            <td colspan="3" class="text-end"><strong>Total Biaya</strong></td>
                            <td>
                                <strong id="total-display">Rp {{ number_format($transaksi->total_biaya, 0, ',', '.') }}</strong>
                                <input type="hidden" name="total_biaya" id="total-value" value="{{ $transaksi->total_biaya }}">
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>

                <button type="button" class="btn btn-info btn-sm mb-3" id="add-row">+ Tambah Item</button>

                <hr>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('transaksi.pembelian.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-warning btn-lg">Update Pembelian</button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
let index = {{ $transaksi->detailPembelian->count() }};

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
                    <option value="{{ $p->id }}" data-harga="{{ $p->harga_beli ?? 0 }}">
                        {{ $p->nama_produk }}
                    </option>
                @endforeach
            </select>
        </td>
        <td><input type="number" name="items[${index}][jumlah]" class="form-control jumlah" min="1" required></td>
        <td><input type="number" name="items[${index}][harga_beli]" class="form-control harga" step="0.01" required></td>
        <td><input type="text" class="form-control subtotal" readonly value="0"></td>
        <td><button type="button" class="btn btn-danger btn-sm remove-row">Hapus</button></td>
    `;
    index++;
};

// Event listeners (sama seperti create)
document.addEventListener('change', function(e) {
    if (e.target.classList.contains('produk-select')) {
        const harga = e.target.selectedOptions[0].dataset.harga;
        e.target.closest('tr').querySelector('.harga').value = harga;
        hitungSubtotal(e.target.closest('tr'));
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
    let total = 0;
    document.querySelectorAll('.subtotal').forEach(el => {
        total += parseFloat(el.value.replace(/[^0-9.-]+/g, '')) || 0;
    });
    document.getElementById('total-display').textContent = 'Rp ' + total.toLocaleString('id-ID');
    document.getElementById('total-value').value = total;
}

hitungTotal();
</script>
@endpush