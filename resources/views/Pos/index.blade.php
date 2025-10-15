{{-- resources/views/pos/index.blade.php --}}
@extends('layouts.pos')

@section('judul', 'POS - Transaksi')

@push('css')
<style>
/* ===== Layout ===== */
.pos-container {
  display: flex;
  gap: 1rem;
  height: calc(100vh - 180px); /* header + padding accounted in layout.pos */
}
.pos-left {
  flex: 2;
  overflow-y: auto;
  background: #fff;
  border-radius: 10px;
  padding: 1.25rem;
}
.pos-right {
  flex: 1;
  display: flex;
  flex-direction: column;
  background: #fff;
  border-radius: 10px;
  padding: 1.25rem;
}

/* ===== Nav / Tabs ===== */
.nav-pills .nav-link {
  font-weight: 600;
  border-radius: 8px;
}
.nav-pills .nav-link i { font-size: 1.05rem; }
.nav-pills .nav-link.active { box-shadow: none; }

/* ===== Accordion & list ===== */
.accordion-item {
  border-radius: 8px;
  overflow: hidden;
  margin-bottom: 0.5rem;
  border: 1px solid #ececec;
}
.accordion-button {
  background-color: #fafafa;
  font-weight: 600;
}
.accordion-button:not(.collapsed) {
  color: #0d6efd;
  background-color: #eef5ff;
}
.list-group-item-action {
  padding: .55rem .9rem;
  cursor: pointer;
}
.list-group-item-action .price {
  min-width: 90px;
  text-align: right;
}

