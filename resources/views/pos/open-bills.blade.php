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
                    <button
                      type="button"
                      class="btn btn-sm btn-outline-primary btn-detail"
                      data-kode="{{ $bill->kode_transaksi }}"
                      aria-label="Buka detail transaksi {{ $bill->kode_transaksi }}"
                    >Detail</button>
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
document.querySelectorAll('.btn-cancel-bill').forEach((button) => {
  button.addEventListener('click', () => {
    document.getElementById('cancelBillForm').action = button.dataset.cancelUrl;
    document.getElementById('cancelBillName').textContent = button.dataset.billLabel;
  });
});

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
    url: `/pos/detail/${code}`,
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
</script>
@endpush
