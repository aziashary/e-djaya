<nav class="mb-3" aria-label="Breadcrumb">
  <ol class="breadcrumb mb-0">
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    @hasSection('breadcrumb')
      @yield('breadcrumb')
    @endif
  </ol>
</nav>
