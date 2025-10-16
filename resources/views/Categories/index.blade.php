@extends('layouts.main')

@section('title', 'Data Categories')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
@endpush

@section('content')
<div class="content-wrapper">
  <div class="card shadow-sm border-0">
    <div class="card-header bg-white d-flex flex-wrap justify-content-between align-items-center">
      <a href="{{ route('categories.create') }}" class="btn btn-gradient btn-primary mb-2">
        + Tambah Categories
      </a>
    </div>

    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-striped align-middle w-100" id="tableCategories">
          <thead class="table-dark">
            <tr class="text-center">
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
                <td class="text-center">{{ $no++ }}</td>
                <td>{{ $k->nama }}</td>
                <td>{{ $k->deskripsi ?? '-' }}</td>
                <td class="text-center">
                  <a href="{{ route('categories.edit', $k->id) }}" class="btn btn-sm btn-warning me-1" title="Edit">
                    <i class="bx bx-edit"></i>
                  </a>
                  <form action="{{ route('categories.destroy', $k->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" title="Hapus"
                            onclick="return confirm('Yakin hapus kategori ini?')">
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
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
  // Inisialisasi DataTable
  const table = $('#tableCategories').DataTable({
    pageLength: 10,
    responsive: true,
    ordering: true,
    info: false,
    language: {
      search: "Cari:",
      lengthMenu: "Tampilkan _MENU_ data",
      paginate: { previous: "Sebelumnya", next: "Berikutnya" },
      zeroRecords: "Tidak ada data ditemukan"
    },
    columnDefs: [
      { orderable: false, targets: [3] },
      { className: "text-center", targets: [0, 3] }
    ]
  });

  // Sinkronisasi input custom search di atas
  $('#searchCategories').on('keyup', function() {
    table.search(this.value).draw();
  });
});
</script>
@endpush
