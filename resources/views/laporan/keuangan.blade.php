@extends('layouts.main')

@section('judul', 'Laporan Keuangan | e-Djaya')

@section('content')
@php $isAdmin = strtolower((string) auth()->user()->level) === 'admin'; @endphp
<section aria-labelledby="finance-title">
  <header class="page-header">
    <div class="page-heading">
      <p class="page-kicker">Analisis operasional</p>
      <h1 id="finance-title" class="page-title">Laporan keuangan</h1>
      <p class="page-description">Bandingkan omzet dan jumlah transaksi berdasarkan periode yang dipilih.</p>
    </div>
  </header>

  <form class="card card-body mb-4" method="GET" action="{{ route('laporan.keuangan') }}">
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
        <button type="submit" class="btn btn-primary">Tampilkan laporan</button>
      </div>
    </div>
  </form>

  <div class="metric-grid" aria-label="Ringkasan keuangan">
    <article class="metric-card metric-card--primary metric-card--wide">
      <p class="metric-label">Omzet periode ini</p>
      <p class="metric-value">Rp {{ number_format($totalNilai, 0, ',', '.') }}</p>
      <p class="metric-note">{{ number_format($totalTransaksi, 0, ',', '.') }} transaksi tercatat</p>
    </article>

    @if($isAdmin)
      <article class="metric-card">
        <p class="metric-label">Warkop Djaya</p>
        <p class="metric-value">Rp {{ number_format($totalNilaiWarkop, 0, ',', '.') }}</p>
      </article>
      <article class="metric-card">
        <p class="metric-label">Ranu</p>
        <p class="metric-value">Rp {{ number_format($totalNilaiRanu, 0, ',', '.') }}</p>
      </article>
    @else
      <article class="metric-card">
        <p class="metric-label">Jumlah transaksi</p>
        <p class="metric-value">{{ number_format($totalTransaksi, 0, ',', '.') }}</p>
      </article>
    @endif

    <article class="metric-card">
      <p class="metric-label">Pembayaran tunai</p>
      <p class="metric-value">Rp {{ number_format($totalCash, 0, ',', '.') }}</p>
    </article>
    <article class="metric-card">
      <p class="metric-label">Pembayaran QRIS</p>
      <p class="metric-value">Rp {{ number_format($totalQris, 0, ',', '.') }}</p>
    </article>
  </div>

  <div class="alert alert-info mb-3" role="status">
    <strong>Periode:</strong> {{ \Carbon\Carbon::parse($start)->translatedFormat('d F Y') }} sampai {{ \Carbon\Carbon::parse($end)->translatedFormat('d F Y') }}
    @if(request('search'))
      <span class="d-block mt-1"><strong>Pencarian:</strong> {{ request('search') }}</span>
    @endif
  </div>

  <div class="card task-panel">
    <div class="card-header">
      <h2 class="h5 fw-bold mb-1">Rekap harian</h2>
      <p class="small text-muted mb-0">Penghasilan dan jumlah transaksi per hari.</p>
    </div>

    @if($rekapHarian->isEmpty())
      <div class="empty-state" role="status">
        <strong>Tidak ada transaksi pada periode ini</strong>
        Ubah rentang tanggal atau hapus kata pencarian untuk melihat data lain.
      </div>
    @else
      <div class="table-responsive" tabindex="0" aria-label="Tabel rekap keuangan, geser jika diperlukan">
        <table id="tabelRekap" class="table align-middle w-100">
          <thead>
            <tr>
              <th scope="col">Tanggal</th>
              <th scope="col">Hari</th>
              <th scope="col" class="text-end">Penghasilan</th>
              <th scope="col" class="text-end">Transaksi</th>
            </tr>
          </thead>
          <tbody>
            @foreach($rekapHarian as $row)
              <tr>
                <td>{{ $row->tanggal_formatted }}</td>
                <td>{{ $row->hari }}</td>
                <td class="text-end fw-bold">Rp {{ number_format($row->penghasilan, 0, ',', '.') }}</td>
                <td class="text-end">{{ $row->jumlah_transaksi }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @endif
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

  if (document.getElementById('tabelRekap')) {
    $('#tabelRekap').DataTable({
      pageLength: 10,
      order: [[0, 'desc']],
      info: false,
      language: {
        search: 'Cari rekap:',
        lengthMenu: 'Tampilkan _MENU_ data',
        paginate: { previous: 'Sebelumnya', next: 'Berikutnya' },
        zeroRecords: 'Rekap tidak ditemukan'
      }
    });
  }
});
</script>
@endpush
