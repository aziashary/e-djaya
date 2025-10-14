<aside id="layout-menu" class="layout-menu menu-vertical bg-menu-theme">
    <div class="app-brand demo d-flex align-items-center justify-content-center py-3">
        <a href="{{ url('/') }}" class="app-brand-link d-flex align-items-center justify-content-center">
          <img src="{{ asset('assets/img/logo.png') }}" alt="KasirApp Logo" class="app-brand-logo demo" style="max-height: 48px;">
        </a>
      
        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-xl-none">
          <i class="bx bx-chevron-left align-middle"></i>
        </a>
    </div>
      
  
    <div class="menu-inner-shadow"></div>
  
    <ul class="menu-inner py-1">
  
      <!-- Dashboard -->
      <li class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <a href="{{ route('dashboard') }}" class="menu-link">
          <i class="menu-icon tf-icons bx bx-home-circle"></i>
          <div data-i18n="Dashboard">Dashboard</div>
        </a>
      </li>
  
      <!-- Barang -->
      <li class="menu-item {{ request()->routeIs('barang.*') || request()->routeIs('Categories.*') ? 'active open' : '' }}">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
          <i class="menu-icon tf-icons bx bx-box"></i>
          <div data-i18n="Master Barang">Master Barang</div>
        </a>
      
        <ul class="menu-sub">
          <li class="menu-item {{ request()->routeIs('barang.*') ? 'active' : '' }}">
            <a href="{{ route('barang.index') }}" class="menu-link">
              <div data-i18n="Barang">Data Barang</div>
            </a>
          </li>
          <li class="menu-item {{ request()->routeIs('Categories.*') ? 'active' : '' }}">
            <a href="{{ route('categories.index') }}" class="menu-link">
              <div data-i18n="Categories">Categories</div>
            </a>
          </li>
        </ul>
      </li>
      
  
      <!-- Laporan Keuangan -->
      <li class="menu-item ">
        <a href="" class="menu-link">
          <i class="menu-icon tf-icons bx bx-bar-chart-alt"></i>
          <div data-i18n="Laporan">Laporan Keuangan</div>
        </a>
      </li>
  
      <!-- Analytics -->
      {{-- <li class="menu-item ">
        <a href="" class="menu-link">
          <i class="menu-icon tf-icons bx bx-pie-chart"></i>
          <div data-i18n="Analytics">Analitik</div>
        </a>
      </li> --}}
  
      <!-- Settings -->
      <li class="menu-item ">
        <a href="" class="menu-link">
          <i class="menu-icon tf-icons bx bx-cog"></i>
          <div data-i18n="Settings">Pengaturan</div>
        </a>
      </li>

       <!-- POS -->
       <li class="menu-item">
        <a href="{{ route('pos.index') }}" class="menu-link">
          <i class="menu-icon tf-icons bx bx-cart"></i>
          <div data-i18n="POS">POS</div>
        </a>
      </li>
      
    </ul>
  </aside>
  