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
  <a class="skip-link" href="#main-content">Lewati ke konten utama</a>
  <div class="app-frame">
    @include('layouts.navigation')

    @isset($header)
      <header class="page-heading-tailwind">
        <div>{{ $header }}</div>
      </header>
    @endisset

    <main id="main-content" class="app-main-tailwind" tabindex="-1">
      {{ $slot }}
    </main>
  </div>
</body>
</html>
