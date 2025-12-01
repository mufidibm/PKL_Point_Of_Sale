<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link"
         data-widget="pushmenu"
         href="#"
         role="button"><i class="fas fa-bars"></i></a>
    </li>
  </ul>

  <ul class="navbar-nav ml-auto">
    {{-- Tombol Fullscreen --}}
    <li class="nav-item">
      <a class="nav-link"
         data-widget="fullscreen"
         href="#"
         role="button">
        <i class="fas fa-expand-arrows-alt"></i>
      </a>
    </li>

    {{-- Dropdown Profil --}}
    <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle"
         href="#"
         id="userDropdown"
         role="button"
         data-toggle="dropdown"
         aria-haspopup="true"
         aria-expanded="false">
        <i class="fas fa-user-circle"></i> {{ Auth::user()->name }}
      </a>
      <div class="dropdown-menu dropdown-menu-right"
           aria-labelledby="userDropdown">
        <a class="dropdown-item"
           href="/profile">Profil Saya</a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item"
           href="{{ route('logout') }}"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
          <i class="fas fa-sign-out-alt"></i> Logout
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