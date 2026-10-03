@extends('layouts.pos')

@section('judul', 'Open Bill | e-Djaya')

@section('content')
<section aria-labelledby="open-bill-title">
  <header class="page-header">
    <div class="page-heading">
      <p class="page-kicker">Pesanan aktif</p>
      <h1 id="open-bill-title" class="page-title">Open bill</h1>
      <p class="page-description">Lanjutkan pesanan yang belum dibayar, selesaikan pembayaran, atau batalkan bill yang tidak dipakai.</p>
    </div>
    <div class="page-actions">
      <a href="{{ route('pos.index') }}" class="btn btn-primary">
        <i class="bx bx-plus" aria-hidden="true"></i>
        Transaksi baru
      </a>
    </div>
  </header>

  <div class="card task-panel">
    <div class="card-header">
      <h2 class="h5 fw-bold mb-1">Bill aktif</h2>
      <p class="text-muted small mb-0">{{ $openBills->count() }} bill menunggu pembayaran.</p>
    </div>

    @if($openBills->isEmpty())
      <div class="empty-state" role="status">
        <strong>Belum ada open bill</strong>
        Simpan keranjang dari halaman POS jika pelanggan ingin membayar nanti.
        <a href="{{ route('pos.index') }}" class="btn btn-primary mt-3">Mulai transaksi</a>
      </div>
    @else
      <div class="table-responsive" tabindex="0" aria-label="Daftar open bill, geser jika diperlukan">
        <table class="table align-middle mb-0">
          <thead>
            <tr>
              <th scope="col">Pelanggan atau meja</th>
              <th scope="col">Dibuka</th>
              <th scope="col">Pesanan</th>
              <th scope="col">Kasir</th>
              <th scope="col" class="text-end">Total</th>
              <th scope="col" class="text-end">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @foreach($openBills as $bill)
              <tr>
                <td>
                  <strong class="d-block">{{ $bill->nama_customer }}</strong>
                  <span class="transaction-code">{{ $bill->kode_transaksi }}</span>
                </td>
                <td>{{ $bill->tanggal->translatedFormat('d M Y, H:i') }}</td>
                <td>
                  {{ $bill->items->sum('qty') }} item
                  <span class="d-block text-muted small">{{ $bill->makan_dimana === 'Takeaway' ? 'Dibawa pulang' : 'Makan di tempat' }}</span>
                </td>
                <td>{{ $bill->kasir->name ?? 'Tidak tersedia' }}</td>
                <td class="text-end fw-bold">Rp {{ number_format($bill->total, 0, ',', '.') }}</td>
                <td>
                  <div class="d-flex flex-wrap justify-content-end gap-2">
                    <a href="{{ route('pos.index', ['bill' => $bill->kode_transaksi]) }}" class="btn btn-sm btn-outline-secondary">Lanjutkan</a>
                    <a href="{{ route('pos.index', ['bill' => $bill->kode_transaksi, 'pay' => 1]) }}" class="btn btn-sm btn-primary">Bayar</a>
                    <button
                      type="button"
                      class="btn btn-sm btn-outline-danger btn-cancel-bill"
                      data-bs-toggle="modal"
                      data-bs-target="#cancelBillModal"
                      data-cancel-url="{{ route('pos.open-bills.cancel', $bill->kode_transaksi) }}"
                      data-bill-label="{{ e($bill->nama_customer) }}"
                    >Batalkan</button>
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @endif
  </div>
</section>

<div class="modal fade" id="cancelBillModal" tabindex="-1" aria-labelledby="cancelBillModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title fs-5 fw-bold" id="cancelBillModalLabel">Batalkan open bill?</h2>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup dialog"></button>
      </div>
      <div class="modal-body">
        <p class="mb-0">Bill untuk <strong id="cancelBillName"></strong> akan dipindahkan dari daftar aktif. Tindakan ini tidak dapat dibatalkan.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Kembali</button>
        <form id="cancelBillForm" method="POST">
          @csrf
          <button type="submit" class="btn btn-danger">Batalkan bill</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.btn-cancel-bill').forEach((button) => {
  button.addEventListener('click', () => {
    document.getElementById('cancelBillForm').action = button.dataset.cancelUrl;
    document.getElementById('cancelBillName').textContent = button.dataset.billLabel;
  });
});
</script>
@endpush
