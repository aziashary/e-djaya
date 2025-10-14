<!doctype html>
<html lang="en" class="layout-menu-fixed layout-compact"
      data-assets-path="{{ asset('assets/') }}/"
      data-template="vertical-menu-template-free">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>@yield('judul')</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}" />
    <!-- Boxicons -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/iconify-icons.css') }}" />



    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('assets/js/config.js') }}"></script>

    @stack('css')
  </head>

  <body>
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        
        {{-- Sidebar --}}
        @include('layouts.partials.sidebar')

        <!-- Layout container -->
        <div class="layout-page">

          <!-- Breadcrumb -->
          @include('layouts.partials.breadcrumb')

          <!-- Content wrapper -->
          <div class="content-wrapper p-4">
            @yield('content')
          </div>
          <!-- / Content wrapper -->
          <!-- Footer  -->
          @include('layouts.partials.footer')
          <!-- / footer -->
        </div>
        <!-- / Layout page -->
      </div>
      <!-- / Layout container -->
    </div>

    <!-- Core JS -->
    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/menu.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>

    @stack('scripts')
  </body>
</html>
