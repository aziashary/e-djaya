@extends('layouts.main')

@section('judul', 'Laporan Keuangan')

@section('content')
<div class="container-fluid">
  <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h4 class="fw-bold mb-0">💰 Laporan Keuangan</h4>
  </div>

  <!-- Filter -->
  <form class="row g-2 mb-4" method="GET" action="{{ route('laporan.keuangan') }}">
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
  <div class="row mb-4">
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
  </div>

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
      <h5 class="card-title mb-3">Summary Transaksi</h5>
      <div class="table-responsive">
        <table id="tabelRekap" class="table table-bordered table-striped align-middle w-100">
          <thead class="table-dark">
          <tr class="text-center">
            <th style="width:8%;">No</th>
            <th class="text-center">Tanggal</th>
            <th class="text-center">Hari</th>
            <th class="text-center">Penghasilan</th>
            <th class="text-center">Jumlah Transaksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($rekapHarian as $i => $row)
            <tr>
              <td class="text-center">{{ $i + 1 }}</td>
              <td class="text-center">{{ $row->tanggal_formatted }}</td>
              <td class="text-center">{{ $row->hari }}</td>
              <td class="text-center">Rp {{ number_format($row->penghasilan, 0, ',', '.') }}</td>
              <td class="text-center">{{ $row->jumlah_transaksi }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="4" class="text-center text-muted py-3">Tidak ada data untuk periode ini</td>
            </tr>
          @endforelse
        </tbody>
        </table>
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
  $('#tabelRekap').DataTable({
  pageLength: 10,
  responsive: true,
  ordering: true,
  order: [[1, 'desc']], // urut tanggal terbaru
  info: false,
  language: {
    search: "Cari:",
    lengthMenu: "Tampilkan _MENU_ data",
    paginate: { previous: "Sebelumnya", next: "Berikutnya" },
    zeroRecords: "Tidak ada data ditemukan"
  },
  columnDefs: [
    { className: "text-center", targets: [0, 3] },
    { className: "text-end", targets: [2] },
    { orderable: true, targets: [1,2,3] }
  ]
});

});

</script>
@endpush
