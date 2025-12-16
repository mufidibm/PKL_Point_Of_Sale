@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="row">
        <!-- Area Produk & Scan -->
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-shopping-cart me-2"></i>Kasir POS</h5>
                </div>
                <div class="card-body">
                    <!-- Scan Barcode -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">Scan Barcode / Cari Produk</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-white">
                                <i class="fas fa-barcode text-primary"></i>
                            </span>
                            <input type="text" id="barcodeInput" class="form-control" 
                                   placeholder="Scan barcode atau ketik nama produk..." autofocus>
                            <button class="btn btn-primary px-4" onclick="cariProduk()">
                                <i class="fas fa-search me-1"></i> Cari
                            </button>
                        </div>
                        <small class="text-muted"><i class="fas fa-info-circle"></i> Tekan Enter setelah scan barcode</small>

                        <div id="hasilPencarian" class="list-group position-absolute" style="z-index: 1000; display: none; max-height: 300px; overflow-y: auto; width: calc(100% - 2rem);"></div>
                    </div>

                    <!-- Tabel Keranjang -->
                    <div class="table-responsive">
                        <table class="table table-hover align-middle" id="tabelKeranjang">
                            <thead class="table-dark">
                                <tr>
                                    <th width="5%" class="text-center">No</th>
                                    <th width="35%">Produk</th>
                                    <th width="15%" class="text-end">Harga</th>
                                    <th width="20%" class="text-center">Qty</th>
                                    <th width="15%" class="text-end">Subtotal</th>
                                    <th width="10%" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="keranjangBody">
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-5">
                                        <i class="fas fa-shopping-basket fa-4x mb-3 d-block"></i>
                                        <h5>Keranjang masih kosong</h5>
                                        <p class="mb-0">Scan barcode untuk menambah produk</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Area Pembayaran -->
        <div class="col-lg-4">
            <div class="card shadow-sm sticky-top" style="top: 20px;">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-calculator me-2"></i>Pembayaran</h5>
                </div>
                <div class="card-body">
                    <!-- Membership -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="fas fa-id-card me-1"></i> Membership (Opsional)
                        </label>
                        <div class="input-group">
                            <input type="text" id="membershipInput" class="form-control" 
                                   placeholder="Cari nama membership...">
                            <button class="btn btn-outline-secondary" onclick="cariMembership()">
                                <i class="fas fa-search"></i>
                            </button>
                            <button class="btn btn-outline-danger" onclick="hapusMembership()" title="Hapus membership">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                        <small class="text-muted d-block mt-1" id="memberInfo"></small>
                        <div id="hasilMembership" class="list-group position-absolute" style="z-index: 1000; display: none; max-height: 200px; overflow-y: auto; width: calc(100% - 2rem);"></div>
                    </div>

                    <hr>

                    <!-- Ringkasan Belanja -->
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Subtotal:</span>
                            <strong id="subtotalText">Rp 0</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Diskon Member:</span>
                            <strong class="text-danger" id="diskonText">Rp 0</strong>
                        </div>
                        <hr class="my-2">
                        <div class="d-flex justify-content-between mb-3">
                            <h5 class="mb-0">Total Bayar:</h5>
                            <h4 class="mb-0 text-primary fw-bold" id="totalText">Rp 0</h4>
                        </div>
                    </div>

                    <!-- Input Uang Bayar -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="fas fa-money-bill-wave me-1"></i> Uang Dibayar
                        </label>
                        <input type="text" id="uangBayar" class="form-control form-control-lg text-end fw-bold" 
                               placeholder="0" oninput="formatInputRupiah(this); hitungKembalian()">
                    </div>

                    <!-- Kembalian -->
                    <div class="alert alert-info mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-hand-holding-usd me-1"></i> Kembalian:</span>
                            <strong class="fs-5" id="kembalianText">Rp 0</strong>
                        </div>
                    </div>

                    <!-- Quick Amount Buttons -->
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted small">Nominal Cepat:</label>
                        <div class="d-grid gap-2">
                            <div class="btn-group" role="group">
                                <button class="btn btn-outline-secondary btn-sm" onclick="setNominal(50000)">50rb</button>
                                <button class="btn btn-outline-secondary btn-sm" onclick="setNominal(100000)">100rb</button>
                                <button class="btn btn-outline-secondary btn-sm" onclick="setNominal(200000)">200rb</button>
                                <button class="btn btn-outline-secondary btn-sm" onclick="setNominal(500000)">500rb</button>
                            </div>
                            <button class="btn btn-outline-primary btn-sm" onclick="setPasExact()">
                                <i class="fas fa-equals me-1"></i> Uang Pas
                            </button>
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="d-grid gap-2">
                        <button class="btn btn-success btn-lg fw-bold" onclick="prosesTransaksi()">
                            <i class="fas fa-check-circle me-2"></i> PROSES BAYAR
                        </button>
                        <button class="btn btn-outline-danger" onclick="resetKeranjang()">
                            <i class="fas fa-trash me-2"></i> Reset Keranjang
                        </button>
                    </div>

                    <!-- Info -->
                    <div class="text-center mt-3">
                        <small class="text-muted">
                            <i class="fas fa-keyboard me-1"></i> F8: Fokus Scan | F9: Reset
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Loading -->
<div class="modal fade" id="loadingModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-body text-center py-4">
                <div class="spinner-border text-primary mb-3" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mb-0">Memproses transaksi...</p>
            </div>
        </div>
    </div>
