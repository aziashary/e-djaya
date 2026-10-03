<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="theme-color" content="#fff8f3">
  <title>e-Djaya</title>
  <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
  <a class="skip-link" href="#main-content">Lewati ke konten utama</a>
  <main id="main-content" class="auth-page" tabindex="-1">
    <div class="auth-frame">
      <aside class="auth-brand" aria-label="Tentang e-Djaya">
        <img src="{{ asset('assets/img/logo 1 transparan.png') }}" alt="Logo e-Djaya" class="auth-brand-logo" width="150" height="52">
        <div>
          <h1>Kerja toko dalam satu alur.</h1>
          <p>e-Djaya menghubungkan transaksi, barang, pengguna, dan laporan.</p>
        </div>
        <small>Djaya 590</small>
      </aside>

      <section class="auth-card">
        <h2 class="auth-title">e-Djaya</h2>
        <p class="auth-copy">Masuk untuk membuka area kerja yang sesuai dengan akun Anda.</p>
        <div class="form-actions mt-6 justify-start">
          @auth
            <a href="{{ route('dashboard') }}" class="ui-button-primary">Buka dashboard</a>
            <a href="{{ route('pos.index') }}" class="ui-button-secondary">Buka POS</a>
          @else
            <a href="{{ route('login') }}" class="ui-button-primary">Masuk ke akun</a>
          @endauth
        </div>
      </section>
    </div>
  </main>
</body>
</html>
