@extends('layouts.main')

@section('judul', 'Laporan | e-Djaya')

@section('content')
<section aria-labelledby="reports-title">
  <header class="page-header">
    <div class="page-heading">
      <p class="page-kicker">Analisis operasional</p>
      <h1 id="reports-title" class="page-title">Laporan</h1>
      <p class="page-description">Pilih laporan berdasarkan keputusan yang ingin diperiksa.</p>
    </div>
  </header>

  <div class="card task-panel">
    <div class="list-group list-group-flush">
      <a href="{{ route('laporan.keuangan') }}" class="list-group-item list-group-item-action p-4">
        <strong class="d-block mb-1">Laporan keuangan</strong>
        <span class="text-muted">Periksa omzet, transaksi, tunai, dan QRIS berdasarkan periode.</span>
      </a>
      <a href="{{ route('laporan.produk') }}" class="list-group-item list-group-item-action p-4">
        <strong class="d-block mb-1">Laporan produk</strong>
        <span class="text-muted">Lihat produk serta kategori yang paling banyak terjual.</span>
      </a>
      <a href="{{ route('laporan.transaksi') }}" class="list-group-item list-group-item-action p-4">
        <strong class="d-block mb-1">Riwayat transaksi</strong>
        <span class="text-muted">Cari transaksi, buka detail struk, cetak, atau hapus transaksi.</span>
      </a>
    </div>
  </div>
</section>
@endsection
