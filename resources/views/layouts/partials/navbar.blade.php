<!-- Navbar Wrapper -->
<nav class="navbar navbar-expand-xl navbar-light bg-white shadow-sm sticky-top">
  <div class="container-fluid">

    <!-- Brand -->
    <a href="{{ url('/') }}" class="navbar-brand d-flex align-items-center">
      <img src="{{ asset('assets/img/logo.png') }}" alt="KasirApp Logo" style="height: 38px;" class="me-2">
    </a>

    <!-- Toggle (for mobile) -->
    <button class="navbar-toggler d-xl-none border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu" aria-controls="navbarMenu" aria-expanded="false" aria-label="Toggle navigation">
      <i class="bx bx-menu" style="font-size: 1.5rem;"></i>
    </button>

    <!-- Desktop Menu -->
    <div class="collapse navbar-collapse justify-content-end" id="navbarMenu">
      <ul class="navbar-nav align-items-center">

        <!-- Dashboard -->
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('dashboard') ? 'active fw-bold' : '' }}" href="{{ route('dashboard') }}">
            <i class="bx bx-home-circle me-1"></i> Dashboard
          </a>
        </li>

        <!-- Master Barang -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle {{ request()->routeIs('barang.*') || request()->routeIs('categories.*') ? 'active fw-bold' : '' }}" href="#" id="navbarMasterBarang" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bx bx-box me-1"></i> Master Barang
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarMasterBarang">
            <li><a class="dropdown-item {{ request()->routeIs('barang.*') ? 'active' : '' }}" href="{{ route('barang.index') }}">Data Barang</a></li>
            <li><a class="dropdown-item {{ request()->routeIs('categories.*') ? 'active' : '' }}" href="{{ route('categories.index') }}">Categories</a></li>
          </ul>
        </li>

        <!-- Laporan -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle {{ request()->routeIs('laporan.*') ? 'active fw-bold' : '' }}" href="#" id="navbarLaporan" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bx bx-chart me-1"></i> Laporan
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarLaporan">
            <li><a class="dropdown-item {{ request()->routeIs('laporan.keuangan') ? 'active' : '' }}" href="{{ route('laporan.keuangan') }}">Laporan Keuangan</a></li>
            <li><a class="dropdown-item {{ request()->routeIs('laporan.produk') ? 'active' : '' }}" href="{{ route('laporan.produk') }}">Laporan Produk</a></li>
          </ul>
        </li>

        <!-- Pengaturan -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle {{ request()->routeIs('users.*') ? 'active fw-bold' : '' }}" href="#" id="navbarUsers" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bx bx-cog me-1"></i> Pengaturan
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarUsers">
            <li><a class="dropdown-item {{ request()->routeIs('users.index') ? 'active' : '' }}" href="{{ route('users.index') }}">User</a></li>
          </ul>
        </li>

        <!-- POS -->
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('pos.index') ? 'active fw-bold' : '' }}" href="{{ route('pos.index') }}">
            <i class="bx bx-cart me-1"></i> POS
          </a>
        </li>

        <!-- Logout (Icon only for desktop, text in mobile) -->
        {{-- <li class="nav-item ms-xl-3 mt-2 mt-xl-0">
          <form action="{{ route('logout') }}" method="POST" class="d-inline" onsubmit="return confirm('Rek Cabut, bray?')">
            @csrf
            <button type="submit" class="btn btn-outline-danger btn-sm d-flex align-items-center gap-1">
              <i class="bx bx-log-out"></i>
              <span class="d-none d-xl-inline">Logout</span>
            </button>
          </form>
        </li> --}}

      </ul>
    </div>
  </div>
</nav>



<style>
  /* ==============================
     🌐 NAVBAR HYBRID STYLE - FIXED
     ============================== */

  /* === DESKTOP NAVBAR === */
  .navbar-nav {
    gap: 0.75rem; /* kasih jarak antar menu */
  }

  .navbar-nav .nav-link {
    display: flex;
    align-items: center;
    color: var(--bs-body-color);
    padding: 8px 14px;
    border-radius: 6px;
    transition: 0.25s ease;
    font-weight: 500;
  }

  .navbar-nav .nav-link:hover {
    background-color: var(--bs-primary-bg-subtle);
    color: var(--bs-primary);
  }

  .navbar-nav .nav-link.active {
    background-color: var(--bs-primary);
    color: #fff !important;
  }

  /* === DROPDOWN DI DESKTOP === */
  .dropdown-menu {
    border-radius: 8px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    padding: 0.5rem 0;
    border: none;
  }

  .dropdown-item {
    padding: 0.6rem 1.25rem;
    border-radius: 4px;
  }

  .dropdown-item:hover {
    background-color: var(--bs-primary-bg-subtle);
    color: var(--bs-primary);
  }

  /* === OFFCANVAS PANEL (MOBILE/TABLET) === */
  .offcanvas-end {
    width: 280px;
    box-shadow: -4px 0 20px rgba(0, 0, 0, 0.1);
  }

  .offcanvas-header {
    border-bottom: 1px solid #eee;
  }

  .offcanvas-body .nav-link {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 14px;
    border-radius: 6px;
    color: var(--bs-body-color);
    transition: 0.2s ease;
  }

  .offcanvas-body .nav-link.active {
    background-color: var(--bs-primary);
    color: #fff !important;
  }

  .offcanvas-body .nav-link:hover {
    background-color: var(--bs-primary-bg-subtle);
    color: var(--bs-primary);
  }

  /* === DROPDOWN DI MOBILE (biar gak nabrak bawah) === */
  .offcanvas-body .dropdown-menu {
    position: static;
    border: none;
    background: transparent;
    box-shadow: none;
    margin-top: 0.25rem;
    padding: 0;
  }

  .offcanvas-body .dropdown-menu .dropdown-item {
    padding: 0.6rem 1.5rem;
    border-radius: 6px;
    background: var(--bs-body-bg);
    margin-bottom: 0.35rem;
  }

  .offcanvas-body .dropdown-menu .dropdown-item:hover {
    background-color: var(--bs-primary-bg-subtle);
    color: var(--bs-primary);
  }

  /* kasih jarak bawah antar menu mobile */
  .offcanvas-body .nav-item {
    margin-bottom: 0.3rem;
  }

  /* === RESPONSIVE VISIBILITY === */
  @media (min-width: 1200px) {
    .offcanvas {
      display: none !important;
    }
  }
</style>

<style>
  .navbar-nav .nav-link {
  color: #555;
  transition: color 0.2s ease;
}

.navbar-nav .nav-link:hover,
.navbar-nav .nav-link.active {
  color: #ffffff !important;
}

.navbar .dropdown-menu {
  border-radius: 8px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}

.navbar .dropdown-item.active {
  font-weight: 600;
  color: #0d6efd !important;
}

@media (max-width: 1199.98px) {
  .navbar-nav .dropdown-menu {
    position: static;
    float: none;
    box-shadow: none;
  }
  .navbar-nav .dropdown-item {
    padding-left: 2rem;
  }
}
</style>
