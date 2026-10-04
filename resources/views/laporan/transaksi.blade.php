@extends('layouts.main')

@section('judul', 'Riwayat Transaksi | e-Djaya')

@section('content')
<section aria-labelledby="transaction-report-title">
  <header class="page-header">
    <div class="page-heading">
      <p class="page-kicker">Analisis operasional</p>
      <h1 id="transaction-report-title" class="page-title">Riwayat transaksi</h1>
      <p class="page-description">Cari transaksi, periksa struk, cetak ulang, atau hapus transaksi yang tidak diperlukan.</p>
    </div>
  </header>

  <form class="card card-body mb-4" method="GET" action="{{ route('laporan.transaksi') }}">
    <div class="row g-3 align-items-end">
      <div class="col-lg-4">
        <label for="date-range" class="form-label">Rentang tanggal</label>
        <input type="text" id="date-range" value="{{ $start }} sampai {{ $end }}" class="form-control" readonly>
        <input type="hidden" name="start_date" value="{{ $start }}">
        <input type="hidden" name="end_date" value="{{ $end }}">
      </div>
      <div class="col-lg-5">
        <label for="search" class="form-label">Kode transaksi atau kasir</label>
        <input type="search" id="search" name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari transaksi" autocomplete="off">
      </div>
      <div class="col-lg-3 d-grid">
        <button type="submit" class="btn btn-primary">Tampilkan transaksi</button>
      </div>
    </div>
  </form>

  <div class="alert alert-info mb-3" role="status">
    <strong>Periode:</strong> {{ \Carbon\Carbon::parse($start)->translatedFormat('d F Y') }} sampai {{ \Carbon\Carbon::parse($end)->translatedFormat('d F Y') }}
    @if(request('search'))
      <span class="d-block mt-1"><strong>Pencarian:</strong> {{ request('search') }}</span>
    @endif
  </div>

  <div class="card task-panel">
    <div class="card-header">
      <h2 class="h5 fw-bold mb-1">Daftar transaksi</h2>
      <p class="small text-muted mb-0">{{ number_format($laporan->count(), 0, ',', '.') }} transaksi pada hasil filter.</p>
    </div>

    @if($laporan->isEmpty())
      <div class="empty-state" role="status">
        <strong>Transaksi tidak ditemukan</strong>
        Ubah rentang tanggal atau kata pencarian untuk melihat hasil lain.
      </div>
    @else
      <div class="table-responsive" tabindex="0" aria-label="Tabel riwayat transaksi, geser jika diperlukan">
        <table id="tabelLaporan" class="table align-middle w-100">
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
                <td>{{ $item->tanggal->format('d/m/Y H:i') }}</td>
                <td><span class="transaction-code">{{ $item->kode_transaksi }}</span></td>
                <td class="text-end fw-bold">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                <td>
                  <span class="d-block">{{ $item->kasir->username ?? 'Tidak tersedia' }}</span>
                  @if($item->kasir)
                    <span class="badge {{ $cashierLevel === 'staff' ? 'bg-success' : 'bg-label-primary' }}">{{ $cashierLevel === 'staff' ? 'Ranu' : 'Warkop' }}</span>
                  @endif
                </td>
                <td class="text-end">
                  <div class="d-inline-flex flex-wrap justify-content-end gap-2">
                    <button type="button" class="btn btn-sm btn-outline-primary btn-detail" data-kode="{{ $item->kode_transaksi }}" aria-label="Buka detail transaksi {{ $item->kode_transaksi }}">Detail</button>
                    <form action="{{ route('pos.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus transaksi ini?')">
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
  const range = document.getElementById('date-range');
  const picker = new Litepicker({
    element: range,
    singleMode: false,
    numberOfMonths: window.innerWidth < 768 ? 1 : 2,
    numberOfColumns: window.innerWidth < 768 ? 1 : 2,
    format: 'YYYY-MM-DD',
    startDate: '{{ $start }}',
    endDate: '{{ $end }}',
    autoApply: true,
    lang: 'id-ID'
  });
  picker.on('selected', (startDate, endDate) => {
    document.querySelector('[name="start_date"]').value = startDate.format('YYYY-MM-DD');
    document.querySelector('[name="end_date"]').value = endDate.format('YYYY-MM-DD');
    range.value = `${startDate.format('YYYY-MM-DD')} sampai ${endDate.format('YYYY-MM-DD')}`;
  });

  if (document.getElementById('tabelLaporan')) {
    $('#tabelLaporan').DataTable({
      pageLength: 10,
      order: [[0, 'desc']],
      language: {
        search: 'Cari pada tabel:',
        lengthMenu: 'Tampilkan _MENU_ data',
        info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
        paginate: { previous: 'Sebelumnya', next: 'Berikutnya' },
        zeroRecords: 'Transaksi tidak ditemukan'
      },
      columnDefs: [{ orderable: false, targets: [4] }]
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
