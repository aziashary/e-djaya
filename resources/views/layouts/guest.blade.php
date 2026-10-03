<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="theme-color" content="#fff8f3">
  <title>e-Djaya</title>
  <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
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
</body>
</html>
