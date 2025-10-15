@extends('layouts.pos')

@section('judul', 'Transaksi Selesai')

@push('css')
<style>
.sukses-wrapper {
  max-width: 600px;
  margin: 0 auto;
  background: #fff;
  border-radius: 10px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.08);
  padding: 2rem;
  text-align: center;
}
.sukses-header h3 {
  font-weight: 700;
  color: #222;
}
.sukses-total {
  font-size: 2.2rem;
  font-weight: 700;
  color: #af3f3f;
  margin: 1rem 0;
}
.table {
  margin-top: 1.5rem;
}
.table th, .table td {
  font-size: 0.9rem;
  padding: .4rem .5rem;
}
.sukses-footer {
  margin-top: 1.5rem;
}
.btn-print {
  background-color: #af3f3f !important;
  border-color: #af3f3f !important;
  color: #fff;
}
.btn-print:hover {
  background-color: #922e2e !important;
  border-color: #922e2e !important;
}
</style>
@endpush

@section('content')
<div class="sukses-wrapper mt-4">
  <div class="sukses-header">
    <h3>Transaksi Berhasil 🎉</h3>
    <p class="text-muted mb-0">Kode Transaksi:</p>
    <h5 class="fw-bold">{{ $transaksi->kode_transaksi }}</h5>
  </div>

  <div class="sukses-total">
    Rp {{ number_format($transaksi->total, 0, ',', '.') }}
  </div>

  <div class="text-muted small mb-3">
    Metode: <strong class="text-uppercase">{{ $transaksi->metode_pembayaran }}</strong> <br>
    Kasir: {{ $transaksi->kasir->name ?? '-' }} <br>
    Tanggal: {{ $transaksi->tanggal->format('d/m/Y H:i') }}
  </div>

  <table class="table table-bordered align-middle">
    <thead class="table-light">
      <tr>
        <th>Barang</th>
        <th class="text-center" width="70">Qty</th>
        <th class="text-end" width="100">Subtotal</th>
      </tr>
    </thead>
    <tbody>
      @foreach($transaksi->items as $item)
      <tr>
        <td>{{ $item->nama }}</td>
        <td class="text-center">{{ $item->qty }}</td>
        <td class="text-end">{{ number_format($item->subtotal, 0, ',', '.') }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>

  <div class="sukses-footer d-flex justify-content-center gap-2 mt-3">
    <a href="{{ route('pos.index') }}" class="btn btn-secondary">
      <i class="bx bx-left-arrow-alt"></i> Kembali
    </a>
   <a href="{{ route('pos.print', $transaksi->kode_transaksi) }}" target="_blank" class="btn btn-print">
      <i class="bx bx-printer"></i> Print Ulang
   </a>
  </div>
</div>
@endsection
