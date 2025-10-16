@extends('layouts.pos')

@section('title', 'Riwayat Transaksi')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/litepicker/dist/css/litepicker.css">
<style>
  .litepicker {
    font-family: inherit;
    border-radius: 8px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
  }
  pre.struk {
    font-family: monospace;
    font-size: 12px;
    white-space: pre-wrap;
    background: #fff;
    color: #000;
  }
</style>
@endpush

@section('content')
<div class="content-wrapper">
  <div class="row justify-content-center">
    <div class="col-lg-10">
      <div class="card shadow-sm border-0">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
          <h5 class="fw-bold mb-0">📜 Riwayat Transaksi</h5>
          <form class="d-flex gap-2" method="GET" action="{{ route('pos.riwayat') }}">
            <input type="text" id="date-range" class="form-control form-control-sm"
                   value="{{ $start }} - {{ $end }}" style="max-width: 250px;" readonly>
            <input type="hidden" name="start_date" value="{{ $start }}">
            <input type="hidden" name="end_date" value="{{ $end }}">
            <button class="btn btn-sm btn-primary">
              <i class="bx bx-filter-alt"></i> Filter
            </button>
          </form>
        </div>

        <div class="card-body">
          <div class="table-responsive">
            <table id="tableRiwayat" class="table table-striped align-middle w-100">
              <thead class="table-dark">
                <tr class="text-center">
                  <th>No</th>
                  <th>Tanggal</th>
                  <th>Kode</th>
                  <th>Kasir</th>
                  <th>Total</th>
                  <th>Metode</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                @foreach($transaksi as $i => $t)
                  <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($t->created_at)->format('d/m/Y H:i') }}</td>
                    <td>{{ $t->kode_transaksi }}</td>
                    <td>{{ $t->kasir->name ?? '-' }}</td>
                    <td class="text-end fw-semibold">Rp {{ number_format($t->total, 0, ',', '.') }}</td>
                    <td class="text-center">
                      <span class="badge bg-{{ $t->metode_pembayaran == 'cash' ? 'warning text-dark' : 'info' }}">
                        {{ strtoupper($t->metode_pembayaran) }}
                      </span>
                    </td>
                    <td class="text-center">
                      <button type="button" class="btn btn-sm btn-outline-primary btn-detail" 
                              data-kode="{{ $t->kode_transaksi }}">
                        <i class="bx bx-search-alt"></i>
                      </button>
                      {{-- <a href="{{ route('print', $t->kode_transaksi) }}" 
                         target="_blank" class="btn btn-sm btn-outline-success">
                        <i class="bx bx-printer"></i> --}}
                      </a>
                    </td>
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


<!-- Modal Struk -->
<div class="modal fade" id="modalDetail" tabindex="-1" aria-labelledby="modalDetailLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered">
    <div class="modal-content shadow">
      <div class="modal-header py-2">
        <h6 class="modal-title fw-bold" id="modalDetailLabel">🧾 Detail Struk</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-0">
        <pre id="strukBody" class="struk p-3 mb-0">Memuat data...</pre>
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

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/litepicker/dist/bundle.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
  // Litepicker setup
  const picker = new Litepicker({
    element: document.getElementById('date-range'),
    singleMode: false,
    numberOfMonths: 2,
    numberOfColumns: 2,
    format: 'YYYY-MM-DD',
    startDate: '{{ $start }}',
    endDate: '{{ $end }}',
    autoApply: true,
    lang: 'id-ID',
    minDate: '{{ now()->subMonths(2)->format('Y-m-d') }}',
    maxDate: '{{ now()->format('Y-m-d') }}',
  });

  picker.on('selected', (startDate, endDate) => {
    document.querySelector('[name="start_date"]').value = startDate.format('YYYY-MM-DD');
    document.querySelector('[name="end_date"]').value = endDate.format('YYYY-MM-DD');
  });

  // DataTables setup
  $('#tableRiwayat').DataTable({
    pageLength: 10,
    responsive: true,
    order: [[1, 'desc']],
    info: false,
    language: {
      search: "Cari:",
      lengthMenu: "Tampilkan _MENU_ data",
      paginate: { previous: "Sebelumnya", next: "Berikutnya" },
      zeroRecords: "Tidak ada transaksi ditemukan"
    },
    columnDefs: [
      { orderable: false, targets: [6] },
      { className: "text-center", targets: [0, 5, 6] },
      { className: "text-end", targets: [4] }
    ]
  });

  // Detail modal
  $(document).on('click', '.btn-detail', function() {
    const kode = $(this).data('kode');
    $('#modalDetail').modal('show');
    $('#strukBody').text('Memuat data...');
    $('#btnPrintStruk').data('kode', kode);

    $.ajax({
      url: `/pos/detail/${kode}`,
      type: "GET",
      success: function(res) {
        if (res.status) renderStrukKasir(res.data);
        else $('#strukBody').text('Data tidak ditemukan.');
      },
      error: function() {
        $('#strukBody').text('Gagal memuat data.');
      }
    });
  });

  // Render struk
  function renderStrukKasir(data) {
    const padRight = (left, right, width = 42) => {
      const space = width - (left.length + right.length);
      return left + ' '.repeat(space > 0 ? space : 0) + right;
    };
    let struk = '';
    struk += '           Warkop Djaya 590\n';
    struk += '       Jln Raya Puncak No. 590\n';
    struk += '------------------------------------------\n';
    struk += `Kode   : ${data.kode}\n`;
    struk += `Tanggal: ${data.tanggal}\n`;
    struk += `Kasir  : ${data.kasir}\n`;
    struk += '------------------------------------------\n';
    data.items.forEach((item) => {
      struk += `${item.nama}\n`;
      struk += padRight(`${item.qty} x ${Number(item.harga).toLocaleString('id-ID')}`, `Rp ${Number(item.subtotal).toLocaleString('id-ID')}`) + '\n';
    });
    struk += '------------------------------------------\n';
    struk += padRight('TOTAL', `Rp ${Number(data.total).toLocaleString('id-ID')}`) + '\n';
    struk += padRight('Metode Pembayaran', data.metode_pembayaran ? data.metode_pembayaran.toUpperCase() : '-') + '\n';
    if (data.catatan && data.catatan.trim() !== '') {
      struk += '------------------------------------------\n';
      struk += 'Catatan:\n';
      struk += data.catatan.trim().replace(/\r?\n|\r/g, ' ') + '\n';
    }
    struk += '------------------------------------------\n';
    struk += '         Terima Kasih 🙏\n';
    struk += '   Semoga harimu menyenangkan!\n';
    $('#strukBody').text(struk);
  }

  // Print struk
  $('#btnPrintStruk').on('click', function() {
    const kode = $(this).data('kode');
    if (!kode) return alert('Kode transaksi tidak ditemukan.');
    window.open(`/pos/print/${kode}`, '_blank');
  });
});
</script>
@endpush
