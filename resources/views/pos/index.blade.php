@extends('layouts.pos')

@section('judul', 'Transaksi Baru | e-Djaya')

@section('content')
@if($openBill)
  <div class="alert alert-warning d-flex flex-wrap align-items-center justify-content-between gap-2" role="status">
    <span>
      <strong>Open bill {{ $openBill['kode'] }}</strong>
      untuk {{ $openBill['nama_customer'] }} sedang diedit.
    </span>
    <a href="{{ route('pos.open-bills') }}" class="btn btn-sm btn-outline-secondary">Kembali ke daftar</a>
  </div>
@endif

<div class="pos-container">
  <section class="pos-left" aria-labelledby="catalog-title">
    <header>
      <h2 id="catalog-title" class="pos-section-title">Pilih produk</h2>
      <p class="pos-section-copy">Cari produk, lalu pilih untuk menambahkannya ke keranjang.</p>
    </header>

    <div class="product-search">
      <label for="search-barang" class="visually-hidden">Cari produk</label>
      <div class="input-group">
        <span class="input-group-text" aria-hidden="true"><i class="bx bx-search"></i></span>
        <input type="search" id="search-barang" class="form-control" placeholder="Cari nama produk" autocomplete="off">
      </div>
    </div>

    @if($data->isEmpty())
      <div class="empty-state" role="status">
        <strong>Belum ada produk aktif</strong>
        Aktifkan atau tambahkan produk agar transaksi dapat dimulai.
      </div>
    @else
      <div class="nav-align-top">
        <ul class="nav nav-pills mb-3" role="tablist" id="deskripsiTabs" aria-label="Jenis produk">
          @foreach($data as $deskripsi => $kategoriGroup)
            @php $desKey = Str::slug($deskripsi); @endphp
            <li class="nav-item flex-grow-1" role="presentation">
              <button type="button" class="nav-link w-100 {{ $loop->first ? 'active' : '' }}" role="tab" data-bs-toggle="tab" data-bs-target="#tab-{{ $desKey }}" aria-controls="tab-{{ $desKey }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                {{ ucfirst($deskripsi) }}
              </button>
            </li>
          @endforeach
        </ul>

        <div class="tab-content p-0 bg-transparent shadow-none">
          @foreach($data as $deskripsi => $kategoriGroup)
            @php $desKey = Str::slug($deskripsi); @endphp
            <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="tab-{{ $desKey }}" role="tabpanel">
              <div class="accordion" id="accordion-{{ $desKey }}">
                @foreach($kategoriGroup as $kategori => $items)
                  @php
                    $catKey = Str::slug($deskripsi . '-' . ($kategori ?: 'tanpa'));
                    if (strtolower($deskripsi) === 'minuman') {
                      $items = $items->sortBy(fn($b) => strtolower($b->nama ?? $b->nama_barang));
                    }
                  @endphp

                  <div class="accordion-item mb-2">
                    <h3 class="accordion-header" id="heading-{{ $catKey }}">
                      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $catKey }}" aria-expanded="false" aria-controls="collapse-{{ $catKey }}">
                        <span>{{ $kategori ?? 'Tanpa kategori' }}</span>
                        <span class="badge bg-label-primary ms-2">{{ $items->count() }} produk</span>
                      </button>
                    </h3>

                    <div id="collapse-{{ $catKey }}" class="accordion-collapse collapse" data-bs-parent="#accordion-{{ $desKey }}">
                      <div class="accordion-body p-0">
                        <div class="list-group list-group-flush">
                          @forelse($items as $b)
                            @php $barangNama = $b->nama ?? $b->nama_barang ?? 'Tanpa nama'; @endphp
                            <button type="button" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center gap-3 add-item text-start" data-id="{{ $b->id }}" data-nama="{{ e($barangNama) }}" data-harga="{{ $b->harga_jual }}" aria-label="Tambah {{ $barangNama }}, harga Rp {{ number_format($b->harga_jual, 0, ',', '.') }}">
                              <span class="min-w-0">
                                <span class="fw-semibold d-block">{{ $barangNama }}</span>
                                @if(isset($b->stok))
                                  <small class="text-muted">Stok {{ $b->stok }}</small>
                                @endif
                              </span>
                              <span class="product-price text-nowrap fw-bold">Rp {{ number_format($b->harga_jual, 0, ',', '.') }}</span>
                            </button>
                          @empty
                            <p class="empty-state mb-0">Tidak ada produk aktif pada kategori ini.</p>
                          @endforelse
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
    @endif

    <div id="search-empty" class="empty-state" role="status" hidden>
      <strong>Produk tidak ditemukan</strong>
      Coba kata pencarian lain atau pilih jenis produk yang berbeda.
    </div>
  </section>

  <aside class="pos-right" aria-labelledby="cart-title">
    <header>
      <h2 id="cart-title" class="pos-section-title">Keranjang</h2>
      <p class="pos-section-copy" id="cart-summary">Belum ada produk dipilih.</p>
    </header>

    <div class="cart-body mt-3">
      <div class="table-responsive">
        <table class="table table-sm align-middle">
          <thead>
            <tr>
              <th scope="col">Produk</th>
              <th scope="col" class="text-center">Jumlah</th>
              <th scope="col" class="text-end">Subtotal</th>
            </tr>
          </thead>
          <tbody id="cart-items" aria-live="polite"></tbody>
        </table>
      </div>
    </div>

    <div class="cart-footer">
      <div class="cart-total-row">
        <span>Subtotal</span>
        <strong id="cart-subtotal" class="cart-total">Rp 0</strong>
      </div>
      <div class="d-grid gap-2">
        <button class="btn btn-outline-primary btn-disabled" type="button" id="btn-open-bill" disabled>
          {{ $openBill ? 'Simpan perubahan bill' : 'Simpan open bill' }}
        </button>
        <button class="btn btn-pay btn-disabled" type="button" id="btn-open-bayar" disabled>
          Lanjut ke pembayaran
        </button>
      </div>
    </div>
  </aside>
