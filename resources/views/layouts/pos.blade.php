<!doctype html>
<html lang="id" data-assets-path="{{ asset('assets/') }}/">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="theme-color" content="#fff8f3">
  <title>@yield('judul', 'POS e-Djaya')</title>

  <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/iconify-icons.css') }}">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/litepicker/dist/css/litepicker.css">
  <link rel="stylesheet" href="{{ asset('assets/css/edjaya-ui.css') }}?v={{ file_exists(public_path('assets/css/edjaya-ui.css')) ? filemtime(public_path('assets/css/edjaya-ui.css')) : time() }}">
  @stack('styles')
  @stack('css')
</head>
<body class="pos-page">
  @php
    $level = strtolower((string) Auth::user()->level);
    $isStaff = $level === 'staff';
    $storeName = $isStaff ? 'Ranu' : 'Djaya 590';
  @endphp

  <a class="skip-link" href="#main-content">Lewati ke area kasir</a>
  <div class="app-loading-bar" data-page-loading role="status" aria-label="Memuat halaman" aria-hidden="true" hidden></div>
  <div class="app-error-banner" data-page-error role="alert" hidden>
    <span data-error-message></span>
    <button type="button" class="btn btn-sm btn-outline-danger ms-2" data-dismiss-error>Tutup</button>
  </div>

  <div class="pos-wrapper">
    <header class="pos-header">
      <a href="{{ route('pos.index') }}" class="brand text-decoration-none" aria-label="POS {{ $storeName }}">
        <img src="{{ $isStaff ? asset('assets/img/ranu logo.png') : asset('assets/img/logo.png') }}" alt="Logo {{ $storeName }}" width="120" height="38">
        <span>
          <h1>Point of Sale</h1>
        </span>
      </a>

      <nav class="header-actions" aria-label="Navigasi kasir">
        <a href="{{ route('dashboard') }}" class="action-btn" data-leave-pos aria-label="Dashboard" title="Dashboard">
          <i class="bx bx-grid-alt" aria-hidden="true"></i>
        </a>
        <a href="{{ route('pos.index') }}" class="action-btn {{ request()->routeIs('pos.index') ? 'active' : '' }}" aria-label="Transaksi baru" title="Transaksi baru" @if(request()->routeIs('pos.index')) aria-current="page" @endif>
          <i class="bx bx-cart" aria-hidden="true"></i>
        </a>
        <a href="{{ route('pos.open-bills') }}" class="action-btn {{ request()->routeIs('pos.open-bills') ? 'active' : '' }}" aria-label="Open bill" title="Open bill" @if(request()->routeIs('pos.open-bills')) aria-current="page" @endif>
          <i class="bx bx-receipt" aria-hidden="true"></i>
        </a>
        <a href="{{ route('pos.riwayat') }}" class="action-btn {{ request()->routeIs('pos.riwayat') ? 'active' : '' }}" aria-label="Riwayat transaksi" title="Riwayat transaksi" @if(request()->routeIs('pos.riwayat')) aria-current="page" @endif>
          <i class="bx bx-history" aria-hidden="true"></i>
        </a>
        <button id="fullscreenBtn" type="button" class="action-btn" aria-label="Aktifkan layar penuh" title="Layar penuh" aria-pressed="false">
          <i class="bx bx-fullscreen" aria-hidden="true"></i>
        </button>
        <button id="logoutBtn" type="button" class="action-btn logout-btn" aria-label="Keluar dari aplikasi" title="Keluar">
          <i class="bx bx-log-out" aria-hidden="true"></i>
        </button>
      </nav>
    </header>

    <main id="main-content" class="pos-content" tabindex="-1">
      @include('layouts.partials.flash')
      @yield('content')
    </main>
  </div>

  <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h2 class="modal-title fs-5" id="logoutModalLabel">Keluar dari aplikasi?</h2>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup dialog"></button>
        </div>
        <div class="modal-body">
          <p class="mb-0">Sesi kasir akan ditutup. Pastikan transaksi yang sedang dikerjakan sudah selesai.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
          <form id="logoutForm" action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-danger">Keluar</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <div id="posFullscreenShell" class="pos-fullscreen-shell" hidden>
    <iframe id="posFullscreenFrame" title="e-Djaya POS layar penuh" allow="fullscreen"></iframe>
  </div>

  <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
  <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
  <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
  <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/litepicker/dist/bundle.js"></script>
  <script src="{{ asset('assets/js/edjaya-ui.js') }}?v={{ file_exists(public_path('assets/js/edjaya-ui.js')) ? filemtime(public_path('assets/js/edjaya-ui.js')) : time() }}"></script>
  @stack('scripts')

  <script>
    const logoutButton = document.getElementById('logoutBtn');
    const logoutForm = document.getElementById('logoutForm');
    const fullscreenButton = document.getElementById('fullscreenBtn');
    const fullscreenShell = document.getElementById('posFullscreenShell');
    const fullscreenFrame = document.getElementById('posFullscreenFrame');
    const runsInsidePosShell = window.self !== window.top;
    let fullscreenExitUrl = null;

    function setFullscreenButton(active) {
      fullscreenButton?.setAttribute('aria-pressed', String(active));
      fullscreenButton?.setAttribute('aria-label', active ? 'Keluar dari layar penuh' : 'Aktifkan layar penuh');
      const icon = fullscreenButton?.querySelector('i');
      if (icon) {
        icon.className = active ? 'bx bx-exit-fullscreen' : 'bx bx-fullscreen';
      }
    }

    function showFullscreenError() {
      const banner = document.querySelector('[data-page-error]');
      const message = banner?.querySelector('[data-error-message]');
      if (banner && message) {
        message.textContent = 'Mode layar penuh tidak tersedia pada perangkat ini.';
        banner.hidden = false;
      }
    }

    if (runsInsidePosShell) {
      setFullscreenButton(true);
      document.querySelectorAll('[data-leave-pos]').forEach((link) => {
        link.target = '_top';
      });
      if (logoutForm) {
        logoutForm.target = '_top';
      }
    }

    logoutButton?.addEventListener('click', () => {
      bootstrap.Modal.getOrCreateInstance(document.getElementById('logoutModal')).show();
    });

    fullscreenButton?.addEventListener('click', async () => {
      if (runsInsidePosShell) {
        window.parent.postMessage({
          type: 'edjaya-exit-pos-fullscreen',
          url: window.location.href,
          state: window.getPosFullscreenState?.() ?? null
        }, window.location.origin);
        return;
      }

      try {
        if (document.fullscreenElement) {
          await document.exitFullscreen();
          return;
        }

        const currentPosState = window.getPosFullscreenState?.();
        if (currentPosState) {
          sessionStorage.setItem('edjaya-pos-fullscreen-cart', JSON.stringify({
            url: window.location.href,
            state: currentPosState
          }));
        }

        fullscreenFrame.src = window.location.href;
        fullscreenShell.hidden = false;
        await document.documentElement.requestFullscreen();
        fullscreenFrame.focus();
      } catch (error) {
        fullscreenShell.hidden = true;
        sessionStorage.removeItem('edjaya-pos-fullscreen-cart');
        fullscreenFrame.src = 'about:blank';
        showFullscreenError();
      }
    });

    window.addEventListener('message', async (event) => {
      if (
        event.origin !== window.location.origin ||
        event.source !== fullscreenFrame.contentWindow ||
        event.data?.type !== 'edjaya-exit-pos-fullscreen'
      ) {
        return;
      }

      const requestedUrl = new URL(event.data.url, window.location.origin);
      if (requestedUrl.origin === window.location.origin && requestedUrl.pathname.startsWith('/pos')) {
        fullscreenExitUrl = requestedUrl.href;

        if (event.data.state) {
          sessionStorage.setItem('edjaya-pos-fullscreen-cart', JSON.stringify({
            url: fullscreenExitUrl,
            state: event.data.state
          }));
        }
      }

      if (document.fullscreenElement) {
        await document.exitFullscreen();
      }
    });

    document.addEventListener('fullscreenchange', () => {
      if (runsInsidePosShell) {
        return;
      }

      const active = Boolean(document.fullscreenElement);
      setFullscreenButton(active);

      if (!active && !fullscreenShell.hidden) {
        fullscreenShell.hidden = true;
        fullscreenFrame.src = 'about:blank';

        if (fullscreenExitUrl) {
          const destination = fullscreenExitUrl;
          fullscreenExitUrl = null;
          window.location.href = destination;
        }
      }
    });
  </script>
</body>
</html>
