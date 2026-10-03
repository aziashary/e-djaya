@extends('layouts.main')

@section('judul', 'Laporan Produk | e-Djaya')

@section('content')
<section aria-labelledby="product-report-title">
  <header class="page-header">
    <div class="page-heading">
      <p class="page-kicker">Analisis operasional</p>
      <h1 id="product-report-title" class="page-title">Laporan produk</h1>
      <p class="page-description">Temukan produk dan kategori yang paling banyak terjual pada periode pilihan.</p>
    </div>
  </header>

  <form class="card card-body mb-4" method="GET" action="{{ route('laporan.produk') }}">
    <div class="row g-3 align-items-end">
      <div class="col-lg-4">
        <label for="date-range" class="form-label">Rentang tanggal</label>
        <input type="text" id="date-range" value="{{ $start }} sampai {{ $end }}" class="form-control" readonly>
        <input type="hidden" name="start_date" value="{{ $start }}">
        <input type="hidden" name="end_date" value="{{ $end }}">
      </div>
      <div class="col-lg-5">
        <label for="search" class="form-label">Nama produk</label>
        <input type="search" id="search" name="search" value="{{ $search }}" class="form-control" placeholder="Cari produk" autocomplete="off">
      </div>
      <div class="col-lg-3 d-grid">
        <button type="submit" class="btn btn-primary">Tampilkan laporan</button>
      </div>
    </div>
  </form>

  <div class="metric-grid" aria-label="Ringkasan produk">
    <article class="metric-card metric-card--primary metric-card--wide">
      <p class="metric-label">Nilai produk terjual</p>
      <p class="metric-value">Rp {{ number_format($totalMakanan + $totalMinuman, 0, ',', '.') }}</p>
      <p class="metric-note">{{ number_format($produkLaku->sum('total_qty'), 0, ',', '.') }} item pada periode pilihan</p>
    </article>
    <article class="metric-card">
      <p class="metric-label">Jenis makanan terjual</p>
      <p class="metric-value">{{ number_format($jumlahMakanan, 0, ',', '.') }}</p>
    </article>
    <article class="metric-card">
      <p class="metric-label">Nilai makanan</p>
      <p class="metric-value">Rp {{ number_format($totalMakanan, 0, ',', '.') }}</p>
    </article>
    <article class="metric-card">
      <p class="metric-label">Jenis minuman terjual</p>
      <p class="metric-value">{{ number_format($jumlahMinuman, 0, ',', '.') }}</p>
    </article>
    <article class="metric-card">
      <p class="metric-label">Nilai minuman</p>
      <p class="metric-value">Rp {{ number_format($totalMinuman, 0, ',', '.') }}</p>
    </article>
  </div>

  <div class="alert alert-info mb-3" role="status">
    <strong>Periode:</strong> {{ \Carbon\Carbon::parse($start)->translatedFormat('d F Y') }} sampai {{ \Carbon\Carbon::parse($end)->translatedFormat('d F Y') }}
    @if($search)
      <span class="d-block mt-1"><strong>Pencarian:</strong> {{ $search }}</span>
    @endif
  </div>

  <div class="row g-3">
    <div class="col-xl-8">
      <section class="card task-panel h-100" aria-labelledby="best-product-title">
        <div class="card-header">
          <h2 id="best-product-title" class="h5 fw-bold mb-1">Produk paling laku</h2>
          <p class="small text-muted mb-0">Diurutkan berdasarkan jumlah item terjual.</p>
        </div>
        @if($produkLaku->isEmpty())
          <div class="empty-state" role="status">
            <strong>Belum ada data produk</strong>
            Ubah periode atau kata pencarian untuk melihat hasil lain.
          </div>
        @else
          <div class="table-responsive" tabindex="0" aria-label="Tabel produk paling laku, geser jika diperlukan">
            <table id="tabelBarang" class="table align-middle w-100">
              <thead>
                <tr>
                  <th scope="col">Produk</th>
                  <th scope="col">Kategori</th>
                  <th scope="col" class="text-end">Jumlah</th>
                  <th scope="col" class="text-end">Nilai</th>
                </tr>
              </thead>
              <tbody>
                @foreach($produkLaku as $item)
                  <tr>
                    <td class="fw-semibold">{{ $item->barang->nama ?? 'Produk tidak tersedia' }}</td>
                    <td>{{ $item->barang->category->nama ?? 'Tanpa kategori' }}</td>
                    <td class="text-end">{{ $item->total_qty }}</td>
                    <td class="text-end fw-bold">Rp {{ number_format($item->total_nilai, 0, ',', '.') }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @endif
      </section>
    </div>

    <div class="col-xl-4">
      <section class="card task-panel h-100" aria-labelledby="best-category-title">
        <div class="card-header">
          <h2 id="best-category-title" class="h5 fw-bold mb-1">Kategori paling laku</h2>
          <p class="small text-muted mb-0">Ringkasan penjualan per kategori.</p>
        </div>
        @if($kategoriLaku->isEmpty())
          <div class="empty-state" role="status">Kategori belum memiliki transaksi pada periode ini.</div>
        @else
          <div class="table-responsive" tabindex="0" aria-label="Tabel kategori paling laku, geser jika diperlukan">
            <table id="tabelKategori" class="table align-middle w-100">
              <thead>
                <tr>
                  <th scope="col">Kategori</th>
                  <th scope="col" class="text-end">Jumlah</th>
                  <th scope="col" class="text-end">Nilai</th>
                </tr>
              </thead>
              <tbody>
                @foreach($kategoriLaku as $category)
                  <tr>
                    <td class="fw-semibold">{{ $category['nama'] }}</td>
                    <td class="text-end">{{ $category['total_qty'] }}</td>
                    <td class="text-end fw-bold">Rp {{ number_format($category['total_nilai'], 0, ',', '.') }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @endif
      </section>
    </div>
  </div>
</section>
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

  if (document.getElementById('tabelBarang')) {
    $('#tabelBarang').DataTable({
      pageLength: 10,
      info: false,
      language: {
        search: 'Cari produk:',
        lengthMenu: 'Tampilkan _MENU_ data',
        paginate: { previous: 'Sebelumnya', next: 'Berikutnya' },
        zeroRecords: 'Produk tidak ditemukan'
      }
    });
  }

  if (document.getElementById('tabelKategori')) {
    $('#tabelKategori').DataTable({
      pageLength: 5,
      searching: false,
      lengthChange: false,
      info: false,
      language: { paginate: { previous: 'Sebelumnya', next: 'Berikutnya' } }
    });
  }
});
</script>
@endpush
