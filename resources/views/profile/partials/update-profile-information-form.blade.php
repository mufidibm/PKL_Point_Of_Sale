<style>
    .custom-file-upload {
        display: inline-block;
        padding: 8px 20px;
        cursor: pointer;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 8px;
        transition: all 0.3s ease;
        font-size: 14px;
        font-weight: 500;
        text-align: center;
        box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
    }
    
    .custom-file-upload:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    }
    
    .custom-file-upload i {
        margin-right: 8px;
    }
    
    #foto_profil {
        display: none;
    }
    
    .file-name-display {
        margin-top: 10px;
        padding: 8px 12px;
        background: #f8f9fa;
        border-radius: 6px;
        font-size: 13px;
        color: #495057;
        display: none;
    }
    
    .file-name-display.show {
        display: block;
    }
    
    .file-name-display i {
        color: #28a745;
        margin-right: 6px;
    }
    
    .profile-photo-section {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
</style>

<form method="post"
      action="{{ route('profile.update') }}"
      enctype="multipart/form-data"
      class="mt-4">
    @csrf
    @method('patch')
    <div class="row">
        <!-- Foto Profil + Preview -->
        <div class="col-md-4">
            <div class="profile-photo-section text-center">
                @php
                    $name = auth()->user()->name;
                    $foto = auth()->user()->foto_profil;
                    $defaultAvatar = 'https://ui-avatars.com/api/?name=' . urlencode($name) . '&background=343a40&color=fff&bold=true&rounded=true&size=256';
                    $currentPhoto = $foto ? (filter_var($foto, FILTER_VALIDATE_URL) ? $foto : Storage::url($foto)) : $defaultAvatar;
                @endphp
                
                <img id="preview-foto"
                     src="{{ $currentPhoto }}"
                     class="profile-user-img img-fluid img-circle elevation-2 mb-3"
                     style="width: 150px; height: 150px; object-fit: cover;"
                     alt="Foto Profil">
                
                <div class="form-group">
                    <label for="foto_profil" class="custom-file-upload">
                        <i class="fas fa-cloud-upload-alt"></i>
                        Pilih Foto Baru
                    </label>
                    <input type="file"
                           name="foto_profil"
                           id="foto_profil"
                           accept="image/*">
                    
                    <div id="file-name-display" class="file-name-display">
                        <i class="fas fa-check-circle"></i>
                        <span id="file-name-text"></span>
                    </div>
                    
                    @error('foto_profil')
                        <small class="text-danger d-block mt-2">{{ $message }}</small>
                    @enderror
                </div>
                
                @if(auth()->user()->foto_profil)
                <button type="button"
                        onclick="hapusFoto()"
                        class="btn btn-danger btn-sm mt-2">
                    <i class="fas fa-trash"></i> Hapus Foto
                </button>
                @endif
            </div>
        </div>
        
        <!-- Form Nama & Email -->
        <div class="col-md-8">
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text"
                       name="name"
                       value="{{ old('name', auth()->user()->name) }}"
                       class="form-control @error('name') is-invalid @enderror"
                       required
                       autofocus>
                @error('name')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
                <label>Email</label>
                <input type="email"
                       name="email"
                       value="{{ old('email', auth()->user()->email) }}"
                       class="form-control @error('email') is-invalid @enderror"
                       required>
                @error('email')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
           
            <div class="form-group">
                <label>Role</label>
                <input value="{{ old('role', auth()->user()->role) }}"
                       class="form-control"
                       readonly>
            </div>
            
            <button type="submit"
                    class="btn btn-primary">
                <i class="fas fa-save"></i> Simpan Perubahan
            </button>
            
            @if(session('status') === 'profile-updated')
                <span class="text-success ml-3">
                    <i class="fas fa-check"></i> Profil berhasil diperbarui!
                </span>
            @endif
        </div>
    </div>
</form>

<script>
    // Live preview foto dan tampilkan nama file
    document.getElementById('foto_profil').addEventListener('change', function (e) {
        const file = e.target.files[0];
        const fileNameDisplay = document.getElementById('file-name-display');
        const fileNameText = document.getElementById('file-name-text');
        
        if (file) {
            // Preview foto
            const reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById('preview-foto').src = e.target.result;
            }
            reader.readAsDataURL(file);
            
            // Tampilkan nama file
            fileNameText.textContent = file.name;
            fileNameDisplay.classList.add('show');
        } else {
            fileNameDisplay.classList.remove('show');
        }
    });
    
    // Hapus foto
    function hapusFoto() {
        if (!confirm('Yakin ingin menghapus foto profil?')) return;
        
        const userName = '{{ auth()->user()->name }}';
        const defaultAvatar = 'https://ui-avatars.com/api/?name=' + encodeURIComponent(userName) + '&background=343a40&color=fff&bold=true&rounded=true&size=256';
        
        fetch('{{ route('profile.hapus-foto') }}', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                document.getElementById('preview-foto').src = defaultAvatar;
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghapus foto');
        });
    }
</script>