</div>

<style>
#barcodeInput {
    font-size: 1.1rem;
    border: 2px solid #e0e0e0;
}

#barcodeInput:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

.table-hover tbody tr:hover {
    background-color: #f8f9fa;
    cursor: pointer;
}

.input-group-lg .form-control {
    height: calc(3rem + 2px);
}

.qty-input {
    max-width: 70px;
    text-align: center;
    font-weight: bold;
}

.btn-qty {
    width: 35px;
}

@media print {
    body * {
        visibility: hidden;
    }
    #strukArea, #strukArea * {
        visibility: visible;
    }
    #strukArea {
        position: absolute;
        left: 0;
        top: 0;
    }
}
</style>

<script>
let keranjang = [];
let membershipData = null;

// Event listener untuk Enter pada barcode
document.getElementById('barcodeInput').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        cariProduk();
    }
});

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // F8 - Focus ke barcode
    if (e.key === 'F8') {
        e.preventDefault();
        document.getElementById('barcodeInput').focus();
    }
    // F9 - Reset keranjang
    if (e.key === 'F9') {
        e.preventDefault();
        if (confirm('Reset keranjang?')) {
            resetKeranjang();
        }
    }
});

// Cari produk
// Cari produk dengan dropdown
function cariProduk() {
    const keyword = document.getElementById('barcodeInput').value.trim();
    if (!keyword) {
        document.getElementById('hasilPencarian').style.display = 'none';
        return;
    }

    fetch(`/pos/cari-produk?keyword=${encodeURIComponent(keyword)}`)
        .then(response => response.json())
        .then(data => {
            const hasil = document.getElementById('hasilPencarian');
            
            if (data.success) {
                // Cek apakah data adalah array (multiple results)
                if (Array.isArray(data.data)) {
                    // Tampilkan dropdown
                    let html = '';
                    data.data.forEach(produk => {
                        html += `
                            <a href="#" class="list-group-item list-group-item-action" onclick='pilihProduk(${JSON.stringify(produk)}); return false;'>
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <strong>${produk.nama_produk}</strong><br>
                                        <small class="text-muted">Stok: ${produk.stok}</small>
                                    </div>
                                    <div class="text-end">
                                        <strong>Rp ${formatRupiah(produk.harga_jual)}</strong>
                                    </div>
                                </div>
                            </a>
                        `;
                    });
                    hasil.innerHTML = html;
                    hasil.style.display = 'block';
                } else {
                    // Single result - langsung tambah
                    tambahKeKeranjang(data.data);
                    document.getElementById('barcodeInput').value = '';
                    hasil.style.display = 'none';
                }
            } else {
                alert(data.message);
                hasil.style.display = 'none';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat mencari produk');
        });
}

// Pilih produk dari dropdown
function pilihProduk(produk) {
    tambahKeKeranjang(produk);
    document.getElementById('barcodeInput').value = '';
    document.getElementById('hasilPencarian').style.display = 'none';
    document.getElementById('barcodeInput').focus();
}

// Tambah ke keranjang
function tambahKeKeranjang(produk) {
    const index = keranjang.findIndex(item => item.id === produk.id);
    
    if (index !== -1) {
        // Cek stok
        if (keranjang[index].qty + 1 > produk.stok) {
            alert(`Stok tidak cukup! Stok tersedia: ${produk.stok}`);
            return;
        }
        keranjang[index].qty++;
    } else {
        if (produk.stok < 1) {
            alert('Stok produk habis!');
            return;
        }
        keranjang.push({
            id: produk.id,
            nama: produk.nama_produk,
            harga: produk.harga_jual,
            qty: 1,
            diskon: produk.diskon || 0,
            stok: produk.stok
        });
    }
    
    renderKeranjang();
    hitungTotal();
}

// Render keranjang
function renderKeranjang() {
    const tbody = document.getElementById('keranjangBody');
    
    if (keranjang.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="6" class="text-center text-muted py-5">
                    <i class="fas fa-shopping-basket fa-4x mb-3 d-block"></i>
                    <h5>Keranjang masih kosong</h5>
                    <p class="mb-0">Scan barcode untuk menambah produk</p>
                </td>
            </tr>
        `;
        return;
    }

    let html = '';
    keranjang.forEach((item, index) => {
        const subtotal = item.harga * item.qty;
        html += `
            <tr>
                <td class="text-center">${index + 1}</td>
                <td>
                    <strong>${item.nama}</strong>
                    <br><small class="text-muted">Stok: ${item.stok}</small>
                </td>
                <td class="text-end">Rp ${formatRupiah(item.harga)}</td>
                <td>
                    <div class="input-group input-group-sm justify-content-center">
                        <button class="btn btn-outline-secondary btn-qty" onclick="ubahQty(${index}, -1)">
                            <i class="fas fa-minus"></i>
                        </button>
                        <input type="number" class="form-control qty-input" value="${item.qty}" 
                               onchange="setQty(${index}, this.value)" min="1" max="${item.stok}">
                        <button class="btn btn-outline-secondary btn-qty" onclick="ubahQty(${index}, 1)">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </td>
                <td class="text-end fw-bold">Rp ${formatRupiah(subtotal)}</td>
                <td class="text-center">
                    <button class="btn btn-sm btn-danger" onclick="hapusItem(${index})" title="Hapus">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
    });
    
    tbody.innerHTML = html;
}

