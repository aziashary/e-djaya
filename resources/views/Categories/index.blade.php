@extends('layouts.main')

@section('judul')
Data Categories
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
    <h4 class="card-header fw-bold">Data Categories</h4>

    <div class="card-body">

      <!-- Tombol & Search -->
      <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
        <a href="{{ route('categories.create') }}" class="btn btn-gradient btn-primary mb-2">
          + Tambah Categories
        </a>

        <div class="dataTable-search mb-2">
          <input
            class="dataTable-input form-control"
            placeholder="Cari Categories..."
            type="text"
            id="searchCategories" />
        </div>
      </div>

      <!-- Table -->
      <div class="table-responsive">
        <table class="table table-striped align-middle" id="tableCategories">
          <thead class="table-light">
            <tr>
              <th>No</th>
              <th>Nama</th>
              <th>Deskripsi</th>
              <th width="150px">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @php $no = 1; @endphp
            @foreach($categories as $k)
              <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $k->nama }}</td>
                <td>{{ $k->deskripsi ?? '-' }}</td>
                <td>
                  <a href="{{ route('categories.edit', $k->id) }}" 
                    class="btn btn-sm btn-warning me-1" 
                    title="Edit">
                   <i class="bx bx-edit"></i>
                 </a>
                 
                 <form action="{{ route('categories.destroy', $k->id) }}" 
                       method="POST" 
                       class="d-inline">
                   @csrf
                   @method('DELETE')
                   <button type="submit" 
                           class="btn btn-sm btn-danger" 
                           title="Hapus" 
                           onclick="return confirm('Yakin hapus kategori ini?')">
                     <i class="bx bx-trash"></i>
                   </button>
                 </form>                 
                  </form>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div class="mt-3">
        {{ $categories->links() }}
      </div>
    </div>
  </div>
</div>
@endsection

@push('js')
<script src="{{ asset('assets/extensions/simple-datatables/umd/simple-datatables.js') }}"></script>
<script>
  document.addEventListener("DOMContentLoaded", function () {
    const dataTable = new simpleDatatables.DataTable("#tableCategories", {
      searchable: true,
      fixedHeight: true,
      perPageSelect: [5, 10, 25, 50],
      perPage: 10,
      labels: {
        placeholder: "Cari...",
        perPage: "Data per halaman",
        noRows: "Tidak ada data ditemukan",
        info: "Menampilkan {start}–{end} dari {rows} data"
      },
    });

    // Custom search bar (external input)
    const searchInput = document.querySelector("#searchCategories");
    searchInput.addEventListener("input", (e) => {
      dataTable.search(e.target.value);
    });
  });
</script>
@endpush
