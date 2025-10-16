@extends('layouts.main')

@section('judul', 'Laporan Produk')

@section('content')
<div class="container-fluid">
  <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h4 class="fw-bold mb-0">📦 Laporan Produk</h4>
  </div>

  <!-- Filter -->
  <form class="row g-2 mb-4" method="GET" action="{{ route('laporan.produk') }}">
    <div class="col-md-6 col-lg-4">
      <input type="text" id="date-range" name="daterange" value="{{ $start }} - {{ $end }}" class="form-control" placeholder="Pilih rentang tanggal" readonly>
    </div>
    <div class="col-md-6 col-lg-4">
      <input type="text" name="search" value="{{ $search }}" class="form-control" placeholder="Cari nama barang...">
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
          <h6 class="text-muted">Jumlah Makanan</h6>
          <h3 class="fw-bold text-success mb-0">{{ number_format($jumlahMakanan, 0, ',', '.') }}</h3>
        </div>
      </div>
    </div>
    <div class="col-md-6 col-xl-3">
      <div class="card text-center border-primary shadow-sm">
        <div class="card-body">
          <h6 class="text-muted">Nilai Makanan</h6>
          <h3 class="fw-bold text-primary mb-0">Rp {{ number_format($totalMakanan, 0, ',', '.') }}</h3>
        </div>
      </div>
    </div>
    <div class="col-md-6 col-xl-3">
      <div class="card text-center border-warning shadow-sm">
        <div class="card-body">
          <h6 class="text-muted">Jumlah Minuman</h6>
          <h3 class="fw-bold text-warning mb-0">{{ number_format($jumlahMinuman, 0, ',', '.') }}</h3>
        </div>
      </div>
    </div>
    <div class="col-md-6 col-xl-3">
      <div class="card text-center border-info shadow-sm">
        <div class="card-body">
          <h6 class="text-muted">Nilai Minuman</h6>
          <h3 class="fw-bold text-info mb-0">Rp {{ number_format($totalMinuman, 0, ',', '.') }}</h3>
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
    
  <!-- Dua tabel: Barang paling laku & Kategori paling laku -->
  <div class="row g-3">
    <div class="col-lg-8">
      <div class="card shadow-sm h-100">
        <div class="card-body">
          <h5 class="card-title mb-3">Yang Paling Laku</h5>
          <div class="table-responsive">
            <table id="tabelBarang" class="table table-bordered table-striped align-middle w-100">
              <thead class="table-dark">
                <tr class="text-center">
                  <th>No</th>
                  <th>Nama Barang</th>
                  <th>Kategori</th>
                  <th>Qty</th>
                  <th>Total</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($produkLaku as $index => $item)
                  <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $item->barang->nama ?? '(Barang tidak ditemukan)' }}</td>
                    <td>{{ $item->barang->category->deskripsi ?? '-' }}</td>
                    <td class="text-center">{{ $item->total_qty }}</td>
                    <td class="text-end fw-semibold">Rp {{ number_format($item->total_nilai, 0, ',', '.') }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-4">
      <div class="card shadow-sm h-100">
        <div class="card-body">
          <h5 class="card-title mb-3">Kategori Paling Laku</h5>
          <div class="table-responsive">
            <table id="tabelKategori" class="table table-bordered table-striped align-middle w-100">
              <thead class="table-dark">
                <tr class="text-center">
                  <th>No</th>
                  <th>Kategori</th>
                  <th>Qty</th>
                  <th>Total</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($kategoriLaku as $index => $kat)
                  <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $kat['nama'] }}</td>
                    <td class="text-center">{{ $kat['total_qty'] }}</td>
                    <td class="text-end fw-semibold">Rp {{ number_format($kat['total_nilai'], 0, ',', '.') }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/litepicker/dist/css/litepicker.css" />
<style>
.litepicker {
  font-family: inherit;
  border-radius: 8px;
  box-shadow: 0 3px 10px rgba(0,0,0,0.1);
}
</style>
@endpush

@push('scripts')
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

  // DataTables untuk Barang
  $('#tabelBarang').DataTable({
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
      { className: "text-center", targets: [0, 2, 3] },
      { className: "text-end", targets: [4] }
    ]
  });

  // DataTables untuk Kategori
  $('#tabelKategori').DataTable({
    pageLength: 5,
    responsive: true,
    ordering: true,
    searching: false,
    lengthChange: false,
     info: false,
    language: {
      paginate: { previous: "Sebelumnya", next: "Berikutnya" },
      zeroRecords: "Tidak ada data kategori"
    },
    columnDefs: [
      { className: "text-center", targets: [0, 2] },
      { className: "text-end", targets: [3] }
    ]
  });
});
</script>
@endpush
