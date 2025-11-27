@extends('layouts.main')

@section('judul', 'Riwayat Transaksi')

@section('content')
<div class="container-fluid">
  <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h4 class="fw-bold mb-0">Riwayat Transaksi</h4>
  </div>

  <!-- Filter -->
  <form class="row g-2 mb-4" method="GET" action="{{ route('laporan.transaksi') }}">
    <div class="col-md-6 col-lg-4">
      <input type="text" id="date-range" name="daterange" value="{{ $start }} - {{ $end }}" class="form-control" placeholder="Pilih rentang tanggal" readonly>
    </div>
    <div class="col-md-6 col-lg-4">
      <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari kode transaksi atau kasir...">
    </div>
    <div class="col-12 col-lg-4 d-grid">
      <button type="submit" class="btn btn-primary">
        <i class="bx bx-search-alt"></i> Filter
      </button>
    </div>
  </form>

  <!-- Cards Summary -->
  <!-- <div class="row mb-4">
    <div class="col-md-6 col-xl-3">
      <div class="card text-center border-success shadow-sm">
        <div class="card-body">
          <h6 class="text-muted">Total Transaksi</h6>
          <h3 class="fw-bold text-success mb-0">{{ number_format($totalTransaksi, 0, ',', '.') }}</h3>
        </div>
      </div>
    </div>
    <div class="col-md-6 col-xl-3">
      <div class="card text-center border-primary shadow-sm">
        <div class="card-body">
          <h6 class="text-muted">Nilai Transaksi</h6>
          <h3 class="fw-bold text-primary mb-0">Rp {{ number_format($totalNilai, 0, ',', '.') }}</h3>
        </div>
      </div>
    </div>
    <div class="col-md-6 col-xl-3">
      <div class="card text-center border-warning shadow-sm">
        <div class="card-body">
          <h6 class="text-muted">Uang Cash</h6>
          <h3 class="fw-bold text-warning mb-0">Rp {{ number_format($totalCash, 0, ',', '.') }}</h3>
        </div>
      </div>
    </div>
    <div class="col-md-6 col-xl-3">
      <div class="card text-center border-info shadow-sm">
        <div class="card-body">
          <h6 class="text-muted">Uang QRIS</h6>
          <h3 class="fw-bold text-info mb-0">Rp {{ number_format($totalQris, 0, ',', '.') }}</h3>
        </div>
      </div>
    </div>
  </div> -->

  <!-- Filter info -->
  <div class="alert alert-info d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2 mb-3">
    <div>
      <strong>📅 Periode:</strong> 
      {{ \Carbon\Carbon::parse($start)->translatedFormat('d F Y') }} – 
      {{ \Carbon\Carbon::parse($end)->translatedFormat('d F Y') }}
    </div>
    @if(request('search'))
      <div>
        <strong>🔍 Pencarian:</strong> "{{ request('search') }}"
      </div>
    @endif
  </div>

  <!-- Tabel Data Transaksi -->
  <div class="card shadow-sm">
    <div class="card-body">
      <h5 class="card-title mb-3">Daftar Transaksi</h5>
      <div class="table-responsive">
        <table id="tabelLaporan" class="table table-bordered table-striped align-middle w-100">
          <thead class="table-dark">
            <tr class="text-center">
              <th>No</th>
              <th>Tanggal</th>
              <th>Kode Transaksi</th>
              <th>Total</th>
              <!-- <th>Metode</th> -->
              <th>Detail</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($laporan as $index => $item)
              <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') }}</td>
                <td>{{ $item->kode_transaksi }}</td>
                <td class="text-end fw-semibold">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                <!-- <td class="text-center">
                  <span class="badge bg-{{ $item->metode_pembayaran == 'cash' ? 'warning text-dark' : 'info' }}">
                    {{ strtoupper($item->metode_pembayaran) }}
                  </span>
                </td> -->
                <td class="text-center">
            <button class="btn btn-sm btn-outline-primary btn-detail" data-kode="{{ $item->kode_transaksi }}">
              <i class="bx bx-search-alt"></i>
            </button>

            <form action="{{ route('pos.destroy', $item->id) }}" method="POST" class="d-inline delete-form"  onsubmit="return confirm('Yakin ingin menghapus transaksi ini?')">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-sm btn-outline-danger btn-delete">
                <i class="bx bx-trash"></i>
              </button>
            </form>
          </td>

              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>


<!-- Modal Struk Kasir -->
<div class="modal fade" id="modalDetail" tabindex="-1" aria-labelledby="modalDetailLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered">
    <div class="modal-content shadow">
      <div class="modal-header py-2">
        <h6 class="modal-title fw-bold" id="modalDetailLabel">🧾 Detail Struk</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-0">
        <pre id="strukBody" class="p-3 mb-0" style="
          font-family: monospace;
          font-size: 12px;
          white-space: pre-wrap;
          background: #fff;
          color: #000;
        ">Memuat data...</pre>
      </div>
      <div class="modal-footer d-flex justify-content-between">
        <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
        <button type="button" id="btnPrintStruk" class="btn btn-sm btn-primary">
          <i class="bx bx-printer"></i> Print
        </button>
      </div>
    </div>
  </div>
