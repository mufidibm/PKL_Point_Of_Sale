@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h1 class="mb-3">Transaksi Pembelian Baru</h1>

    <form action="{{ route('pembelian.store') }}" method="POST" id="form-pembelian">
        @csrf
        <div class="card">
            <div class="card-body">
                <!-- Header -->
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Supplier <span class="text-danger">*</span></label>
                        <select name="supplier_id" class="form-control @error('supplier_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Supplier --</option>
                            @foreach($suppliers as $s)
                                <option value="{{ $s->id }}">{{ $s->nama }}</option>
                            @endforeach
                        </select>
                        @error('supplier_id') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Karyawan <span class="text-danger">*</span></label>
                        <select name="karyawan_id" class="form-control @error('karyawan_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Karyawan --</option>
                            @foreach($karyawans as $k)
                                <option value="{{ $k->id }}">{{ $k->nama }}</option>
                            @endforeach
                        </select>
                        @error('karyawan_id') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror"
                               value="{{ old('tanggal', date('Y-m-d')) }}" required>
                        @error('tanggal') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                </div>

                <hr>

                <!-- Item Table -->
                <h5>Item Produk</h5>
                <table class="table table-bordered" id="item-table">
                    <thead class="table-light">
                        <tr>
                            <th width="30%">Produk</th>
                            <th width="15%">Jumlah</th>
                            <th width="20%">Harga Beli</th>
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
                                        <option value="{{ $p->id }}" data-harga="{{ $p->harga_beli ?? 0 }}">
                                            {{ $p->nama_produk }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="number" name="items[0][jumlah]" class="form-control jumlah" min="1" required>
                            </td>
                            <td>
                                <input type="number" name="items[0][harga_beli]" class="form-control harga" step="0.01" min="0" required>
                            </td>
                            <td>
                                <input type="text" class="form-control subtotal" readonly value="0">
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm remove-row">Hapus</button>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-end"><strong>Total Biaya</strong></td>
                            <td>
                                <strong id="total-display">Rp 0</strong>
                                <input type="hidden" name="total_biaya" id="total-value" value="0">
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
                    <a href="{{ route('pembelian.index') }}" class="btn btn-secondary">
                        Batal
                    </a>
                    <button type="submit" class="btn btn-success btn-lg">
                        Simpan Pembelian
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
        <td><input type="number" name="items[${index}][harga_beli]" class="form-control harga" step="0.01" min="0" required></td>
        <td><input type="text" class="form-control subtotal" readonly value="0"></td>
        <td><button type="button" class="btn btn-danger btn-sm remove-row">Hapus</button></td>
    `;
    index++;
};

// Hapus baris
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-row')) {
        e.target.closest('tr').remove();
        hitungTotal();
    }
});

// Update harga otomatis
document.addEventListener('change', function(e) {
    if (e.target.classList.contains('produk-select')) {
        const harga = e.target.selectedOptions[0].dataset.harga;
        e.target.closest('tr').querySelector('.harga').value = harga;
        hitungSubtotal(e.target.closest('tr'));
    }
});

// Hitung subtotal per baris
document.addEventListener('input', function(e) {
    if (e.target.classList.contains('jumlah') || e.target.classList.contains('harga')) {
        hitungSubtotal(e.target.closest('tr'));
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
</script>
@endpush