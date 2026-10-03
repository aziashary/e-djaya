@extends('layouts.main')

@section('judul', 'Dashboard e-Djaya')

@section('content')
@php
  $level = strtolower((string) auth()->user()->level);
  $isAdmin = $level === 'admin';
  $historyRoute = $isAdmin ? route('laporan.transaksi') : route('pos.riwayat');
@endphp

<section aria-labelledby="dashboard-title">
  <header class="page-header">
    <div class="page-heading">
      <p class="page-kicker">{{ now()->translatedFormat('l, d F Y') }}</p>
      <h1 id="dashboard-title" class="page-title">Dashboard operasional</h1>
      <p class="page-description">Ringkasan transaksi yang tercatat pada akun dan toko Anda.</p>
    </div>
    <div class="page-actions">
      <a href="{{ route('pos.index') }}" class="btn btn-primary">
        <i class="bx bx-cart me-1" aria-hidden="true"></i>
        Mulai transaksi
      </a>
    </div>
  </header>

  @if($isAdmin)
    <div class="metric-grid" aria-label="Ringkasan penjualan admin">
      <article class="metric-card metric-card--primary metric-card--wide">
        <p class="metric-label">Omzet hari ini, semua toko</p>
        <p class="metric-value">Rp {{ number_format($nilai_hari_ini, 0, ',', '.') }}</p>
        <p class="metric-note">Gabungan transaksi Warkop Djaya dan Ranu</p>
      </article>
      <article class="metric-card">
        <p class="metric-label">Warkop Djaya hari ini</p>
        <p class="metric-value">Rp {{ number_format($nilai_hari_ini_warkop, 0, ',', '.') }}</p>
      </article>
      <article class="metric-card">
        <p class="metric-label">Ranu hari ini</p>
        <p class="metric-value">Rp {{ number_format($nilai_hari_ini_ranu, 0, ',', '.') }}</p>
      </article>
      <article class="metric-card metric-card--wide">
        <p class="metric-label">Omzet bulan ini, semua toko</p>
        <p class="metric-value">Rp {{ number_format($nilai_bulan_ini, 0, ',', '.') }}</p>
      </article>
      <article class="metric-card">
        <p class="metric-label">Warkop Djaya bulan ini</p>
        <p class="metric-value">Rp {{ number_format($nilai_bulan_ini_warkop, 0, ',', '.') }}</p>
      </article>
      <article class="metric-card">
        <p class="metric-label">Ranu bulan ini</p>
        <p class="metric-value">Rp {{ number_format($nilai_bulan_ini_ranu, 0, ',', '.') }}</p>
      </article>
    </div>
  @else
    <div class="metric-grid" aria-label="Ringkasan penjualan toko">
      <article class="metric-card metric-card--primary metric-card--wide">
        <p class="metric-label">Omzet hari ini</p>
        <p class="metric-value">Rp {{ number_format($nilai_hari_ini, 0, ',', '.') }}</p>
        <p class="metric-note">{{ number_format($transaksi_hari_ini, 0, ',', '.') }} transaksi tercatat</p>
      </article>
      <article class="metric-card">
        <p class="metric-label">Transaksi hari ini</p>
        <p class="metric-value">{{ number_format($transaksi_hari_ini, 0, ',', '.') }}</p>
      </article>
      <article class="metric-card">
        <p class="metric-label">Transaksi bulan ini</p>
        <p class="metric-value">{{ number_format($transaksi_bulan_ini, 0, ',', '.') }}</p>
      </article>
      <article class="metric-card metric-card--wide">
        <p class="metric-label">Omzet bulan ini</p>
        <p class="metric-value">Rp {{ number_format($nilai_bulan_ini, 0, ',', '.') }}</p>
      </article>
    </div>
  @endif

  <section class="card task-panel" aria-labelledby="recent-title">
    <div class="card-header d-flex align-items-center justify-content-between gap-3">
      <div>
        <h2 id="recent-title" class="h5 mb-1 fw-bold">Transaksi terbaru</h2>
        <p class="mb-0 text-muted small">Lima transaksi terakhir yang dapat diakses akun ini.</p>
      </div>
      <a href="{{ $historyRoute }}" class="btn btn-outline-secondary">Lihat riwayat</a>
    </div>
    <div class="table-responsive" tabindex="0" aria-label="Tabel transaksi terbaru, geser jika diperlukan">
      <table class="table align-middle">
        <thead>
          <tr>
            <th scope="col">Kode</th>
            <th scope="col">Waktu</th>
            <th scope="col" class="text-end">Total</th>
            <th scope="col">Kasir</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($transaksi_terbaru as $t)
            <tr>
              <td><span class="transaction-code">{{ $t->kode_transaksi }}</span></td>
              <td>{{ $t->tanggal->translatedFormat('d M Y, H:i') }}</td>
              <td class="text-end fw-bold">Rp {{ number_format($t->total, 0, ',', '.') }}</td>
              <td>{{ $t->kasir->username ?? 'Tidak tersedia' }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="4" class="table-empty">
                <strong>Belum ada transaksi</strong>
                Mulai transaksi baru untuk menampilkan riwayat di sini.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </section>
</section>
@endsection
