<div class="container-xxl mt-3">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
        @hasSection('breadcrumb')
          @yield('breadcrumb')
        @else
          <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
        @endif
      </ol>
    </nav>
  </div>
  