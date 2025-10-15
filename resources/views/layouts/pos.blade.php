<!doctype html>
<html lang="en" class="layout-compact" data-assets-path="{{ asset('assets/') }}/">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no" />
    <title>@yield('judul', 'POS')</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;600;700&display=swap" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/iconify-icons.css') }}" />
    @stack('css')

    <style>
      body {
        background: #f7f7f7;
      }

      .pos-wrapper {
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        position: relative;
        padding: 0.2rem;
      }

            /* ====== Header Actions ====== */
      .header-actions {
        display: flex;
        align-items: center;
        gap: 1rem;
      }

      .action-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: #f7f8fa;
        color: #555;
        font-size: 1.3rem;
        cursor: pointer;
        transition: all 0.2s ease;
        border: none;
      }

      .action-btn:hover {
        background: #007bff;
        color: #fff;
        transform: scale(1.05);
      }

      .logout-btn {
        color: #ff4d4f !important;
      }

      .logout-btn:hover {
        background: #ff4d4f !important;
        color: #fff !important;
      }


      /* Header POS */
      .pos-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #fff;
        border-radius: 12px;
         padding: 0.5rem 1rem;      /* lebih tipis */
        margin-bottom: 1rem;       /* jarak bawah dikit aja */
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
      }

      .pos-header .brand {
        display: flex;
        align-items: center;
        gap: 0.8rem;
      }

      .pos-header img {
        width: 65px;               /* logo mengecil */
        height: 32px;
        border-radius: 8px;
      }

      .pos-header h4 {
        margin: 0;
        font-weight: 700;
        color: #222;
      }

      .pos-header small {
        display: block;
        color: #666;
        font-weight: 500;
        font-size: 0.85rem;
      }

      /* Tombol logout */
      .logout-btn {
        background: #fff;
        border: 1px solid #eee;
        border-radius: 50%;
        width: 42px;
        height: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #555;
        cursor: pointer;
        transition: 0.25s;
      }

      .logout-btn:hover {
        background: #dc3545;
        color: #fff;
      }
    </style>
  </head>

  <body>
    {{-- <div class="logout-btn" id="logoutBtn" title="Logout">
          <i class="bx bx-log-out"></i>
    </div> --}}
    <div class="pos-wrapper">
      <!-- Header POS -->
      <div class="pos-header">
        <div class="brand">
          <img src="{{ asset('assets/img/logo.png') }}" alt="Logo Toko">
          <div>
            <h4>Point Of Sales</h4>
            <small>Djaya 590</small>
          </div>
        </div>

        <div class="header-actions">
          <!-- Tombol Riwayat Transaksi -->
          <a href="" class="action-btn" title="Riwayat Transaksi">
            <i class="bx bx-history"></i>
          </a>

          <!-- Tombol Fullscreen -->
          <button id="fullscreenBtn" class="action-btn" title="Fullscreen">
            <i class="bx bx-fullscreen"></i>
          </button>

          <!-- Tombol Logout -->
          <div class="logout-btn action-btn" id="logoutBtn" title="Logout">
            <i class="bx bx-log-out"></i>
          </div>
        </div>
      </div>
    

      <!-- Modal Konfirmasi Logout -->
      <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title"><i class="bx bx-log-out-circle text-danger me-2"></i>Konfirmasi Logout</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
              <p>Beneran Cabut?</p>
            </div>
            <div class="modal-footer justify-content-center">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                <i class="bx bx-x"></i> Kedengan
              </button>
              <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-danger">
                  <i class="bx bx-log-out"></i> Iya
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>

      <!-- Konten POS -->
      <div class="content-wrapper">
        @yield('content')
      </div>
    </div>

    <!-- JS -->
    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    @stack('scripts')

    <script>
      // trigger modal konfirmasi logout
      document.getElementById('logoutBtn').addEventListener('click', function() {
        const modal = new bootstrap.Modal(document.getElementById('logoutModal'));
        modal.show();
      });

        // Toggle fullscreen
        const fullscreenBtn = document.getElementById('fullscreenBtn');
        fullscreenBtn.addEventListener('click', () => {
          if (!document.fullscreenElement) {
            document.documentElement.requestFullscreen();
            fullscreenBtn.innerHTML = '<i class="bx bx-exit-fullscreen"></i>';
          } else {
            document.exitFullscreen();
            fullscreenBtn.innerHTML = '<i class="bx bx-fullscreen"></i>';
          }
        });

    </script>
  </body>
</html>
