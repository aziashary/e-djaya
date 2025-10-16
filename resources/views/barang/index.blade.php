@extends('layouts.main')

@section('judul')
Data Barang
@endsection

@push('css')
<link rel="stylesheet" href="{{ asset('assets/extensions/simple-datatables/style.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/pages/simple-datatables.css') }}">
<style>
  /* Responsive Table Tweaks */
  .table-responsive {
    overflow-x: auto;
  }

  @media (max-width: 768px) {
    table.table {
      font-size: 0.875rem;
    }
    .table th, .table td {
      padding: 0.5rem 0.6rem;
    }
    .btn {
      font-size: 0.75rem;
      padding: 0.3rem 0.6rem;
    }
    .card-body {
      padding: 1rem;
    }
  }

  @media (max-width: 480px) {
    table.table {
      font-size: 0.8rem;
    }
    .table th, .table td {
      padding: 0.4rem 0.5rem;
    }
    .btn {
      font-size: 0.7rem;
      padding: 0.25rem 0.5rem;
    }
  }
</style>
@endpush

@section('content')
<div class="content-wrapper">
  <div class="card shadow-sm border-0">
    <h4 class="card-header fw-bold">Data Barang</h4>

    <div class="card-body">

      <!-- Tombol & Search -->
      <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
        <a href="{{ route('barang.create') }}" class="btn btn-gradient btn-primary mb-2">
          + Tambah Barang
        </a>

        <div class="dataTable-search mb-2">
          <input
            class="dataTable-input form-control"
            placeholder="Cari barang..."
            type="text"
            id="searchBar" />
        </div>
      </div>

      <!-- Table -->
      <div class="table-responsive">
        <div class="table-responsive">
          <table class="table table-striped align-middle w-100" id="tableBarang">
            <thead class="table-dark">
              <tr class="text-center">
                <th>No</th>
                <th>Nama</th>
                <th>Kategori</th>
                <th>Status</th>
                <th width="150px">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @php $no = 1; @endphp
              @foreach($barang as $b)
                <tr>
                  <td class="text-center">{{ $no++ }}</td>
                  <td>{{ $b->nama }}</td>
                  <td>{{ $b->category->nama ?? '-' }}</td>
                  <td class="text-center">
                    @if($b->is_active)
                      <span class="badge bg-success">Aktif</span>
                    @else
                      <span class="badge bg-danger">Nonaktif</span>
                    @endif
                  </td>
                  <td class="text-center">
                    <a href="{{ route('barang.edit', $b->id) }}" 
                      class="btn btn-sm btn-warning me-1" 
                      title="Edit">
                      <i class="bx bx-edit"></i>
                    </a>

                    <form action="{{ route('barang.destroy', $b->id) }}" 
                          method="POST" 
                          class="d-inline">
                      @csrf
                      @method('DELETE')
                      <button type="submit" 
                              class="btn btn-sm btn-danger" 
                              title="Hapus" 
                              onclick="return confirm('Yakin hapus barang ini?')">
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

      <!-- Pagination -->
    </div>
  </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
  $('#tableBarang').DataTable({
    pageLength: 10,
    responsive: true,
    ordering: true,
    info: false, // hilangkan “Menampilkan 1–x dari y data”
    language: {
      search: "Cari:",
      lengthMenu: "Tampilkan _MENU_ data",
      paginate: { previous: "Sebelumnya", next: "Berikutnya" },
      zeroRecords: "Tidak ada data ditemukan"
    },
    columnDefs: [
      { orderable: false, targets: [4] }, // kolom aksi tidak bisa di-sort
      { className: "text-center", targets: [0, 3, 4] }
    ]
  });
});
</script>
@endpush