// Ubah qty
function ubahQty(index, delta) {
    const newQty = keranjang[index].qty + delta;
    
    if (newQty <= 0) {
        if (confirm('Hapus produk dari keranjang?')) {
            keranjang.splice(index, 1);
        }
    } else if (newQty > keranjang[index].stok) {
        alert(`Stok tidak cukup! Stok tersedia: ${keranjang[index].stok}`);
        return;
    } else {
        keranjang[index].qty = newQty;
    }
    
    renderKeranjang();
    hitungTotal();
}

// Set qty manual
function setQty(index, value) {
    const qty = parseInt(value);
    if (qty > 0 && qty <= keranjang[index].stok) {
        keranjang[index].qty = qty;
    } else if (qty > keranjang[index].stok) {
        alert(`Stok tidak cukup! Stok tersedia: ${keranjang[index].stok}`);
        keranjang[index].qty = keranjang[index].stok;
    } else {
        keranjang.splice(index, 1);
    }
    renderKeranjang();
    hitungTotal();
}

// Hapus item
function hapusItem(index) {
    if (confirm('Hapus produk dari keranjang?')) {
        keranjang.splice(index, 1);
        renderKeranjang();
        hitungTotal();
    }
}

//Cari membership
function cariMembership() {
    const keyword = document.getElementById('membershipInput').value.trim();
    if (!keyword) {
        document.getElementById('hasilMembership').style.display = 'none';
        return;
    }

    fetch(`/pos/cari-membership?keyword=${encodeURIComponent(keyword)}`)
        .then(response => response.json())
        .then(data => {
            const hasil = document.getElementById('hasilMembership');
            
            if (data.success) {
                if (Array.isArray(data.data)) {
                    // Tampilkan dropdown
                    let html = '';
                    data.data.forEach(member => {
                        html += `
                            <a href="#" class="list-group-item list-group-item-action" onclick='pilihMembership(${JSON.stringify(member)}); return false;'>
                                <strong>${member.nama}</strong><br>
                                <small class="text-success">Diskon: ${member.diskon_persen}%</small>
                            </a>
                        `;
                    });
                    hasil.innerHTML = html;
                    hasil.style.display = 'block';
                } else {
                    // Single result
                    pilihMembership(data.data);
                    hasil.style.display = 'none';
                }
            } else {
                alert(data.message);
                hasil.style.display = 'none';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat mencari membership');
        });
}

// Pilih membership dari dropdown
function pilihMembership(member) {
    membershipData = member;
    document.getElementById('membershipInput').value = member.nama;
    document.getElementById('memberInfo').innerHTML = 
        `<i class="fas fa-check-circle text-success"></i> <strong>${member.nama}</strong> (Diskon ${member.diskon_persen}%)`;
    document.getElementById('hasilMembership').style.display = 'none';
    hitungTotal();
}

// Hapus membership
function hapusMembership() {
    membershipData = null;
    document.getElementById('membershipInput').value = '';
    document.getElementById('memberInfo').innerHTML = '';
    hitungTotal();
}

// Hitung total
function hitungTotal() {
    let subtotal = 0;
    keranjang.forEach(item => {
        subtotal += item.harga * item.qty;
    });

    let diskon = 0;
    if (membershipData && membershipData.diskon_persen) {
        diskon = subtotal * (membershipData.diskon_persen / 100);
    }

    const total = subtotal - diskon;

    document.getElementById('subtotalText').textContent = 'Rp ' + formatRupiah(subtotal);
    document.getElementById('diskonText').textContent = 'Rp ' + formatRupiah(diskon);
    document.getElementById('totalText').textContent = 'Rp ' + formatRupiah(total);

    hitungKembalian();
}

// Format input rupiah
function formatInputRupiah(input) {
    let value = input.value.replace(/[^0-9]/g, '');
    input.value = value;
}

// Set nominal cepat
function setNominal(nominal) {
    document.getElementById('uangBayar').value = nominal;
    hitungKembalian();
}

// Set uang pas
function setPasExact() {
    const total = parseFloat(document.getElementById('totalText').textContent.replace(/[^0-9]/g, ''));
    document.getElementById('uangBayar').value = total;
    hitungKembalian();
}

// Hitung kembalian
function hitungKembalian() {
    const total = parseFloat(document.getElementById('totalText').textContent.replace(/[^0-9]/g, ''));
    const bayar = parseFloat(document.getElementById('uangBayar').value) || 0;
    const kembalian = bayar - total;
    
    const kembalianEl = document.getElementById('kembalianText');
    kembalianEl.textContent = 'Rp ' + formatRupiah(Math.max(0, kembalian));
    
    if (kembalian < 0) {
        kembalianEl.classList.add('text-danger');
        kembalianEl.classList.remove('text-success');
    } else {
        kembalianEl.classList.remove('text-danger');
        kembalianEl.classList.add('text-success');
    }
}

// Proses transaksi
// FUNGSI PROSES TRANSAKSI - VERSI SEDERHANA TANPA MODAL
function prosesTransaksi() {
    if (keranjang.length === 0) {
        alert('Keranjang masih kosong!');
        return;
    }

    const total = parseFloat(document.getElementById('totalText').textContent.replace(/[^0-9]/g, ''));
    const bayar = parseFloat(document.getElementById('uangBayar').value) || 0;

    if (bayar < total) {
        alert('Uang yang dibayar kurang!');
        document.getElementById('uangBayar').focus();
        return;
    }

    const subtotal = parseFloat(document.getElementById('subtotalText').textContent.replace(/[^0-9]/g, ''));
    const diskon = parseFloat(document.getElementById('diskonText').textContent.replace(/[^0-9]/g, ''));

    // Disable tombol proses agar tidak double click
    const btnProses = event.target;
    btnProses.disabled = true;
    btnProses.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Memproses...';

    fetch('/pos/proses', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            items: keranjang.map(item => ({
                produk_id: item.id,
                qty: item.qty,
                harga: item.harga
            })),
            membership_id: membershipData ? membershipData.id : null,
            subtotal: subtotal,
            diskon: diskon,
            total_bayar: total,
            uang_dibayar: bayar
        })
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => {
                throw new Error(err.message || 'Network response was not ok');
            });
        }
        return response.json();
    })
  .then(data => {
    btnProses.disabled = false;
    btnProses.innerHTML = '<i class="fas fa-check-circle me-2"></i> PROSES BAYAR';

    if (data.success) {
        // Langsung buka struk
        window.open(`/pos/cetak-struk/${data.data.transaksi_id}`, '_blank');

        // Reset kasir
        resetKeranjang();
    } else {
        alert('❌ Transaksi gagal: ' + data.message);
    }
})
    .catch(error => {
        // Enable tombol kembali
        btnProses.disabled = false;
        btnProses.innerHTML = '<i class="fas fa-check-circle me-2"></i> PROSES BAYAR';
        
        console.error('Error:', error);
        alert('Terjadi kesalahan: ' + error.message);
    });
}

// Reset keranjang
function resetKeranjang() {
    keranjang = [];
    membershipData = null;
    document.getElementById('barcodeInput').value = '';
    document.getElementById('membershipInput').value = '';
    document.getElementById('memberInfo').innerHTML = '';
    document.getElementById('uangBayar').value = '';
    renderKeranjang();
    hitungTotal();
    document.getElementById('barcodeInput').focus();
}

// Format rupiah
function formatRupiah(angka) {
    return new Intl.NumberFormat('id-ID').format(angka);
}

// Auto focus pada load
window.onload = function() {
    document.getElementById('barcodeInput').focus();
};
</script>
@endsection