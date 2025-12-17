@extends('layouts.app')
@section('title', 'Pengaturan Toko')
@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('settings.update') }}"
                              method="POST"
                              enctype="multipart/form-data">
                            @csrf
                            @method('POST')

                            @if(session('error'))
                                <div class="alert alert-danger alert-dismissible fade show"
                                     role="alert">
                                    <strong>Error!</strong> {{ session('error') }}
                                    <button type="button"
                                            class="btn-close"
                                            data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            {{-- Logo Toko --}}
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Logo Toko</label>
                                <div class="col-sm-10">
                                    @if($settings->logo)
                                        <div class="mb-3">
                                            <img src="{{ $settings->logo_url }}"
                                                 alt="Logo Toko"
                                                 class="img-thumbnail"
                                                 style="max-height: 150px;">
                                            <div class="mt-2">
                                                <a href="{{ route('settings.delete-logo') }}"
                                                   class="btn btn-sm btn-danger"
                                                   onclick="event.preventDefault(); if(confirm('Yakin ingin menghapus logo?')) document.getElementById('delete-logo-form').submit();">
                                                    <i class="fas fa-trash"></i> Hapus Logo
                                                </a>
                                            </div>
                                        </div>
                                    @endif

                                    <input type="file"
                                           class="form-control @error('logo') is-invalid @enderror"
                                           name="logo"
                                           accept="image/*"
                                           onchange="previewLogo(event)">
                                    <small class="text-muted">Format: JPG, JPEG, PNG, GIF. Max: 2MB</small>

                                    @error('logo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror

                                    {{-- Preview Logo --}}
                                    <div id="logo-preview"
                                         class="mt-3"
                                         style="display: none;">
                                        <img id="preview-img"
                                             src=""
                                             alt="Preview"
                                             class="img-thumbnail"
                                             style="max-height: 150px;">
                                    </div>
                                </div>
                            </div>

                            {{-- Nama Toko --}}
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Nama Toko *</label>
                                <div class="col-sm-10">
                                    <input type="text"
                                           class="form-control @error('nama_toko') is-invalid @enderror"
                                           name="nama_toko"
                                           value="{{ old('nama_toko', $settings->nama_toko) }}"
                                           required>
                                    @error('nama_toko')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Alamat --}}
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Alamat</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control"
                                              name="alamat"
                                              rows="3">{{ old('alamat', $settings->alamat) }}</textarea>
                                </div>
                            </div>

                            {{-- Telepon --}}
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Telepon</label>
                                <div class="col-sm-10">
                                    <input type="text"
                                           class="form-control"
                                           name="telepon"
                                           value="{{ old('telepon', $settings->telepon) }}">
                                </div>
                            </div>

                            {{-- Email --}}
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Email</label>
                                <div class="col-sm-10">
                                    <input type="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           name="email"
                                           value="{{ old('email', $settings->email) }}">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Catatan Footer 1 --}}
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Catatan Footer 1</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control"
                                              name="footer_note1"
                                              rows="2">{{ old('footer_note1', $settings->footer_note1) }}</textarea>
                                    <small class="text-muted">Contoh: Barang yang sudah dibeli tidak dapat
                                        dikembalikan</small>
                                </div>
                            </div>

                            {{-- Catatan Footer 2 --}}
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Catatan Footer 2</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control"
                                              name="footer_note2"
                                              rows="2">{{ old('footer_note2', $settings->footer_note2) }}</textarea>
                                    <small class="text-muted">Contoh: Selamat Berbelanja Kembali</small>
                                </div>
                            </div>

                            {{-- Pesan Terima Kasih --}}
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Pesan Terima Kasih</label>
                                <div class="col-sm-10">
                                    <input type="text"
                                           class="form-control @error('thank_you_message') is-invalid @enderror"
                                           name="thank_you_message"
                                           value="{{ old('thank_you_message', $settings->thank_you_message) }}"
                                           required>
                                    @error('thank_you_message')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-10 offset-sm-2">
                                    <button type="submit"
                                            class="btn btn-primary">
                                        <i class="fas fa-save"></i> Simpan Perubahan
                                    </button>
                                </div>
                            </div>
                        </form>

                        {{-- Form tersembunyi untuk hapus logo --}}
                        <form id="delete-logo-form"
                              action="{{ route('settings.delete-logo') }}"
                              method="POST"
                              style="display: none;">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function previewLogo(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('preview-img').src = e.target.result;
                    document.getElementById('logo-preview').style.display = 'block';
                }
                reader.readAsDataURL(file);
            }
        }
    </script>
@endsection