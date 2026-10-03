@extends('layouts.pos')

@section('judul', 'Riwayat Transaksi | e-Djaya')

@section('content')
<section aria-labelledby="history-title">
  <header class="page-header">
    <div class="page-heading">
      <p class="page-kicker">Riwayat kasir</p>
      <h1 id="history-title" class="page-title">Transaksi sebelumnya</h1>
      <p class="page-description">Periksa transaksi berdasarkan rentang tanggal, lalu buka detail struk jika diperlukan.</p>
    </div>
  </header>

  <form class="card card-body mb-3" method="GET" action="{{ route('pos.riwayat') }}">
    <div class="row g-3 align-items-end">
      <div class="col-md-8">
        <label for="date-range" class="form-label">Rentang tanggal</label>
        <input type="text" id="date-range" class="form-control" value="{{ $start }} sampai {{ $end }}" readonly>
        <input type="hidden" name="start_date" value="{{ $start }}">
        <input type="hidden" name="end_date" value="{{ $end }}">
      </div>
      <div class="col-md-4 d-grid">
        <button class="btn btn-primary" type="submit">Terapkan tanggal</button>
      </div>
    </div>
  </form>

  <div class="card task-panel">
    <div class="card-header">
      <h2 class="h5 fw-bold mb-1">Daftar transaksi</h2>
      <p class="text-muted small mb-0">Periode {{ \Carbon\Carbon::parse($start)->translatedFormat('d M Y') }} sampai {{ \Carbon\Carbon::parse($end)->translatedFormat('d M Y') }}.</p>
    </div>

    @if($transaksi->isEmpty())
      <div class="empty-state" role="status">
        <strong>Tidak ada transaksi pada periode ini</strong>
        Ubah rentang tanggal atau mulai transaksi baru dari halaman POS.
      </div>
    @else
      <div class="table-responsive" tabindex="0" aria-label="Tabel riwayat transaksi, geser jika diperlukan">
        <table id="tableRiwayat" class="table align-middle w-100">
          <thead>
            <tr>
              <th scope="col">Tanggal</th>
              <th scope="col">Kode</th>
              <th scope="col">Kasir</th>
              <th scope="col" class="text-end">Total</th>
              <th scope="col">Metode</th>
              <th scope="col" class="text-center">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @foreach($transaksi as $t)
              <tr>
                <td>{{ $t->tanggal->format('d/m/Y H:i') }}</td>
                <td><span class="transaction-code">{{ $t->kode_transaksi }}</span></td>
                <td>{{ $t->kasir->name ?? 'Tidak tersedia' }}</td>
                <td class="text-end fw-bold">Rp {{ number_format($t->total, 0, ',', '.') }}</td>
                <td><span class="badge {{ $t->metode_pembayaran === 'cash' ? 'bg-warning' : 'bg-label-primary' }}">{{ $t->metode_pembayaran === 'cash' ? 'Tunai' : 'QRIS' }}</span></td>
                <td class="text-center">
                  <button type="button" class="btn btn-sm btn-outline-primary btn-detail" data-kode="{{ $t->kode_transaksi }}" aria-label="Buka detail transaksi {{ $t->kode_transaksi }}">
                    <i class="bx bx-detail" aria-hidden="true"></i>
                    <span class="visually-hidden">Detail</span>
                  </button>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
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
  const dateRange = document.getElementById('date-range');
  if (dateRange) {
    const picker = new Litepicker({
      element: dateRange,
      singleMode: false,
      numberOfMonths: window.innerWidth < 768 ? 1 : 2,
      numberOfColumns: window.innerWidth < 768 ? 1 : 2,
      format: 'YYYY-MM-DD',
      startDate: '{{ $start }}',
      endDate: '{{ $end }}',
      autoApply: true,
      lang: 'id-ID',
      minDate: '{{ now()->subMonths(2)->format('Y-m-d') }}',
      maxDate: '{{ now()->format('Y-m-d') }}'
    });

    picker.on('selected', (startDate, endDate) => {
      document.querySelector('[name="start_date"]').value = startDate.format('YYYY-MM-DD');
      document.querySelector('[name="end_date"]').value = endDate.format('YYYY-MM-DD');
      dateRange.value = `${startDate.format('YYYY-MM-DD')} sampai ${endDate.format('YYYY-MM-DD')}`;
    });
  }

  if (document.getElementById('tableRiwayat')) {
    $('#tableRiwayat').DataTable({
      pageLength: 10,
      order: [[0, 'desc']],
      info: false,
      language: {
        search: 'Cari transaksi:',
        lengthMenu: 'Tampilkan _MENU_ data',
        paginate: { previous: 'Sebelumnya', next: 'Berikutnya' },
        zeroRecords: 'Transaksi tidak ditemukan'
      },
      columnDefs: [{ orderable: false, targets: [5] }]
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
    const isCash = String(data.metode_pembayaran || '').toLowerCase() === 'cash';
    const paymentMethod = isCash ? 'Tunai (Cash)' : String(data.metode_pembayaran || '-').toUpperCase();

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
        <p class="receipt-footer-sub">Simpan struk ini sebagai bukti pembayaran</p>
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