</div>

<div class="modal fade" id="modalBayar" tabindex="-1" aria-labelledby="modalBayarLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <div>
          <h2 class="modal-title fs-5 fw-bold" id="modalBayarLabel">Detail transaksi</h2>
          <p class="mb-0 text-muted small">Lengkapi data pesanan, lalu simpan sebagai open bill atau selesaikan pembayaran.</p>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup dialog pembayaran"></button>
      </div>

      <div class="modal-body">
        <div class="payment-total mb-4">
          <span>Total pembayaran</span>
          <strong id="modal-total">Rp 0</strong>
        </div>

        <div id="payment-feedback" class="alert alert-danger" role="alert" tabindex="-1" hidden></div>

        <form id="formPembayaran" autocomplete="off" data-no-loading="true">
          <div class="row g-3">
            <div class="col-md-6">
              <label for="nama_customer" class="form-label">Nama pelanggan atau meja</label>
              <input type="text" class="form-control" id="nama_customer" name="nama_customer" placeholder="Wajib untuk open bill" autocomplete="off" value="{{ $openBill['nama_customer'] ?? '' }}">
            </div>

            <div class="col-md-6">
              <label for="makan_dimana" class="form-label">Jenis pesanan</label>
              <select name="makan_dimana" id="makan_dimana" class="form-select">
                <option value="Dine in">Makan di tempat</option>
                <option value="Takeaway">Dibawa pulang</option>
              </select>
            </div>

            <div class="col-12">
              <label for="catatan" class="form-label">Catatan pesanan</label>
              <input type="text" class="form-control" id="catatan" name="catatan" placeholder="Contoh: tanpa gula" autocomplete="off">
            </div>

            <div class="col-md-6">
              <label for="diskon" class="form-label">Diskon</label>
              <div class="input-group">
                <input type="number" class="form-control" id="diskon" name="diskon" value="0" min="0" max="100" step="0.1" inputmode="decimal">
                <span class="input-group-text">%</span>
              </div>
            </div>

            <div class="col-md-6">
              <label for="metode" class="form-label">Metode pembayaran</label>
              <select id="metode" name="metode_pembayaran" class="form-select">
                <option value="cash">Tunai</option>
                <option value="qris">QRIS</option>
              </select>
            </div>
          </div>
        </form>
      </div>

      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
        <div class="d-flex flex-wrap gap-2">
          <button type="button" class="btn btn-outline-primary" id="btn-save-open-bill">
            {{ $openBill ? 'Simpan perubahan' : 'Simpan open bill' }}
          </button>
          <button type="button" class="btn btn-outline-primary" id="btn-bayar-tanpa-cetak">Bayar tanpa cetak</button>
          <button type="button" class="btn btn-primary" id="btn-konfirmasi-bayar">Bayar dan cetak</button>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
const formatRupiah = (value) => `Rp ${Number(value || 0).toLocaleString('id-ID')}`;
const openBill = @json($openBill);
const checkoutUrl = @json(route('pos.transaksi.store'));
const openBillStoreUrl = @json(route('pos.open-bills.store'));
const openBillUpdateUrl = @json($openBill ? route('pos.open-bills.update', $openBill['kode']) : null);
const openBillSettleUrl = @json($openBill ? route('pos.open-bills.settle', $openBill['kode']) : null);
const openBillsUrl = @json(route('pos.open-bills'));
const successBaseUrl = @json(url('/pos/sukses'));
const printBaseUrl = @json(url('/pos/print'));
const fullscreenStateKey = 'edjaya-pos-fullscreen-cart';
let restoredFullscreenState = null;

