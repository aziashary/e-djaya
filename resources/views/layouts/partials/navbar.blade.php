@php
  $level = strtolower((string) Auth::user()->level);
  $isAdmin = $level === 'admin';
  $isStaff = $level === 'staff';
  $logo = $isStaff ? asset('assets/img/ranu logo.png') : asset('assets/img/logo.png');
  $brandName = $isStaff ? 'Ranu' : 'Djaya 590';
@endphp

<nav class="navbar navbar-expand-sm app-navbar sticky-top" aria-label="Navigasi utama">
  <div class="navbar-shell d-flex align-items-center flex-nowrap w-100">
    <a href="{{ route('dashboard') }}" class="navbar-brand" aria-label="{{ $brandName }}, buka dashboard">
      <img src="{{ $logo }}" alt="Logo {{ $brandName }}" class="brand-logo" width="120" height="38">
      <span class="brand-copy" aria-hidden="true">
        <span class="brand-name">e-Djaya</span>
        <span class="brand-context">{{ $brandName }}</span>
      </span>
    </a>

    <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu" aria-controls="navbarMenu" aria-expanded="false" aria-label="Buka menu navigasi">
      <span class="navbar-toggler-label">Menu</span>
      <i class="bx bx-menu" aria-hidden="true"></i>
    </button>

    <div class="collapse navbar-collapse" id="navbarMenu">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link nav-icon-tablet {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}" title="Dashboard" aria-label="Dashboard" @if(request()->routeIs('dashboard')) aria-current="page" @endif>
            <i class="bx bx-grid-alt" aria-hidden="true"></i>
            <span class="nav-text">Dashboard</span>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link nav-pos nav-icon-tablet {{ request()->routeIs('pos.index') ? 'active' : '' }}" href="{{ route('pos.index') }}" title="Buka POS" aria-label="Buka POS" @if(request()->routeIs('pos.index')) aria-current="page" @endif>
            <i class="bx bx-cart" aria-hidden="true"></i>
            <span class="nav-text">Buka POS</span>
          </a>
        </li>

        <li class="nav-item dropdown">
          <button class="nav-link dropdown-toggle {{ request()->routeIs('barang.*') || request()->routeIs('categories.*') ? 'active' : '' }}" type="button" id="navbarMasterBarang" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bx bx-package" aria-hidden="true"></i>
            Produk
          </button>
          <ul class="dropdown-menu" aria-labelledby="navbarMasterBarang">
            <li><a class="dropdown-item {{ request()->routeIs('barang.*') ? 'active' : '' }}" href="{{ route('barang.index') }}">Daftar barang</a></li>
            <li><a class="dropdown-item {{ request()->routeIs('categories.*') ? 'active' : '' }}" href="{{ route('categories.index') }}">Kategori</a></li>
          </ul>
        </li>

        @if($isAdmin)
          <li class="nav-item dropdown">
            <button class="nav-link dropdown-toggle {{ request()->routeIs('laporan.*') ? 'active' : '' }}" type="button" id="navbarLaporan" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="bx bx-line-chart" aria-hidden="true"></i>
              Laporan
            </button>
            <ul class="dropdown-menu" aria-labelledby="navbarLaporan">
              <li><a class="dropdown-item {{ request()->routeIs('laporan.keuangan') ? 'active' : '' }}" href="{{ route('laporan.keuangan') }}">Keuangan</a></li>
              <li><a class="dropdown-item {{ request()->routeIs('laporan.produk') ? 'active' : '' }}" href="{{ route('laporan.produk') }}">Produk</a></li>
              <li><a class="dropdown-item {{ request()->routeIs('laporan.transaksi') ? 'active' : '' }}" href="{{ route('laporan.transaksi') }}">Transaksi</a></li>
            </ul>
          </li>

          <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}" @if(request()->routeIs('users.*')) aria-current="page" @endif>
              <i class="bx bx-user-circle" aria-hidden="true"></i>
              Pengguna
            </a>
          </li>
        @endif

        <li class="nav-item dropdown ms-xl-1">
          <button class="account-trigger dropdown-toggle" type="button" id="navbarAccount" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bx bx-user" aria-hidden="true"></i>
            <span class="account-meta">
              <span class="account-name">{{ Auth::user()->name }}</span>
              <span class="account-role">{{ ucfirst($level) }}</span>
            </span>
          </button>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarAccount">
            <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profil akun</a></li>
            <li><hr class="dropdown-divider"></li>
            <li>
              <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="dropdown-item text-danger">Keluar</button>
              </form>
            </li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>
