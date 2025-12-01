@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
    <div class="row">
        <div class="col-md-3">
            <!-- Foto profil -->
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <div class="text-center">
                        <img class="profile-user-img img-fluid img-circle"
                             src="{{ auth()->user()->profile_photo_url ?? asset('adminlte/dist/img/user2-160x160.jpg') }}"
                             alt="User profile picture">
                    </div>
                    <h3 class="profile-username text-center">{{ auth()->user()->name }}</h3>
                    <p class="text-muted text-center text-capitalize">{{ auth()->user()->role ?? 'User' }}</p>
                </div>
            </div>
        </div>

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
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <!-- Tab Informasi Profil -->
                        <div class="tab-pane active"
                             id="info">
                            @include('profile.partials.update-profile-information-form')
                        </div>

                        <!-- Tab Ganti Password -->
                        <div class="tab-pane"
                             id="password">
                            @include('profile.partials.update-password-form')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection