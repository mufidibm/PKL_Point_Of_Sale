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
                                <span class="input-group-text bg-white" style="cursor: pointer;" onclick="bukaModalScanner()">
                                    <i class="fas fa-barcode text-primary"></i>
                                </span>
                                <input type="text"
                                       id="barcodeInput"
                                       class="form-control"
                                       placeholder="Scan barcode atau ketik nama produk..."
                                       autofocus>
                                <button class="btn btn-primary px-4"
                                        onclick="cariProduk()">
                                    <i class="fas fa-search me-1"></i> Cari
                                </button>
                            </div>
                            <small class="text-muted"><i class="fas fa-info-circle"></i> Klik icon barcode untuk scan dengan kamera atau tekan Enter untuk cari</small>

                            <div id="hasilPencarian"
                                 class="list-group position-absolute"
                                 style="z-index: 1000; display: none; max-height: 300px; overflow-y: auto; width: calc(100% - 2rem);">
                            </div>
                        </div>

                        <!-- Tabel Keranjang -->
                        <div class="table-responsive">
                            <table class="table table-hover align-middle"
                                   id="tabelKeranjang">
                                <thead class="table-dark">
                                    <tr>
                                        <th width="5%"
                                            class="text-center">No</th>
                                        <th width="35%">Produk</th>
                                        <th width="15%"
                                            class="text-end">Harga</th>
                                        <th width="20%"
                                            class="text-center">Qty</th>
                                        <th width="15%"
                                            class="text-end">Subtotal</th>
                                        <th width="10%"
                                            class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="keranjangBody">
                                    <tr>
                                        <td colspan="6"
                                            class="text-center text-muted py-5">
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
                <div class="card shadow-sm sticky-top"
                     style="top: 20px;">
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
                                <input type="text"
                                       id="membershipInput"
                                       class="form-control"
                                       placeholder="Cari nama membership...">
                                <button class="btn btn-outline-secondary"
                                        onclick="cariMembership()">
                                    <i class="fas fa-search"></i>
                                </button>
                                <button class="btn btn-outline-danger"
                                        onclick="hapusMembership()"
                                        title="Hapus membership">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                            <small class="text-muted d-block mt-1"
                                   id="memberInfo"></small>
                            <div id="hasilMembership"
                                 class="list-group position-absolute"
                                 style="z-index: 1000; display: none; max-height: 200px; overflow-y: auto; width: calc(100% - 2rem);">
                            </div>
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
                                <strong class="text-danger"
                                        id="diskonText">Rp 0</strong>
                            </div>
                            <hr class="my-2">
                            <div class="d-flex justify-content-between mb-3">
                                <h5 class="mb-0">Total Bayar:</h5>
                                <h4 class="mb-0 text-primary fw-bold"
                                    id="totalText">Rp 0</h4>
                            </div>
                        </div>

                        <!-- Input Uang Bayar -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-money-bill-wave me-1"></i> Uang Dibayar
                            </label>
                            <input type="text"
                                   id="uangBayar"
                                   class="form-control form-control-lg text-end fw-bold"
                                   placeholder="0"
                                   oninput="formatInputRupiah(this); hitungKembalian()">
                        </div>

                        <!-- Kembalian -->
                        <div class="alert alert-info mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-hand-holding-usd me-1"></i> Kembalian:</span>
                                <strong class="fs-5"
                                        id="kembalianText">Rp 0</strong>
                            </div>
                        </div>

                        <!-- Quick Amount Buttons -->
                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted small">Nominal Cepat:</label>
                            <div class="d-grid gap-2">
                                <div class="btn-group"
                                     role="group">
                                    <button class="btn btn-outline-secondary btn-sm"
                                            onclick="setNominal(50000)">50rb</button>
                                    <button class="btn btn-outline-secondary btn-sm"
                                            onclick="setNominal(100000)">100rb</button>
                                    <button class="btn btn-outline-secondary btn-sm"
                                            onclick="setNominal(200000)">200rb</button>
                                    <button class="btn btn-outline-secondary btn-sm"
                                            onclick="setNominal(500000)">500rb</button>
                                </div>
                                <button class="btn btn-outline-primary btn-sm"
                                        onclick="setPasExact()">
                                    <i class="fas fa-equals me-1"></i> Uang Pas
                                </button>
                            </div>
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="d-grid gap-2">
                            <button class="btn btn-success btn-lg fw-bold"
                                    onclick="prosesTransaksi()">
                                <i class="fas fa-check-circle me-2"></i> PROSES BAYAR
                            </button>
                            <button class="btn btn-outline-danger"
                                    onclick="resetKeranjang()">
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

    <!-- Modal Barcode Scanner -->
    <div class="modal fade" id="modalScanner" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-camera me-2"></i>Scan Barcode Produk
                    </h5>
                    <button type="button" class="btn-close btn-close-white" onclick="tutupModalScanner()"></button>
                </div>
                <div class="modal-body">
                    <!-- Pilih Kamera -->
                    <div class="mb-3" id="pilihanKamera" style="display: none;">
                        <label class="form-label fw-bold">Pilih Kamera:</label>
                        <select class="form-select" id="selectKamera" onchange="gantiKamera()">
                            <option value="">Memuat daftar kamera...</option>
                        </select>
                    </div>

                    <!-- Video Preview -->
                    <div class="text-center mb-3">
                        <video id="videoPreview" width="100%" style="max-height: 400px; border-radius: 8px; background: #000;"></video>
                        <div id="scannerLoading" class="mt-3">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2">Memuat kamera...</p>
                        </div>
                    </div>

                    <!-- Status Scan -->
                    <div id="statusScan" class="alert alert-info text-center" style="display: none;">
                        <i class="fas fa-spinner fa-spin me-2"></i>
                        <span id="statusText">Siap untuk scan...</span>
                    </div>

                    <!-- Input Manual Barcode -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Atau Masukkan Barcode Manual:</label>
                        <div class="input-group">
                            <input type="text" 
                                   id="barcodeManualInput" 
                                   class="form-control" 
                                   placeholder="Ketik kode barcode..."
                                   onkeypress="if(event.key==='Enter') cariProdukDariBarcode()">
                            <button class="btn btn-primary" onclick="cariProdukDariBarcode()">
                                <i class="fas fa-search me-1"></i> Cari
                            </button>
                        </div>
                    </div>

                    <!-- Hasil Scan Terakhir -->
                    <div id="hasilScanTerakhir" class="alert alert-success" style="display: none;">
                        <i class="fas fa-check-circle me-2"></i>
                        <strong>Berhasil!</strong> Produk ditambahkan ke keranjang
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="tutupModalScanner()">
                        <i class="fas fa-times me-1"></i> Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Loading -->
    <div class="modal fade"
         id="loadingModal"
         data-bs-backdrop="static"
         data-bs-keyboard="false"
         tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-body text-center py-4">
                    <div class="spinner-border text-primary mb-3"
                         role="status">
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

        #videoPreview {
            display: none;
        }

        #videoPreview.active {
            display: block;
        }

        @media print {
            body * {
                visibility: hidden;
            }

            #strukArea,
            #strukArea * {
                visibility: visible;
            }

            #strukArea {
                position: absolute;
                left: 0;
                top: 0;
            }
        }
    </style>

    <!-- Library Barcode Scanner -->
    <script src="https://unpkg.com/@zxing/library@latest"></script>

    <script>
        let keranjang = [];
        let membershipData = null;
        let searchTimeout = null;
        let codeReader = null;
        let scannerAktif = false;
        let lastScanTime = 0;
        const SCAN_COOLDOWN = 2000; // 2 detik cooldown

        // Event listener untuk Enter pada barcode
        document.getElementById('barcodeInput').addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                cariProduk();
            }
        });

        // ===== REALTIME SEARCH PRODUK =====
        document.getElementById('barcodeInput').addEventListener('input', function (e) {
            clearTimeout(searchTimeout);
            const keyword = this.value.trim();

            if (keyword.length < 2) {
                document.getElementById('hasilPencarian').style.display = 'none';
                return;
            }

            searchTimeout = setTimeout(() => {
                cariProdukRealtime(keyword);
            }, 300);
        });

        // Hide dropdown saat klik di luar
        document.addEventListener('click', function (e) {
            if (!e.target.closest('#barcodeInput') && !e.target.closest('#hasilPencarian')) {
                document.getElementById('hasilPencarian').style.display = 'none';
            }
            if (!e.target.closest('#membershipInput') && !e.target.closest('#hasilMembership')) {
                document.getElementById('hasilMembership').style.display = 'none';
            }
        });

        // ===== REALTIME SEARCH MEMBERSHIP =====
        document.getElementById('membershipInput').addEventListener('input', function (e) {
            clearTimeout(searchTimeout);
            const keyword = this.value.trim();

            if (keyword.length < 2) {
                document.getElementById('hasilMembership').style.display = 'none';
                return;
            }

            searchTimeout = setTimeout(() => {
                cariMembershipRealtime(keyword);
            }, 300);
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', function (e) {
            if (e.key === 'F8') {
                e.preventDefault();
                document.getElementById('barcodeInput').focus();
            }
            if (e.key === 'F9') {
                e.preventDefault();
                if (confirm('Reset keranjang?')) {
                    resetKeranjang();
                }
            }
            if (e.key === 'Escape') {
                document.getElementById('hasilPencarian').style.display = 'none';
                document.getElementById('hasilMembership').style.display = 'none';
                if (scannerAktif) {
                    tutupModalScanner();
                }
            }
        });

        // ===== BARCODE SCANNER FUNCTIONS =====
        function bukaModalScanner() {
            const modal = new bootstrap.Modal(document.getElementById('modalScanner'));
            modal.show();
            
            setTimeout(() => {
                initScanner();
            }, 500);
        }

        function tutupModalScanner() {
            stopScanner();
            const modal = bootstrap.Modal.getInstance(document.getElementById('modalScanner'));
            if (modal) {
                modal.hide();
            }
            document.getElementById('barcodeManualInput').value = '';
            document.getElementById('hasilScanTerakhir').style.display = 'none';
        }

        async function initScanner() {
            try {
                document.getElementById('scannerLoading').style.display = 'block';
                document.getElementById('videoPreview').classList.remove('active');
                
                codeReader = new ZXing.BrowserMultiFormatReader();
                
                videoInputDevices = await codeReader.listVideoInputDevices();
                
                if (videoInputDevices.length === 0) {
                    throw new Error('Tidak ada kamera yang terdeteksi');
                }

                // Tampilkan dropdown pilihan kamera jika ada lebih dari 1
                if (videoInputDevices.length > 1) {
                    const selectKamera = document.getElementById('selectKamera');
                    selectKamera.innerHTML = '';
                    
                    videoInputDevices.forEach((device, index) => {
                        const option = document.createElement('option');
                        option.value = device.deviceId;
                        option.text = device.label || `Kamera ${index + 1}`;
                        selectKamera.appendChild(option);
                    });
                    
                    document.getElementById('pilihanKamera').style.display = 'block';
                }

                selectedDeviceId = videoInputDevices[0].deviceId;
                
                document.getElementById('scannerLoading').style.display = 'none';
                document.getElementById('videoPreview').classList.add('active');
                document.getElementById('statusScan').style.display = 'block';
                document.getElementById('statusText').textContent = 'Arahkan barcode ke kamera...';
                
                scannerAktif = true;
                
                codeReader.decodeFromVideoDevice(selectedDeviceId, 'videoPreview', (result, err) => {
                    if (result) {
                        handleBarcodeScan(result.text);
                    }
                });
                
            } catch (err) {
                console.error('Error scanner:', err);
                document.getElementById('scannerLoading').innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Gagal mengakses kamera: ${err.message}
                        <br><small>Silakan gunakan input manual di bawah</small>
                    </div>
                `;
            }
        }

        function gantiKamera() {
            const selectKamera = document.getElementById('selectKamera');
            selectedDeviceId = selectKamera.value;
            
            if (codeReader && selectedDeviceId) {
                // Stop scanner yang lama
                codeReader.reset();
                
                // Start dengan kamera baru
                scannerAktif = true;
                codeReader.decodeFromVideoDevice(selectedDeviceId, 'videoPreview', (result, err) => {
                    if (result) {
                        handleBarcodeScan(result.text);
                    }
                });
            }
        }

        function stopScanner() {
            if (codeReader) {
                codeReader.reset();
                scannerAktif = false;
            }
            document.getElementById('videoPreview').classList.remove('active');
            document.getElementById('scannerLoading').style.display = 'block';
            document.getElementById('statusScan').style.display = 'none';
            document.getElementById('pilihanKamera').style.display = 'none';
        }

        function handleBarcodeScan(barcode) {
            const now = Date.now();
            
            // Cek cooldown
            if (now - lastScanTime < SCAN_COOLDOWN) {
                return;
            }
            
            lastScanTime = now;
            
            document.getElementById('statusText').textContent = 'Memproses barcode: ' + barcode;
            
            // Cari produk berdasarkan barcode
            fetch(`/pos/cari-produk?keyword=${encodeURIComponent(barcode)}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const produk = Array.isArray(data.data) ? data.data[0] : data.data;
                        
                        // Set qty ke 1
                        const produkDenganQty1 = {...produk, forceQty: 1};
                        tambahKeKeranjang(produkDenganQty1);
                        
                        // Tampilkan notifikasi sukses
                        document.getElementById('hasilScanTerakhir').style.display = 'block';
                        document.getElementById('hasilScanTerakhir').innerHTML = `
                            <i class="fas fa-check-circle me-2"></i>
                            <strong>Berhasil!</strong> ${produk.nama_produk} ditambahkan (Qty: 1)
                        `;
                        
                        // Auto hide notifikasi setelah 2 detik
                        setTimeout(() => {
                            document.getElementById('hasilScanTerakhir').style.display = 'none';
                        }, 2000);
                        
                        document.getElementById('statusText').textContent = 'Siap scan barcode berikutnya...';
                    } else {
                        document.getElementById('statusText').textContent = 'Produk tidak ditemukan!';
                        setTimeout(() => {
                            document.getElementById('statusText').textContent = 'Arahkan barcode ke kamera...';
                        }, 2000);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('statusText').textContent = 'Error mencari produk!';
                });
        }

        function cariProdukDariBarcode() {
            const barcode = document.getElementById('barcodeManualInput').value.trim();
            if (!barcode) {
                alert('Masukkan kode barcode terlebih dahulu');
                return;
            }
            
            handleBarcodeScan(barcode);
            document.getElementById('barcodeManualInput').value = '';
        }

        // Cari produk realtime
        function cariProdukRealtime(keyword) {
            fetch(`/pos/cari-produk?keyword=${encodeURIComponent(keyword)}`)
                .then(response => response.json())
                .then(data => {
                    const hasil = document.getElementById('hasilPencarian');

                    if (data.success) {
                        const dataArray = Array.isArray(data.data) ? data.data : [data.data];
                        let html = '';

                        dataArray.forEach(produk => {
                            const stokClass = produk.stok > 0 ? 'text-success' : 'text-danger';
                            html += `
                            <a href="#" class="list-group-item list-group-item-action" onclick='pilihProduk(${JSON.stringify(produk)}); return false;'>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>${produk.nama_produk}</strong>
                                        ${produk.barcode ? `<br><small class="text-muted">Barcode: ${produk.barcode}</small>` : ''}
                                        <br><small class="${stokClass}"><i class="fas fa-box"></i> Stok: ${produk.stok}</small>
                                    </div>
                                    <div class="text-end">
                                        <strong class="text-primary">Rp ${formatRupiah(produk.harga_jual)}</strong>
                                    </div>
                                </div>
                            </a>
                        `;
                        });

                        hasil.innerHTML = html;
                        hasil.style.display = 'block';
                    } else {
                        hasil.innerHTML = `
                        <div class="list-group-item text-center text-muted">
                            <i class="fas fa-search"></i> ${data.message}
                        </div>
                    `;
                        hasil.style.display = 'block';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('hasilPencarian').style.display = 'none';
                });
        }

        // Cari membership realtime
        function cariMembershipRealtime(keyword) {
            fetch(`/pos/cari-membership?keyword=${encodeURIComponent(keyword)}`)
                .then(response => response.json())
                .then(data => {
                    const hasil = document.getElementById('hasilMembership');

                    if (data.success) {
                        const dataArray = Array.isArray(data.data) ? data.data : [data.data];
                        let html = '';

                        dataArray.forEach(member => {
                            html += `
                            <a href="#" class="list-group-item list-group-item-action" onclick='pilihMembership(${JSON.stringify(member)}); return false;'>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>${member.nama}</strong>
                                    </div>
                                    <div>
                                        <span class="badge bg-success">Diskon ${member.diskon_persen}%</span>
                                    </div>
                                </div>
                            </a>
                        `;
                        });

                        hasil.innerHTML = html;
                        hasil.style.display = 'block';
                    } else {
                        hasil.innerHTML = `
                        <div class="list-group-item text-center text-muted">
                            <i class="fas fa-search"></i> ${data.message}
                        </div>
                    `;
                        hasil.style.display = 'block';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('hasilMembership').style.display = 'none';
                });
        }

        function cariProduk() {
            const keyword = document.getElementById('barcodeInput').value.trim();
            if (!keyword) {
                document.getElementById('hasilPencarian').style.display = 'none';
                return;
            }
            cariProdukRealtime(keyword);
        }

        function cariMembership() {
            const keyword = document.getElementById('membershipInput').value.trim();
            if (!keyword) {
                document.getElementById('hasilMembership').style.display = 'none';
                return;
            }
            cariMembershipRealtime(keyword);
        }

        function pilihProduk(produk) {
            tambahKeKeranjang(produk);
            document.getElementById('barcodeInput').value = '';
            document.getElementById('hasilPencarian').style.display = 'none';
            document.getElementById('barcodeInput').focus();
        }

        function pilihMembership(member) {
            membershipData = member;
            document.getElementById('membershipInput').value = member.nama;
            document.getElementById('memberInfo').innerHTML =
                `<i class="fas fa-check-circle text-success"></i> <strong>${member.nama}</strong> (Diskon ${member.diskon_persen}%)`;
            document.getElementById('hasilMembership').style.display = 'none';
            hitungTotal();
        }

        function tambahKeKeranjang(produk) {
            const index = keranjang.findIndex(item => item.id === produk.id);

            if (index !== -1) {
                // Jika dari scanner (forceQty), tambah 1
                if (produk.forceQty) {
                    if (keranjang[index].qty + 1 > produk.stok) {
                        alert(`Stok tidak cukup! Stok tersedia: ${produk.stok}`);
                        return;
                    }
                    keranjang[index].qty++;
                } else {
                    // Jika dari manual, tambah 1
                    if (keranjang[index].qty + 1 > produk.stok) {
                        alert(`Stok tidak cukup! Stok tersedia: ${produk.stok}`);
                        return;
                    }
                    keranjang[index].qty++;
                }
            } else {
                if (produk.stok < 1) {
                    alert('Stok produk habis!');
                    return;
                }
                keranjang.push({
                    id: produk.id,
                    nama: produk.nama_produk,
                    harga: produk.harga_jual,
                    qty: 1, // Selalu set 1 untuk produk baru
                    diskon: produk.diskon || 0,
                    stok: produk.stok
                });
            }

            renderKeranjang();
            hitungTotal();
        }

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

        function hapusItem(index) {
            if (confirm('Hapus produk dari keranjang?')) {
                keranjang.splice(index, 1);
                renderKeranjang();
                hitungTotal();
            }
        }

        function hapusMembership() {
            membershipData = null;
            document.getElementById('membershipInput').value = '';
            document.getElementById('memberInfo').innerHTML = '';
            hitungTotal();
        }

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

        function formatInputRupiah(input) {
            let value = input.value.replace(/[^0-9]/g, '');
            input.value = value;
        }

        function setNominal(nominal) {
            document.getElementById('uangBayar').value = nominal;
            hitungKembalian();
        }

        function setPasExact() {
            const total = parseFloat(document.getElementById('totalText').textContent.replace(/[^0-9]/g, ''));
            document.getElementById('uangBayar').value = total;
            hitungKembalian();
        }

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
                        window.open(`/pos/cetak-struk/${data.data.transaksi_id}`, '_blank');
                        resetKeranjang();
                    } else {
                        alert('❌ Transaksi gagal: ' + data.message);
                    }
                })
                .catch(error => {
                    btnProses.disabled = false;
                    btnProses.innerHTML = '<i class="fas fa-check-circle me-2"></i> PROSES BAYAR';
                    console.error('Error:', error);
                    alert('Terjadi kesalahan: ' + error.message);
                });
        }

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

        function formatRupiah(angka) {
            return new Intl.NumberFormat('id-ID').format(angka);
        }

        window.onload = function () {
            document.getElementById('barcodeInput').focus();
        };
    </script>
@endsection