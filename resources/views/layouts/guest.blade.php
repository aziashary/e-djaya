<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="theme-color" content="#b42318">
  <meta name="mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="default">
  <meta name="apple-mobile-web-app-title" content="e-Djaya">
  <title>e-Djaya</title>
  <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}">
  <link rel="manifest" href="{{ asset('assets/img/favicon/site.webmanifest') }}">
  <link rel="apple-touch-icon" href="{{ asset('assets/img/favicon/favicon-192x192.png') }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('assets/css/auth.css') }}?v={{ file_exists(public_path('assets/css/auth.css')) ? filemtime(public_path('assets/css/auth.css')) : time() }}">
  <link rel="stylesheet" href="{{ asset('assets/css/edjaya-ui.css') }}?v={{ file_exists(public_path('assets/css/edjaya-ui.css')) ? filemtime(public_path('assets/css/edjaya-ui.css')) : time() }}">
  @vite(['resources/js/app.js'])
</head>
<body>
  <a class="skip-link" href="#main-content">Lewati ke formulir</a>
  <main id="main-content" class="auth-page" tabindex="-1">
    <div class="auth-frame">
      <aside class="auth-brand" aria-label="Tentang e-Djaya">
        <a href="{{ route('login') }}" aria-label="e-Djaya, buka halaman masuk">
          <img src="{{ asset('assets/img/logo 1 transparan.png') }}" alt="Logo e-Djaya" class="auth-brand-logo" width="150" height="52">
        </a>
        <div>
          <h1>Kerja toko dalam satu alur.</h1>
          <p>Kelola transaksi, barang, pengguna, dan laporan dari ruang kerja e-Djaya.</p>
        </div>
        <small>Djaya 590</small>
      </aside>

      <section class="auth-card">
        {{ $slot }}
      </section>
    </div>
  </main>
  <script>
    if ('serviceWorker' in navigator) {
      window.addEventListener('load', () => {
        navigator.serviceWorker.register('{{ asset('sw.js') }}?v={{ file_exists(public_path('sw.js')) ? filemtime(public_path('sw.js')) : time() }}').catch(() => {});
      });
    }
  </script>
</body>
</html>
