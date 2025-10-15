<nav class="layout-navbar navbar navbar-expand-xl align-items-center bg-navbar-theme shadow-sm px-3">
  <div class="container-fluid">

    <!-- Brand -->
    <a href="{{ url('/') }}" class="navbar-brand d-flex align-items-center">
      <img src="{{ asset('assets/img/logo.png') }}" alt="KasirApp Logo" style="height: 38px;" class="me-2">
      <span class="fw-semibold fs-5 text-dark mb-0">KasirApp</span>
    </a>

    <!-- Toggler visible on tablet & mobile -->
    <button
      class="navbar-toggler border-0 d-xl-none"
      type="button"
      data-bs-toggle="offcanvas"
      data-bs-target="#offcanvasNav"
      aria-controls="offcanvasNav"
    >
      <i class="bx bx-menu fs-3"></i>
    </button>

    <!-- Desktop Menu (hidden on mobile) -->
    <div class="collapse navbar-collapse justify-content-end d-none d-xl-flex" id="desktopNavbar">
      <ul class="navbar-nav align-items-center">

        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('dashboard') ? 'active fw-bold' : '' }}" href="{{ route('dashboard') }}">
            <i class="bx bx-home-circle me-1"></i> Dashboard
          </a>
        </li>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle {{ request()->routeIs('barang.*') || request()->routeIs('categories.*') ? 'active fw-bold' : '' }}" href="#" id="navbarMasterBarang" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bx bx-box me-1"></i> Master Barang
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarMasterBarang">
            <li><a class="dropdown-item {{ request()->routeIs('barang.*') ? 'active' : '' }}" href="{{ route('barang.index') }}">Data Barang</a></li>
            <li><a class="dropdown-item {{ request()->routeIs('categories.*') ? 'active' : '' }}" href="{{ route('categories.index') }}">Categories</a></li>
          </ul>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="#"><i class="bx bx-bar-chart-alt me-1"></i> Laporan Keuangan</a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="#"><i class="bx bx-cog me-1"></i> Pengaturan</a>
        </li>

        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('pos.index') ? 'active fw-bold' : '' }}" href="{{ route('pos.index') }}">
            <i class="bx bx-cart me-1"></i> POS
          </a>
        </li>

      </ul>
    </div>
  </div>
</nav>

<!-- ============================
     OFFCANVAS MENU (mobile/tablet)
     ============================ -->
<div
  class="offcanvas offcanvas-end"
  tabindex="-1"
  id="offcanvasNav"
  aria-labelledby="offcanvasNavLabel"
>
  <div class="offcanvas-header border-bottom">
    <h5 id="offcanvasNavLabel" class="offcanvas-title fw-semibold">Menu</h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>

  <div class="offcanvas-body">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active fw-bold' : '' }}" href="{{ route('dashboard') }}">
          <i class="bx bx-home-circle me-1"></i> Dashboard
        </a>
      </li>

      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle {{ request()->routeIs('barang.*') || request()->routeIs('categories.*') ? 'active fw-bold' : '' }}" href="#" id="menuBarang" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="bx bx-box me-1"></i> Master Barang
        </a>
        <ul class="dropdown-menu" aria-labelledby="menuBarang">
          <li><a class="dropdown-item" href="{{ route('barang.index') }}">Data Barang</a></li>
          <li><a class="dropdown-item" href="{{ route('categories.index') }}">Categories</a></li>
        </ul>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="#"><i class="bx bx-bar-chart-alt me-1"></i> Laporan Keuangan</a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="#"><i class="bx bx-cog me-1"></i> Pengaturan</a>
      </li>

      <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('pos.index') ? 'active fw-bold' : '' }}" href="{{ route('pos.index') }}">
          <i class="bx bx-cart me-1"></i> POS
        </a>
      </li>
    </ul>
  </div>
</div>

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

