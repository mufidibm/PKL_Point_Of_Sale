<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link"
         data-widget="pushmenu"
         href="#"
         role="button">
        <i class="fas fa-bars"></i>
      </a>
    </li>
  </ul>

  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto">
    <!-- Fullscreen -->
    <li class="nav-item">
      <a class="nav-link"
         data-widget="fullscreen"
         href="#"
         role="button">
        <i class="fas fa-expand-arrows-alt"></i>
      </a>
    </li>

    <!-- User Dropdown -->
    <li class="nav-item dropdown user-menu">
      <a class="nav-link dropdown-toggle d-flex align-items-center"
         href="#"
         id="userDropdown"
         role="button"
         data-toggle="dropdown"
         aria-haspopup="true"
         aria-expanded="false"
         style="white-space: nowrap;">

        @php
          $name = auth()->user()->name;
          $foto = auth()->user()->foto_profil;
          $defaultAvatar = 'https://ui-avatars.com/api/?name=' . urlencode($name) . '&background=343a40&color=fff&bold=true&rounded=true&size=128';
          $currentPhoto = $foto ? (filter_var($foto, FILTER_VALIDATE_URL) ? $foto : Storage::url($foto)) : $defaultAvatar;
        @endphp

        <img src="{{ $currentPhoto }}"
             alt="{{ $name }}"
             class="img-circle elevation-2"
             style="width: 33px; height: 33px; object-fit: cover; flex-shrink: 0;">
        <span class="d-none d-md-inline ml-2">{{ $name }}</span>
      </a>

      <!-- Dropdown menu dengan lebar pas -->
      <div class="dropdown-menu dropdown-menu-right"
           style="min-width: 160px; max-width: 200px;"
           aria-labelledby="userDropdown">
        <a href="/profile"
           class="dropdown-item">
          <i class="fas fa-user mr-2"></i> Profil Saya
        </a>
        <div class="dropdown-divider"></div>
        <a href="{{ route('logout') }}"
           class="dropdown-item"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
          <i class="fas fa-sign-out-alt mr-2"></i> Logout
        </a>
        <form id="logout-form"
              action="{{ route('logout') }}"
              method="POST"
              class="d-none">
          @csrf
        </form>
      </div>
    </li>
  </ul>
</nav>