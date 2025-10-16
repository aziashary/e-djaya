@extends('layouts.main')

@section('judul', 'Laporan')

@section('content')
<div class="container-fluid">
  <!-- Header -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">📊 Laporan</h4>
  </div>

  <!-- Nav Tabs -->
  <ul class="nav nav-tabs mb-4" id="laporanTabs" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link active" id="laporan-keuangan-tab" data-bs-toggle="tab" data-bs-target="#laporan-keuangan" type="button" role="tab">
        💰 Laporan Keuangan
      </button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="laporan-produk-tab" data-bs-toggle="tab" data-bs-target="#laporan-produk" type="button" role="tab">
        📦 Laporan Produk
      </button>
    </li>
  </ul>

  <div class="tab-content" id="laporanTabsContent">
    <!-- Laporan Keuangan -->
    <div class="tab-pane fade show active" id="laporan-keuangan" role="tabpanel">
      @include('laporan.partials.keuangan')
    </div>

    <!-- Laporan Produk -->
    <div class="tab-pane fade" id="laporan-produk" role="tabpanel">
      @include('laporan.partials.produk')
    </div>
  </div>
</div>
@endsection