/* ===== Cart ===== */
.cart-body { flex: 1; overflow-y: auto; }
.cart-footer { border-top: 1px solid #eee; padding-top: 1rem; }

/* btn-pay custom color */
.btn-pay {
  background-color: #af3f3f !important;
  border-color: #af3f3f !important;
  color: #fff !important;
  font-weight: 600;
  width: 100%;
}
.btn-pay:hover { background-color: #922e2e !important; border-color: #922e2e !important; }

/* disabled style */
.btn-pay.btn-disabled {
  opacity: 0.7;
  cursor: not-allowed;
  background-color: #aaa !important;
  border-color: #aaa !important;
}

/* minus small btn */
.btn-dec {
  width: 28px;
  height: 28px;
  padding: 0;
  line-height: 1;
  font-size: 1rem;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
}

/* search box style */
#search-barang { border-radius: 8px; }

/* mobile tweaks */
@media (max-width: 991px) {
  .pos-container { flex-direction: column; height: auto; }
  .pos-left, .pos-right { height: auto; }
}
</style>
@endpush

@section('content')
<div class="container-fluid py-2">
  <div class="pos-container">

    {{-- LEFT: Search + Tabs + Accordion --}}
    <div class="pos-left">
      <div class="d-flex align-items-center justify-content-between mb-3">
        {{-- <h5 class="fw-bold mb-0">Daftar Produk</h5>
        <div class="ms-2 d-none d-sm-block text-muted">Total kategori: {{ count($data) }}</div> --}}
      </div>

      {{-- Search --}}
      <div class="mb-3">
        <div class="input-group">
          <span class="input-group-text bg-white"><i class="bx bx-search"></i></span>
          <input type="text" id="search-barang" class="form-control" placeholder="Cari Produk">
        </div>
      </div>

      {{-- Tabs per deskripsi --}}
      <div class="nav-align-top">
        <ul class="nav nav-pills mb-3 nav-fill" role="tablist" id="deskripsiTabs">
          @foreach($data as $deskripsi => $kategoriGroup)
            @php
              $desKey = Str::slug($deskripsi);
              $desIcons = [
                'makanan' => 'bx bx-bowl-hot text-danger',
                'minuman' => 'bx bx-drink text-primary',
              ];
              $icon = $desIcons[strtolower($deskripsi)] ?? 'bx bx-category-alt text-muted';
            @endphp

            <li class="nav-item mb-1 mb-sm-0" role="presentation">
              <button
                type="button"
                class="nav-link {{ $loop->first ? 'active' : '' }}"
                role="tab"
                data-bs-toggle="tab"
                data-bs-target="#tab-{{ $desKey }}"
                aria-controls="tab-{{ $desKey }}"
                aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                <span class="d-none d-sm-inline-flex align-items-center">
                  <i class="{{ $icon }} me-2"></i> {{ ucfirst($deskripsi) }}
                </span>
                <i class="{{ $icon }} d-sm-none"></i>
              </button>
            </li>
          @endforeach
        </ul>

        <div class="tab-content">
          @foreach($data as $deskripsi => $kategoriGroup)
            @php $desKey = Str::slug($deskripsi); @endphp
            <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="tab-{{ $desKey }}" role="tabpanel">
              {{-- Accordion for categories inside this deskripsi --}}
              <div class="accordion" id="accordion-{{ $desKey }}">
                @foreach($kategoriGroup as $kategori => $items)
                  @php
                    $catKey = Str::slug($deskripsi . '-' . ($kategori ?: 'tanpa'));
                    // choose an icon for category (simple map)
                    $catIcons = [
                      'kopi' => 'bx bx-coffee',
                      'teh' => 'bx bx-leaf',
                      'susu' => 'bx bx-cup',
                      'pisang' => 'bx bx-banana',
                      'roti' => 'bx bx-baguette',
                      'dimsum' => 'bx bx-food-menu',
                      'mie' => 'bx bx-noodles',
                    ];
                    $catIcon = $catIcons[strtolower($kategori)] ?? $catIcons['default'];
                    $badgeCount = $items->count();
                  @endphp

                  <div class="accordion-item">
                    <h2 class="accordion-header" id="heading-{{ $catKey }}">
                      <button
                        class="accordion-button collapsed"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#collapse-{{ $catKey }}"
                        aria-expanded="false"
                        aria-controls="collapse-{{ $catKey }}">
                        <i class="{{ $catIcon }} me-2"></i> {{ $kategori ?? 'Tanpa Kategori' }}
                        {{-- <span class="badge bg-light text-muted ms-2">{{ $badgeCount }}</span> --}}
                      </button>
                    </h2>

                    <div id="collapse-{{ $catKey }}" class="accordion-collapse collapse" data-bs-parent="#accordion-{{ $desKey }}">
                      <div class="accordion-body p-0">
                        <div class="list-group list-group-flush">
                          @foreach($items as $b)
                            {{-- Ensure name attribute fallback (nama or nama_barang) --}}
                            @php $barangNama = $b->nama ?? $b->nama_barang ?? 'Tanpa Nama'; @endphp
                            <a href="javascript:void(0);"
                               class="list-group-item list-group-item-action d-flex justify-content-between align-items-center add-item"
                               data-id="{{ $b->id }}"
                               data-nama="{{ e($barangNama) }}"
                               data-harga="{{ $b->harga_jual }}">
                              <div>
                                <div class="fw-semibold">{{ $barangNama }}</div>
                                @if(isset($b->stok))
                                  <small class="text-muted">Stok: {{ $b->stok }}</small>
                                @endif
                              </div>
                              <div class="price text-muted small">Rp {{ number_format($b->harga_jual) }}</div>
                            </a>
                          @endforeach
                        </div>
                      </div>
                    </div>
                  </div>

                @endforeach
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </div>

    <!-- KANAN: Keranjang -->
  <div class="pos-right">
    <h5 class="fw-bold mb-3">Keranjang</h5>

    <div class="cart-body">
      <table class="table table-sm">
        <thead>
          <tr>
            <th>Produk</th>
            <th class="text-center">Qty</th>
            <th class="text-end">Subtotal</th>
          </tr>
        </thead>
        <tbody id="cart-items">
          <tr><td colspan="3" class="text-center text-muted">Belum ada item</td></tr>
        </tbody>        
      </table>
    </div>

    <div class="cart-footer mt-auto">
      <div class="d-flex justify-content-between mb-2">
        <span>Subtotal</span>
        <strong id="cart-subtotal">Rp 0</strong>
      </div>
      <button class="btn btn-pay btn-pay-main btn-disabled" type="button" id="btn-open-bayar" disabled>
            <i class="bx bx-check-circle me-1"></i> Bayarin
      </button>
    </div>
  </div>
</div>
</div>

{{-- Modal Pembayaran (single modal used for both Rayab and Pay&Print) --}}
<div class="modal fade" id="modalBayar" tabindex="-1" aria-labelledby="modalBayarLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold" id="modalBayarLabel"><i class="bx bx-wallet-alt me-2 text-success"></i> Pembayaran</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <div class="text-center mb-4">
          <h6 class="text-muted mb-0">Total Pembayaran</h6>
          <h2 class="fw-bolder" id="modal-total">Rp 0</h2>
        </div>

        <form id="formPembayaran" autocomplete="off">
          <div class="row g-3">
            <div class="col-md-6">
              <label for="diskon" class="form-label fw-semibold">Diskon (%)</label>
              <input type="number" class="form-control" id="diskon" value="0" min="0" max="100" step="0.1">
            </div>

            <div class="col-md-6">
              <label for="metode" class="form-label fw-semibold">Metode Pembayaran</label>
              <select id="metode" class="form-select">
                <option value="qris">QRIS</option>
                <option value="cash">Cash</option>
              </select>
            </div>
          </div>

          <div id="cash-section" class="mt-4" style="display:none;">
            <div class="row g-3">
              <div class="col-md-6">
                <label for="uang-diterima" class="form-label fw-semibold">Uang Diterima</label>
                <input type="number" class="form-control" id="uang-diterima" placeholder="Masukkan jumlah uang">
              </div>
              <div class="col-md-6">
                <label for="kembalian" class="form-label fw-semibold">Kembalian</label>
                <input type="text" class="form-control" id="kembalian" readonly>
              </div>
            </div>
          </div>
        </form>
      </div>

      <div class="modal-footer d-flex justify-content-between align-items-center flex-wrap">
        <div class="d-flex gap-2">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bx bx-x"></i> Batal</button>
          <button type="button" class="btn text-white" id="btn-rayab" style="background-color:#af3f3f; border-color:#af3f3f;">
            <i class="bx bx-receipt"></i> Rayab doang
          </button>
        </div>

        <div>
          <button type="button" class="btn btn-success" id="btn-konfirmasi-bayar">
            <i class="bx bx-printer"></i> Pay & Print
          </button>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
/*
  POS frontend logic
  - cart: array of items {id, nama, harga, qty}
  - add-item: add or increment
  - decrement: decrement and remove if qty = 0
  - renderCart: re-render cart and subtotal, toggle pay button
  - payment modal: show totals, handle discount & cash calculation
  - submit via AJAX to route('pos.transaksi.store')
*/

/* ---------- Helpers ---------- */
function numberToRupiah(n) {
  if (!n) return 'Rp 0';
  return 'Rp ' + n.toLocaleString('id-ID');
}

/* ---------- State ---------- */
let cart = [];

/* ---------- Add item handler (delegated) ---------- */
$(document).on('click', '.add-item', function(e) {
  e.preventDefault();
  const $el = $(this);
  const id = $el.data('id');
  const nama = $el.data('nama');
  const harga = parseInt($el.data('harga')) || 0;

  const existing = cart.find(i => i.id == id);
  if (existing) {
    existing.qty += 1;
  } else {
    cart.push({ id, nama, harga, qty: 1 });
  }
  renderCart();
});

/* ---------- Render cart ---------- */
function renderCart() {
  const $tbody = $('#cart-items');
  $tbody.empty();

  if (cart.length === 0) {
    $tbody.append('<tr><td colspan="3" class="text-center text-muted py-4">Belum ada item</td></tr>');
    $('#cart-subtotal').text('Rp 0');
    togglePayButton(false);
    return;
  }

  let subtotal = 0;
  cart.forEach(item => {
    const sub = item.harga * item.qty;
    subtotal += sub;

    $tbody.append(`
      <tr data-id="${item.id}">
        <td>
          <div class="fw-semibold">${escapeHtml(item.nama)}</div>
        </td>
        <td class="text-center align-middle">
          <div class="d-inline-flex align-items-center justify-content-center">
            <button class="btn btn-sm btn-outline-danger btn-dec me-2" data-id="${item.id}" title="Kurangi jumlah">−</button>
            <span class="fw-semibold mx-1">${item.qty}</span>
          </div>
        </td>
        <td class="text-end align-middle">Rp ${sub.toLocaleString('id-ID')}</td>
      </tr>
    `);
  });

  $('#cart-subtotal').text('Rp ' + subtotal.toLocaleString('id-ID'));
  togglePayButton(true);
}

/* ---------- Toggle pay button ---------- */
function togglePayButton(enable) {
  const $btn = $('#btn-open-bayar');
  if (enable) {
    $btn.prop('disabled', false).removeClass('btn-disabled');
  } else {
    $btn.prop('disabled', true).addClass('btn-disabled');
  }
}

/* ---------- Decrement listener ---------- */
$(document).on('click', '.btn-dec', function(e) {
  e.preventDefault();
  const id = $(this).data('id');
  const idx = cart.findIndex(i => i.id == id);
  if (idx === -1) return;
  cart[idx].qty -= 1;
  if (cart[idx].qty <= 0) {
    cart.splice(idx, 1);
  }
  renderCart();
});

/* ---------- Escape HTML helper (avoid XSS when injecting nama) ---------- */
function escapeHtml(str) {
  return String(str)
    .replace(/&/g, '&amp;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#39;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;');
}

/* ---------- Search logic (smart: opens relevant accordions) ---------- */
$('#search-barang').on('input', function() {
  const q = $(this).val().toLowerCase().trim();

  if (q === '') {
    // show all and close all accordion
    $('.list-group-item').show();
    $('.accordion-collapse').collapse('hide');
    $('#no-result').remove();
    return;
  }

  // hide all first and close all
  $('.list-group-item').hide();
  $('.accordion-collapse').collapse('hide');

  let found = false;
  $('.list-group-item').each(function() {
    const txt = $(this).text().toLowerCase();
    if (txt.includes(q)) {
      found = true;
      $(this).show();

      // open category accordion that contains this item
      const $collapse = $(this).closest('.accordion-collapse');
      if ($collapse.length) {
        $collapse.collapse('show');
        $collapse.prev().find('.accordion-button').removeClass('collapsed');
      }
    }
  });

  if (!found) {
    if ($('#no-result').length === 0) {
      $('.pos-left').append('<p id="no-result" class="text-center text-muted mt-3">Barang tidak ditemukan 😅</p>');
    }
  } else {
    $('#no-result').remove();
  }
});

/* ---------- Payment modal flow ---------- */
$('#btn-open-bayar').on('click', function() {
  // compute subtotal
  let subtotal = cart.reduce((acc, it) => acc + (it.harga * it.qty), 0);
  $('#diskon').val(0);
  $('#uang-diterima').val('');
  $('#kembalian').val('');
  $('#modal-total').text(numberToRupiah(subtotal));
  $('#modalBayar').modal('show');
  // default method hide cash section
  $('#cash-section').hide();
  $('#metode').val('qris');
});

/* change metode */
$('#metode').on('change', function() {
  if ($(this).val() === 'cash') {
    $('#cash-section').slideDown();
  } else {
    $('#cash-section').slideUp();
    $('#uang-diterima').val('');
    $('#kembalian').val('');
  }
});

/* recalc when diskon or uang diterima changes */
$('#diskon, #uang-diterima').on('input', function() {
  updatePaymentPreview();
});

function updatePaymentPreview() {
  let subtotal = cart.reduce((acc, it) => acc + (it.harga * it.qty), 0);
  let diskonPerc = parseFloat($('#diskon').val()) || 0;
  diskonPerc = Math.max(0, Math.min(diskonPerc, 100));
  let totalSetelahDiskon = subtotal - (subtotal * diskonPerc / 100);
  $('#modal-total').text(numberToRupiah(totalSetelahDiskon));

  if ($('#metode').val() === 'cash') {
    let uang = parseInt($('#uang-diterima').val()) || 0;
    let kembalian = uang - totalSetelahDiskon;
    $('#kembalian').val(kembalian > 0 ? numberToRupiah(kembalian) : 'Rp 0');
  } else {
    $('#kembalian').val('');
  }
}

/* ---------- AJAX submit logic ---------- */
function buildPayload() {
  const subtotal = cart.reduce((acc, it) => acc + (it.harga * it.qty), 0);
  const diskonPerc = parseFloat($('#diskon').val()) || 0;
  const total = subtotal - (subtotal * diskonPerc / 100);
  const metode = $('#metode').val();
  const catatan = ''; // extendable
  const items = cart.map(it => ({
    barang_id: it.id,
    nama: it.nama,
    harga: it.harga,
    qty: it.qty,
    subtotal: it.harga * it.qty
  }));

  return { subtotal, diskon: diskonPerc, total, metode_pembayaran: metode, catatan, items };
}

/* common AJAX POST */
function submitTransaction(isPrint) {
  const payload = buildPayload();
  if (payload.items.length === 0) {
    alert('Keranjang kosong');
    return;
  }

  // if cash, ensure uang diterima enough
  if (payload.metode_pembayaran === 'cash') {
    const uang = parseInt($('#uang-diterima').val()) || 0;
    if (uang < payload.total) {
      alert('Uang diterima kurang dari total pembayaran');
      return;
    }
  }

  // send ajax
    $.ajax({
    url: "{{ route('pos.transaksi.store') }}",
    method: 'POST',
    data: {
      _token: "{{ csrf_token() }}",
      subtotal: payload.subtotal,
      diskon: payload.diskon,
      total: payload.total,
      metode_pembayaran: payload.metode_pembayaran,
      catatan: payload.catatan,
      items: payload.items
    },
    success: function(res) {
      if (res.success && res.kode_transaksi) {
        // kosongkan keranjang & tutup modal
        $('#modalBayar').modal('hide');
        cart = [];
        renderCart();

        // buka tab baru untuk print (manual)
        window.open("{{ url('/pos/print') }}/" + res.kode_transaksi, "_blank");

        // redirect ke halaman sukses
        window.location.href = "{{ url('/pos/sukses') }}/" + res.kode_transaksi;
      } else {
        alert('Gagal menyimpan transaksi.');
      }
    },
    error: function(xhr) {
      console.error(xhr);
      alert('Terjadi error saat menyimpan transaksi.');
    }
  });

}

/* Rayab doang (simpan tanpa print) */
$('#btn-rayab').on('click', function() {
  submitTransaction(false);
});

/* Pay & Print */
$('#btn-konfirmasi-bayar').on('click', function() {
  submitTransaction(true);
});

/* initialize */
$(function() {
  renderCart();
});
</script>
@endpush