try {
  const savedState = JSON.parse(sessionStorage.getItem(fullscreenStateKey) || 'null');
  if (savedState?.url === window.location.href && savedState?.state) {
    restoredFullscreenState = savedState.state;
    sessionStorage.removeItem(fullscreenStateKey);
  }
} catch (error) {
  sessionStorage.removeItem(fullscreenStateKey);
}

let cart = restoredFullscreenState?.cart
  ? [...restoredFullscreenState.cart]
  : (openBill?.items ? [...openBill.items] : []);

function escapeHtml(value) {
  return String(value)
    .replace(/&/g, '&amp;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#39;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;');
}

function setPaymentBusy(isBusy) {
  [
    document.getElementById('btn-save-open-bill'),
    document.getElementById('btn-bayar-tanpa-cetak'),
    document.getElementById('btn-konfirmasi-bayar')
  ].forEach((button) => {
    button.disabled = isBusy;
    button.setAttribute('aria-busy', String(isBusy));
  });
}

function showPaymentError(message) {
  const feedback = document.getElementById('payment-feedback');
  feedback.textContent = message;
  feedback.hidden = false;
  feedback.focus();
}

function renderCart() {
  const tbody = document.getElementById('cart-items');
  const summary = document.getElementById('cart-summary');
  tbody.innerHTML = '';

  if (cart.length === 0) {
    tbody.innerHTML = '<tr><td colspan="3" class="table-empty"><strong>Keranjang masih kosong</strong>Pilih produk dari daftar untuk memulai transaksi.</td></tr>';
    document.getElementById('cart-subtotal').textContent = 'Rp 0';
    summary.textContent = 'Belum ada produk dipilih.';
    toggleCartActions(false);
    return;
  }

  let subtotal = 0;
  cart.forEach((item) => {
    const itemSubtotal = item.harga * item.qty;
    subtotal += itemSubtotal;
    tbody.insertAdjacentHTML('beforeend', `
      <tr data-id="${item.id}">
        <td><span class="fw-semibold">${escapeHtml(item.nama)}</span></td>
        <td class="text-center">
          <div class="d-inline-flex align-items-center gap-2">
            <button type="button" class="btn btn-sm btn-outline-danger btn-dec" data-id="${item.id}" aria-label="Kurangi ${escapeHtml(item.nama)}">−</button>
            <span class="fw-bold" aria-label="Jumlah ${item.qty}">${item.qty}</span>
          </div>
        </td>
        <td class="text-end fw-bold">${formatRupiah(itemSubtotal)}</td>
      </tr>
    `);
  });

  document.getElementById('cart-subtotal').textContent = formatRupiah(subtotal);
  summary.textContent = `${cart.reduce((total, item) => total + item.qty, 0)} item dalam keranjang.`;
  toggleCartActions(true);
}

function toggleCartActions(enabled) {
  ['btn-open-bill', 'btn-open-bayar'].forEach((id) => {
    const button = document.getElementById(id);
    button.disabled = !enabled;
    button.classList.toggle('btn-disabled', !enabled);
  });
}

function updatePaymentPreview() {
  const subtotal = cart.reduce((total, item) => total + (item.harga * item.qty), 0);
  const discount = Math.max(0, Math.min(Number(document.getElementById('diskon').value) || 0, 100));
  document.getElementById('modal-total').textContent = formatRupiah(subtotal - (subtotal * discount / 100));
}

function buildPayload() {
  const subtotal = cart.reduce((total, item) => total + (item.harga * item.qty), 0);
  const discount = Math.max(0, Math.min(Number(document.getElementById('diskon').value) || 0, 100));

  return {
    subtotal,
    diskon: discount,
    total: subtotal - (subtotal * discount / 100),
    metode_pembayaran: document.getElementById('metode').value,
    nama_customer: document.getElementById('nama_customer').value.trim(),
    makan_dimana: document.getElementById('makan_dimana').value,
    catatan: document.getElementById('catatan').value.trim(),
    items: cart.map((item) => ({
      barang_id: item.id,
      nama: item.nama,
      harga: item.harga,
      qty: item.qty,
      subtotal: item.harga * item.qty
    }))
  };
}

function requestTransaction(url, method, payload, onSuccess) {
  document.getElementById('payment-feedback').hidden = true;
  setPaymentBusy(true);

  $.ajax({
    url,
    method,
    data: { _token: @json(csrf_token()), ...payload },
    success: (response) => {
      if (!response.success || !response.kode_transaksi) {
        showPaymentError(response.message || 'Transaksi tidak dapat disimpan. Coba lagi.');
        setPaymentBusy(false);
        return;
      }

      onSuccess(response);
    },
    error: (xhr) => {
      showPaymentError(xhr.responseJSON?.message || 'Transaksi gagal disimpan. Periksa koneksi dan coba lagi.');
      setPaymentBusy(false);
    }
  });
}