</div>



@endsection

@push('css')
<!-- DataTables + Litepicker -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/litepicker/dist/css/litepicker.css" />
<style>
.litepicker { font-family: inherit; border-radius: 8px; box-shadow: 0 3px 10px rgba(0,0,0,0.1); }
</style>
@endpush

@push('scripts')
<!-- jQuery + DataTables + Litepicker -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/litepicker/dist/bundle.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {

  // Litepicker
  const picker = new Litepicker({
    element: document.getElementById('date-range'),
    singleMode: false,
    numberOfMonths: 2,
    numberOfColumns: 2,
    format: 'YYYY-MM-DD',
    startDate: '{{ $start }}',
    endDate: '{{ $end }}',
    autoApply: true,
    lang: 'id-ID'
  });

  picker.on('selected', (startDate, endDate) => {
    const form = document.querySelector('form');
    const startInput = document.createElement('input');
    const endInput = document.createElement('input');
    startInput.type = 'hidden';
    startInput.name = 'start_date';
    startInput.value = startDate.format('YYYY-MM-DD');
    endInput.type = 'hidden';
    endInput.name = 'end_date';
    endInput.value = endDate.format('YYYY-MM-DD');
    form.appendChild(startInput);
    form.appendChild(endInput);
    form.submit();
  });

  // DataTables
  $('#tabelLaporan').DataTable({
    pageLength: 10,
    responsive: true,
    ordering: true,
    language: {
      search: "Cari:",
      lengthMenu: "Tampilkan _MENU_ data",
      info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
      paginate: { previous: "Sebelumnya", next: "Berikutnya" },
      zeroRecords: "Tidak ada data ditemukan"
    },
    columnDefs: [
      { orderable: false, targets: [4] }, // kolom aksi tidak bisa sort
      { className: "text-center", targets: [0, 3, 4] },
      { className: "text-end", targets: [4] }
    ]
  });

// Klik tombol Detail
$(document).on('click', '.btn-detail', function() {
  const kode = $(this).data('kode');
  $('#modalDetail').modal('show');
  $('#strukBody').text('Memuat data...');
  $('#btnPrintStruk').data('kode', kode); 

  $.ajax({
    url: `/laporan/detail/${kode}`,
    type: "GET",
    success: function(res) {
      if (res.status) {
        renderStrukKasir(res.data);
      } else {
        $('#strukBody').text('Data tidak ditemukan.');
      }
    },
    error: function() {
      $('#strukBody').text('Gagal memuat data.');
    }
  });
});

function renderStrukKasir(data) {
  const padRight = (left, right, width = 48) => {
    const space = width - (left.length + right.length);
    return left + ' '.repeat(space > 0 ? space : 0) + right;
  };

  const boldOn = '';   // simulasi aja
  const boldOff = '';
  const doubleSize = '';
  const normalSize = '';
  const center = '';
  const left = '';

  let struk = '';

  // HEADER
  struk += '           Warkop Djaya 590\n';
  struk += '       Jln Raya Puncak No. 590\n';
  struk += '------------------------------------------------\n';

  // INFO TRANSAKSI
  struk += `Kode   : ${data.kode}\n`;
  struk += `Tanggal: ${data.tanggal}\n`;
  struk += `Kasir  : ${data.kasir}\n`;
  struk += `Atas Nama : ${data.nama_customer}\n`;
  struk += '------------------------------------------------\n';

  // ITEMS
  data.items.forEach((item) => {
    struk += `${item.nama}\n`;
    struk += padRight(`${item.qty} x ${Number(item.harga).toLocaleString('id-ID')}`, `Rp ${Number(item.subtotal).toLocaleString('id-ID')}`) + '\n';
  });

  struk += '------------------------------------------------\n';

  // TOTAL
  struk += padRight('TOTAL', `Rp ${Number(data.total).toLocaleString('id-ID')}`) + '\n';
  struk += padRight('Metode Pembayaran', data.metode_pembayaran ? data.metode_pembayaran.toUpperCase() : '-') + '\n';

  // CATATAN (kalau ada)
  if (data.catatan && data.catatan.trim() !== '') {
    struk += '------------------------------------------------\n';
    struk += 'Catatan:\n';
    struk += data.catatan.trim().replace(/\r?\n|\r/g, ' ') + '\n';
  }

  struk += '------------------------------------------------\n';  
  struk += '         Djaya !\n';

  $('#strukBody').text(struk);
}


  // Tombol Print Struk
  $('#btnPrintStruk').on('click', function() {
    const kode = $('#btnPrintStruk').data('kode'); // ambil kode dari tombol
    if (!kode) {
      alert('Kode transaksi tidak ditemukan.');
      return;
    }
    window.open(`/pos/print/${kode}`, '_blank');
  });

});

</script>
@endpush
