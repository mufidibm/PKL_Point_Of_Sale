@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">Pengaturan Toko</h3>
                </div>

                <form action="{{ route('settings.update') }}" method="POST">
                    @csrf
                    @method('POST')

                    <div class="card-body">
                        @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <div class="form-group">
                            <label>Nama Toko *</label>
                            <input type="text" name="nama_toko" class="form-control" 
                                   value="{{ old('nama_toko', $settings->nama_toko) }}" required>
                        </div>

                        <div class="form-group">
                            <label>Alamat</label>
                            <textarea name="alamat" class="form-control" rows="3">{{ old('alamat', $settings->alamat) }}</textarea>
                        </div>

                        <div class="form-group">
                            <label>Telepon</label>
                            <input type="text" name="telepon" class="form-control" 
                                   value="{{ old('telepon', $settings->telepon) }}">
                        </div>

                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" 
                                   value="{{ old('email', $settings->email) }}">
                        </div>

                        <div class="form-group">
                            <label>Catatan Footer 1</label>
                            <input type="text" name="footer_note1" class="form-control" 
                                   value="{{ old('footer_note1', $settings->footer_note1) }}"
                                   placeholder="Contoh: Barang yang sudah dibeli tidak dapat dikembalikan">
                        </div>

                        <div class="form-group">
                            <label>Catatan Footer 2</label>
                            <input type="text" name="footer_note2" class="form-control" 
                                   value="{{ old('footer_note2', $settings->footer_note2) }}"
                                   placeholder="Contoh: Selamat Berbelanja Kembali">
                        </div>

                        <div class="form-group">
                            <label>Pesan Terima Kasih</label>
                            <input type="text" name="thank_you_message" class="form-control" 
                                   value="{{ old('thank_you_message', $settings->thank_you_message) }}" required>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection