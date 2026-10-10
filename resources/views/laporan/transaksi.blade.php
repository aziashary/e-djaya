@extends('layouts.main')

@section('judul', 'Riwayat Transaksi | e-Djaya')

@section('content')
<section aria-labelledby="transaction-report-title">
  <header class="page-header">
    <div class="page-heading">
      <p class="page-kicker">Analisis operasional</p>
      <h1 id="transaction-report-title" class="page-title">Riwayat transaksi</h1>
      <p class="page-description">Daftar transaksi terbaru diurutkan dari yang paling baru. Gunakan filter tanggal atau pencarian jika diperlukan.</p>
  </header>

  <form class="card card-body mb-4" method="GET" action="{{ route('laporan.transaksi') }}">
    <div class="row g-3 align-items-end">
      <div class="col-lg-4">
        <label for="tanggal" class="form-label">Filter tanggal transaksi</label>
        <input type="text" id="tanggal" name="tanggal" value="{{ $tanggal ?? '' }}" class="form-control" placeholder="Pilih tanggal (kosongkan untuk semua)" readonly>
      </div>
      <div class="col-lg-5">
        <label for="search" class="form-label">Kode transaksi atau kasir</label>
        <input type="search" id="search" name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari kode transaksi atau kasir" autocomplete="off">
      </div>
      <div class="col-lg-3 d-flex flex-wrap gap-2">
        <button type="submit" class="btn btn-primary flex-grow-1">Filter</button>
        @if(!empty($tanggal) || request('search'))
          <a href="{{ route('laporan.transaksi') }}" class="btn btn-outline-secondary">Reset</a>
        @endif
      </div>
    </div>
  </form>

  @if(!empty($tanggal) || request('search'))
    <div class="alert alert-info mb-3" role="status">
      @if(!empty($tanggal))
        <strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}
      @endif
      @if(request('search'))
        <span class="@if(!empty($tanggal)) d-block mt-1 @endif"><strong>Pencarian:</strong> {{ request('search') }}</span>
      @endif
    </div>
  @endif

  <div class="card task-panel">
    <div class="card-header">
      <h2 class="h5 fw-bold mb-1">Daftar transaksi</h2>
      <p class="small text-muted mb-0">
        Menampilkan {{ $laporan->firstItem() ?? 0 }}–{{ $laporan->lastItem() ?? 0 }} dari {{ number_format($laporan->total(), 0, ',', '.') }} transaksi {{ !empty($tanggal) ? 'pada tanggal ' . \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') : 'terbaru' }}.
      </p>
    </div>

    @if($laporan->isEmpty())
      <div class="empty-state" role="status">
        <strong>{{ $laporan->total() > 0 ? 'Halaman transaksi kosong' : 'Transaksi tidak ditemukan' }}</strong>
        @if($laporan->total() > 0)
          <a href="{{ $laporan->url(1) }}">Kembali ke halaman pertama</a> untuk melihat transaksi.
        @elseif(!empty($tanggal) || request('search'))
          Ubah filter tanggal atau kata pencarian untuk melihat hasil lain.
        @else
          Belum ada transaksi yang tercatat di sistem.
        @endif
      </div>
    @else
      <div class="table-responsive" tabindex="0" aria-label="Tabel riwayat transaksi, geser jika diperlukan">
        <table class="table history-table align-middle w-100">
          <thead>
            <tr>
              <th scope="col">Tanggal</th>
              <th scope="col">Kode</th>
              <th scope="col" class="text-end">Total</th>
              <th scope="col">Kasir dan toko</th>
              <th scope="col" class="text-end">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @foreach($laporan as $item)
              @php $cashierLevel = strtolower((string) ($item->kasir->level ?? '')); @endphp
              <tr>
                <td data-label="Tanggal">{{ $item->tanggal->format('d/m/Y H:i') }}</td>
                <td data-label="Kode"><span class="transaction-code">{{ $item->kode_transaksi }}</span></td>
                <td data-label="Total" class="text-end fw-bold">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                <td data-label="Kasir dan toko">
                  <span class="d-block">{{ $item->kasir->username ?? 'Tidak tersedia' }}</span>
                  @if($item->kasir)
                    <span class="badge {{ $cashierLevel === 'staff' ? 'bg-success' : 'bg-label-primary' }}">{{ $cashierLevel === 'staff' ? 'Ranu' : 'Warkop' }}</span>
                  @endif
                </td>
                <td data-label="Aksi" class="text-end">
                  <div class="d-inline-flex flex-wrap justify-content-end gap-2">
                    <button type="button" class="btn btn-sm btn-outline-primary btn-detail" data-kode="{{ $item->kode_transaksi }}" aria-label="Buka detail transaksi {{ $item->kode_transaksi }}">Detail</button>
                    <form action="{{ route('pos.destroy', $item->id) }}" method="POST" data-no-loading="true" onsubmit="return confirm('Hapus transaksi ini?')">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                    </form>
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      @include('components.history-pagination', ['paginator' => $laporan])
    @endif
  </div>
</section>

<div class="modal fade receipt-modal" id="modalDetail" tabindex="-1" aria-labelledby="modalDetailLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered receipt-modal-dialog">
    <div class="modal-content receipt-paper-modal">
      <div class="modal-header receipt-modal-header">
        <div>
          <span class="receipt-badge">Salinan struk</span>
          <h2 class="modal-title visually-hidden" id="modalDetailLabel">Detail struk</h2>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup detail struk"></button>
      </div>
      <div class="modal-body receipt-modal-body">
        <div id="strukBody" class="receipt-paper-sheet" role="status" aria-live="polite">
          <div class="receipt-status-msg">Memuat detail transaksi...</div>
        </div>
      </div>
      <div class="modal-footer receipt-modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
        <button type="button" id="btnPrintStruk" class="btn btn-primary">Cetak struk</button>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
  const tanggalInput = document.getElementById('tanggal');
  if (tanggalInput) {
    new Litepicker({
      element: tanggalInput,
      singleMode: true,
      numberOfMonths: 1,
      numberOfColumns: 1,
      format: 'YYYY-MM-DD',
      autoApply: true,
      lang: 'id-ID',
      allowRepick: true,
      resetButton: true,
      maxDate: '{{ now()->format('Y-m-d') }}'
    });
  }


  const formatRupiah = (val) => 'Rp ' + Number(val || 0).toLocaleString('id-ID');

  function escapeHtml(str) {
    return String(str || '')
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#39;');
  }

  $(document).on('click', '.btn-detail', function() {
    const code = $(this).data('kode');
    const body = $('#strukBody');
    body.html('<div class="receipt-status-msg">Memuat detail transaksi...</div>');
    $('#btnPrintStruk').attr('data-kode', code).data('kode', code);
    bootstrap.Modal.getOrCreateInstance(document.getElementById('modalDetail')).show();

    $.ajax({
      url: `/laporan/detail/${code}`,
      type: 'GET',
      success: (response) => response.status ? renderReceipt(response.data) : body.html('<div class="receipt-status-msg receipt-error">Data transaksi tidak ditemukan.</div>'),
      error: () => body.html('<div class="receipt-status-msg receipt-error">Detail transaksi gagal dimuat. Tutup dialog, lalu coba lagi.</div>')
    });
  });

  function renderReceipt(data) {
    const isStaff = String(data.level || '').toLowerCase() === 'staff';
    const storeName = isStaff ? 'Kopi Ranu' : 'Warkop Djaya 590';
    const storeAddress = isStaff
      ? 'Jl. Raya Puncak - Gadog, Tugu Selatan, Bogor'
      : 'Jln Raya Puncak No. 590';

    const orderType = data.makan_dimana === 'Takeaway' ? 'Dibawa pulang' : 'Makan di tempat';
    const isPending = String(data.status || '').toLowerCase() === 'pending';
    const isCash = String(data.metode_pembayaran || '').toLowerCase() === 'cash';
    const paymentMethod = isPending
      ? 'Belum dibayar'
      : (isCash ? 'Tunai (Cash)' : String(data.metode_pembayaran || '-').toUpperCase());
    const statusHtml = isPending
      ? `
        <div class="receipt-meta-row">
          <span class="receipt-meta-label">Status transaksi</span>
          <span class="receipt-meta-val">Open bill</span>
        </div>
      `
      : '';

    let itemsHtml = '';
    (data.items || []).forEach((item) => {
      const itemSubtotal = item.subtotal || (item.qty * item.harga);
      itemsHtml += `
        <div class="receipt-item-row">
          <div class="receipt-item-name">${escapeHtml(item.nama)}</div>
          <div class="receipt-item-calc">
            <span class="receipt-item-qty">${item.qty} x ${formatRupiah(item.harga)}</span>
            <span class="receipt-item-subtotal">${formatRupiah(itemSubtotal)}</span>
          </div>
        </div>
      `;
    });

    const subtotal = Number(data.subtotal || 0) || Number(data.total || 0);
    const discount = Number(data.diskon || 0);
    const total = Number(data.total || 0);

    let discountHtml = '';
    if (discount > 0) {
      const discountAmount = subtotal * (discount / 100);
      discountHtml = `
        <div class="receipt-calc-row">
          <span>Subtotal</span>
          <span>${formatRupiah(subtotal)}</span>
        </div>
        <div class="receipt-calc-row receipt-calc-discount">
          <span>Diskon (${discount}%)</span>
          <span>-${formatRupiah(discountAmount)}</span>
        </div>
      `;
    }

    let noteHtml = '';
    if (data.catatan && String(data.catatan).trim()) {
      noteHtml = `
        <div class="receipt-divider"></div>
        <div class="receipt-note-box">
          <span class="receipt-note-label">Catatan:</span>
          <span class="receipt-note-text">${escapeHtml(String(data.catatan).trim())}</span>
        </div>
      `;
    }

    const html = `
      <div class="receipt-header-center">
        <div class="receipt-brand-title">${escapeHtml(storeName)}</div>
        <div class="receipt-brand-address">${escapeHtml(storeAddress)}</div>
      </div>

      <div class="receipt-divider"></div>

      <div class="receipt-meta-grid">
        <div class="receipt-meta-row">
          <span class="receipt-meta-label">No. Transaksi</span>
          <span class="receipt-meta-val receipt-code">${escapeHtml(data.kode)}</span>
        </div>
        ${statusHtml}
        <div class="receipt-meta-row">
          <span class="receipt-meta-label">Waktu</span>
          <span class="receipt-meta-val">${escapeHtml(data.tanggal)}</span>
        </div>
        <div class="receipt-meta-row">
          <span class="receipt-meta-label">Kasir</span>
          <span class="receipt-meta-val">${escapeHtml(data.kasir)}</span>
        </div>
        ${data.nama_customer && data.nama_customer !== '-' ? `
          <div class="receipt-meta-row">
            <span class="receipt-meta-label">Pelanggan</span>
            <span class="receipt-meta-val">${escapeHtml(data.nama_customer)}</span>
          </div>
        ` : ''}
        <div class="receipt-meta-row">
          <span class="receipt-meta-label">Pesanan</span>
          <span class="receipt-meta-val">${escapeHtml(orderType)}</span>
        </div>
      </div>

      <div class="receipt-divider"></div>

      <div class="receipt-items-list">
        ${itemsHtml}
      </div>

      <div class="receipt-divider"></div>

      <div class="receipt-totals-block">
        ${discountHtml}
        <div class="receipt-total-main-row">
          <span>TOTAL</span>
          <span class="receipt-total-value">${formatRupiah(total)}</span>
        </div>
        <div class="receipt-calc-row receipt-payment-row">
          <span>Metode Bayar</span>
          <span class="receipt-payment-badge">${escapeHtml(paymentMethod)}</span>
        </div>
      </div>

      ${noteHtml}

      <div class="receipt-divider"></div>

      <div class="receipt-footer-center">
        <p class="receipt-footer-thanks">Terima kasih atas kunjungan Anda!</p>
        <p class="receipt-footer-sub">${isPending ? 'Pembayaran belum diselesaikan' : 'Simpan struk ini sebagai bukti pembayaran'}</p>
      </div>
    `;

    $('#strukBody').html(html);
  }

  $('#btnPrintStruk').on('click', function() {
    const code = $(this).data('kode');
    if (!code) {
      $('#strukBody').text('Kode transaksi tidak tersedia. Tutup dialog, lalu coba lagi.');
      return;
    }
    window.open(`/pos/print/${code}`, '_blank');
  });
});
</script>
@endpush
