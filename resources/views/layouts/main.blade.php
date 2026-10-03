<!doctype html>
<html lang="id" data-assets-path="{{ asset('assets/') }}/">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="theme-color" content="#b42318">
  <meta name="mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="default">
  <meta name="apple-mobile-web-app-title" content="e-Djaya">
  <title>@yield('judul', 'e-Djaya')</title>

  <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/img/favicon/favicon-32x32.png') }}">
  <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/img/favicon/favicon-16x16.png') }}">
  <link rel="manifest" href="{{ asset('assets/img/favicon/site.webmanifest') }}">
  <link rel="apple-touch-icon" href="{{ asset('assets/img/favicon/favicon-192x192.png') }}">
  <link rel="shortcut icon" href="{{ asset('assets/img/favicon/favicon.ico') }}">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/iconify-icons.css') }}">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/litepicker/dist/css/litepicker.css">
  <link rel="stylesheet" href="{{ asset('assets/css/edjaya-ui.css') }}?v={{ file_exists(public_path('assets/css/edjaya-ui.css')) ? filemtime(public_path('assets/css/edjaya-ui.css')) : time() }}">

  @stack('styles')
  @stack('css')
</head>
<body>
  <a class="skip-link" href="#main-content">Lewati ke konten utama</a>
  <div class="app-loading-bar" data-page-loading role="status" aria-label="Memuat halaman" aria-hidden="true" hidden></div>
  <div class="app-error-banner" data-page-error role="alert" hidden>
    <span data-error-message></span>
    <button type="button" class="btn btn-sm btn-outline-danger ms-2" data-dismiss-error>Tutup</button>
  </div>

  <div class="layout-wrapper layout-content-navbar layout-without-menu">
    <div class="layout-container">
      <div class="layout-page">
        @include('layouts.partials.navbar')

        <main id="main-content" class="app-main" tabindex="-1">
          <div class="app-content">
            @include('layouts.partials.flash')
            @yield('content')
          </div>
        </main>

        @includeWhen(View::exists('layouts.partials.footer'), 'layouts.partials.footer')
      </div>
    </div>
  </div>

  <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
  <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
  <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
  <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
  <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/litepicker/dist/bundle.js"></script>
  <script src="{{ asset('assets/js/edjaya-ui.js') }}?v={{ file_exists(public_path('assets/js/edjaya-ui.js')) ? filemtime(public_path('assets/js/edjaya-ui.js')) : time() }}"></script>

  @yield('scripts')
  @stack('scripts')
  <script>
    if ('serviceWorker' in navigator) {
      window.addEventListener('load', () => {
        navigator.serviceWorker.register('{{ asset('sw.js') }}?v={{ file_exists(public_path('sw.js')) ? filemtime(public_path('sw.js')) : time() }}').catch(() => {});
      });
    }
  </script>
</body>
</html>