function saveOpenBill() {
  const payload = buildPayload();
  if (payload.items.length === 0) {
    showPaymentError('Keranjang masih kosong. Pilih setidaknya satu produk.');
    return;
  }
  if (!payload.nama_customer) {
    showPaymentError('Isi nama pelanggan atau meja sebelum menyimpan open bill.');
    document.getElementById('nama_customer').focus();
    return;
  }

  requestTransaction(
    openBill ? openBillUpdateUrl : openBillStoreUrl,
    openBill ? 'PUT' : 'POST',
    payload,
    () => {
      window.location.href = openBillsUrl;
    }
  );
}

function submitTransaction(shouldPrint) {
  const payload = buildPayload();
  if (payload.items.length === 0) {
    showPaymentError('Keranjang masih kosong. Pilih setidaknya satu produk.');
    return;
  }

  requestTransaction(
    openBill ? openBillSettleUrl : checkoutUrl,
    'POST',
    payload,
    (response) => {
      bootstrap.Modal.getOrCreateInstance(document.getElementById('modalBayar')).hide();
      if (shouldPrint) {
        window.open(`${printBaseUrl}/${response.kode_transaksi}?copies=2`, '_blank');
      }
      window.location.href = `${successBaseUrl}/${response.kode_transaksi}`;
    }
  );
}

function openTransactionDetails() {
  document.getElementById('payment-feedback').hidden = true;
  updatePaymentPreview();
  bootstrap.Modal.getOrCreateInstance(document.getElementById('modalBayar')).show();
}

$(document).on('click', '.add-item', function() {
  const id = $(this).data('id');
  const existing = cart.find((item) => item.id == id);
  if (existing) {
    existing.qty += 1;
  } else {
    cart.push({
      id,
      nama: $(this).data('nama'),
      harga: Number($(this).data('harga')) || 0,
      qty: 1
    });
  }
  renderCart();
});

$(document).on('click', '.btn-dec', function() {
  const index = cart.findIndex((item) => item.id == $(this).data('id'));
  if (index < 0) return;
  cart[index].qty -= 1;
  if (cart[index].qty <= 0) cart.splice(index, 1);
  renderCart();
});

$('#search-barang').on('input', function() {
  const query = this.value.toLowerCase().trim();
  let found = false;
  $('.list-group-item-action').each(function() {
    const matches = !query || this.textContent.toLowerCase().includes(query);
    this.hidden = !matches;
    if (matches) {
      found = true;
      if (query) {
        bootstrap.Collapse.getOrCreateInstance($(this).closest('.accordion-collapse')[0], { toggle: false }).show();
      }
    }
  });
  document.getElementById('search-empty').hidden = found;
});

if (openBill) {
  document.getElementById('nama_customer').value = openBill.nama_customer || '';
  document.getElementById('makan_dimana').value = openBill.makan_dimana || 'Dine in';
  document.getElementById('catatan').value = openBill.catatan || '';
  document.getElementById('diskon').value = openBill.diskon || 0;
}

if (restoredFullscreenState?.fields) {
  document.getElementById('nama_customer').value = restoredFullscreenState.fields.nama_customer || '';
  document.getElementById('makan_dimana').value = restoredFullscreenState.fields.makan_dimana || 'Dine in';
  document.getElementById('catatan').value = restoredFullscreenState.fields.catatan || '';
  document.getElementById('diskon').value = restoredFullscreenState.fields.diskon || 0;
  document.getElementById('metode').value = restoredFullscreenState.fields.metode_pembayaran || 'cash';
}

window.getPosFullscreenState = () => ({
  cart,
  fields: {
    nama_customer: document.getElementById('nama_customer').value,
    makan_dimana: document.getElementById('makan_dimana').value,
    catatan: document.getElementById('catatan').value,
    diskon: document.getElementById('diskon').value,
    metode_pembayaran: document.getElementById('metode').value
  }
});

$('#btn-open-bill, #btn-open-bayar').on('click', openTransactionDetails);
$('#diskon').on('input', updatePaymentPreview);
$('#btn-save-open-bill').on('click', saveOpenBill);
$('#btn-bayar-tanpa-cetak').on('click', () => submitTransaction(false));
$('#btn-konfirmasi-bayar').on('click', () => submitTransaction(true));

renderCart();

if (@json(request()->boolean('pay'))) {
  openTransactionDetails();
}
</script>
@endpush
