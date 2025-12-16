@extends('layouts.app')

@section('title', 'Profil Saya')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
    <li class="breadcrumb-item active">Profil Saya</li>
@endsection

@section('content')
    <div class="row">

        <!-- Form Profil (Full Breeze Style) -->
        <div class="col-md-9">
            <div class="card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item"><a class="nav-link active"
                               href="#info"
                               data-toggle="tab">Informasi Profil</a></li>
                        <li class="nav-item"><a class="nav-link"
                               href="#password"
                               data-toggle="tab">Ganti Password</a></li>
                        <li class="nav-item"><a class="nav-link"
                               href="#delete"
                               data-toggle="tab"
                               class="text-danger">Hapus Akun</a></li>
                    </ul>
                </div>

                <div class="card-body">
                    <div class="tab-content">

                        <!-- TAB INFORMASI PROFIL (Breeze asli) -->
                        <div class="tab-pane active"
                             id="info">
                            @include('profile.partials.update-profile-information-form')
                        </div>

                        <!-- TAB GANTI PASSWORD (Breeze asli) -->
                        <div class="tab-pane"
                             id="password">
                            @include('profile.partials.update-password-form')
                        </div>

                        <!-- TAB HAPUS AKUN (Breeze asli) -->
                        <div class="tab-pane"
                             id="delete">
                            @include('profile.partials.delete-user-form')
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

{{-- Script supaya preview foto langsung muncul di sidebar juga --}}
@push('scripts')
    <script>
        // Preview foto di form + sidebar
        document.getElementById('photo')?.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function (e) {
                // Update preview di dalam form Breeze
                const preview = document.querySelector('[x-ref="photoPreview"]');
                if (preview) preview.src = e.target.result;

                // Update foto di sidebar AdminLTE
                document.getElementById('profile-photo-sidebar').src = e.target.result;
            }
            reader.readAsDataURL(file);
        });
    </script>
@endpush