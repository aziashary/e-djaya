@extends('layouts.pos')

@section('judul', 'Transaksi Selesai | e-Djaya')

@section('content')
<section class="transaction-success" aria-labelledby="success-title">
  <header class="text-center mb-4">
    <span class="success-mark" aria-hidden="true"><i class="bx bx-check"></i></span>
    <h1 id="success-title" class="page-title mt-3">Transaksi tersimpan</h1>
    <p class="page-description mx-auto">Struk dan detail transaksi sudah tersedia.</p>
  </header>

  <div class="payment-total mb-4">
    <span>Total pembayaran</span>
    <strong>Rp {{ number_format($transaksi->total, 0, ',', '.') }}</strong>
  </div>

  <dl class="row g-2 mb-4">
    <dt class="col-sm-4 text-muted">Kode transaksi</dt>
    <dd class="col-sm-8 fw-bold transaction-code">{{ $transaksi->kode_transaksi }}</dd>
    <dt class="col-sm-4 text-muted">Metode</dt>
    <dd class="col-sm-8">{{ $transaksi->metode_pembayaran === 'cash' ? 'Tunai' : 'QRIS' }}</dd>
    <dt class="col-sm-4 text-muted">Kasir</dt>
    <dd class="col-sm-8">{{ $transaksi->kasir->name ?? 'Tidak tersedia' }}</dd>
    <dt class="col-sm-4 text-muted">Waktu</dt>
    <dd class="col-sm-8">{{ $transaksi->tanggal->translatedFormat('d M Y, H:i') }}</dd>
    @if(!empty($transaksi->nama_customer))
      <dt class="col-sm-4 text-muted">Pelanggan</dt>
      <dd class="col-sm-8">{{ $transaksi->nama_customer }}</dd>
    @endif
  </dl>

  @if(!empty($transaksi->catatan))
    <div class="alert alert-warning" role="note">
      <strong>Catatan pesanan:</strong> {{ $transaksi->catatan }}
    </div>
  @endif

  <div class="table-responsive" tabindex="0" aria-label="Rincian produk transaksi">
    <table class="table align-middle">
      <thead>
        <tr>
          <th scope="col">Produk</th>
          <th scope="col" class="text-center">Jumlah</th>
          <th scope="col" class="text-end">Subtotal</th>
        </tr>
      </thead>
      <tbody>
        @forelse($transaksi->items as $item)
          <tr>
            <td>{{ $item->nama ?? $item->nama_barang }}</td>
            <td class="text-center">{{ $item->qty }}</td>
            <td class="text-end fw-bold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
          </tr>
        @empty
          <tr><td colspan="3" class="table-empty">Rincian produk tidak tersedia.</td></tr>
        @endforelse
      </tbody>
      <tfoot>
        <tr>
          <th colspan="2">Subtotal</th>
          <th class="text-end">Rp {{ number_format($transaksi->items->sum('subtotal'), 0, ',', '.') }}</th>
        </tr>
        @if(!empty($transaksi->diskon) && $transaksi->diskon > 0)
          <tr>
            <th colspan="2">Diskon</th>
            <th class="text-end">{{ number_format($transaksi->diskon, 1, ',', '.') }}%</th>
          </tr>
        @endif
        <tr>
          <th colspan="2">Total akhir</th>
          <th class="text-end text-primary">Rp {{ number_format($transaksi->total, 0, ',', '.') }}</th>
        </tr>
      </tfoot>
    </table>
  </div>

  <div class="page-actions justify-content-center mt-4">
    <a href="{{ route('pos.index') }}" class="btn btn-outline-secondary">Transaksi baru</a>
    <a href="{{ route('pos.print', $transaksi->kode_transaksi) }}" target="_blank" rel="noopener" class="btn btn-primary">Cetak ulang struk</a>
  </div>
</section>
@endsection
