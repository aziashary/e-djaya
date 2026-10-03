@php
  $level = strtolower((string) Auth::user()->level);
  $isAdmin = $level === 'admin';
@endphp

<nav x-data="{ open: false }" class="ui-nav" aria-label="Navigasi akun">
  <div class="ui-nav-shell">
    <a href="{{ route('dashboard') }}" aria-label="e-Djaya, buka dashboard">
      <img src="{{ asset('assets/img/logo.png') }}" alt="Logo e-Djaya" class="ui-nav-logo" width="120" height="36">
    </a>

    <div class="ui-nav-links hidden md:flex">
      <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">Dashboard</x-nav-link>
      <x-nav-link :href="route('pos.index')" :active="request()->routeIs('pos.*')">POS</x-nav-link>
      <x-nav-link :href="route('barang.index')" :active="request()->routeIs('barang.*') || request()->routeIs('categories.*')">Produk</x-nav-link>
      @if($isAdmin)
        <x-nav-link :href="route('laporan.keuangan')" :active="request()->routeIs('laporan.*')">Laporan</x-nav-link>
        <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')">Pengguna</x-nav-link>
      @endif

      <x-dropdown align="right" width="48">
        <x-slot name="trigger">
          <button type="button" class="ui-nav-link" aria-haspopup="menu" x-bind:aria-expanded="open.toString()">
            {{ Auth::user()->name }}
          </button>
        </x-slot>
        <x-slot name="content">
          <x-dropdown-link :href="route('profile.edit')">Profil akun</x-dropdown-link>
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">Keluar</x-dropdown-link>
          </form>
        </x-slot>
      </x-dropdown>
    </div>

    <button type="button" @click="open = !open" :aria-expanded="open.toString()" aria-controls="account-mobile-menu" class="ui-button-secondary md:hidden">
      Menu
    </button>
  </div>

  <div id="account-mobile-menu" x-show="open" x-transition class="border-t border-[#e7d8d1] bg-white md:hidden" @keydown.escape.window="open = false">
    <div class="mx-auto grid w-[calc(100%-1rem)] max-w-7xl gap-1 py-2">
      <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">Dashboard</x-responsive-nav-link>
      <x-responsive-nav-link :href="route('pos.index')" :active="request()->routeIs('pos.*')">POS</x-responsive-nav-link>
      <x-responsive-nav-link :href="route('barang.index')" :active="request()->routeIs('barang.*') || request()->routeIs('categories.*')">Produk</x-responsive-nav-link>
      @if($isAdmin)
        <x-responsive-nav-link :href="route('laporan.keuangan')" :active="request()->routeIs('laporan.*')">Laporan</x-responsive-nav-link>
        <x-responsive-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')">Pengguna</x-responsive-nav-link>
      @endif
      <x-responsive-nav-link :href="route('profile.edit')" :active="request()->routeIs('profile.*')">Profil akun</x-responsive-nav-link>
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">Keluar</x-responsive-nav-link>
      </form>
    </div>
  </div>
</nav>
