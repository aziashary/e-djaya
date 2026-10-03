@extends('layouts.main')

@section('judul', 'Kategori Produk | e-Djaya')

@section('content')
<section aria-labelledby="category-title">
  <header class="page-header">
    <div class="page-heading">
      <p class="page-kicker">Master produk</p>
      <h1 id="category-title" class="page-title">Kategori produk</h1>
      <p class="page-description">Kelompokkan barang agar katalog POS lebih cepat dipindai.</p>
    </div>
    <div class="page-actions">
      <a href="{{ route('categories.create') }}" class="btn btn-primary">Tambah kategori</a>
    </div>
  </header>

  <div class="card task-panel">
    @if($categories->isEmpty())
      <div class="empty-state" role="status">
        <strong>Belum ada kategori</strong>
        Tambahkan kategori sebelum menyusun barang pada katalog POS.
      </div>
    @else
      <div class="table-responsive" tabindex="0" aria-label="Tabel kategori, geser jika diperlukan">
        <table class="table align-middle w-100" id="tableCategories">
          <thead>
            <tr>
              <th scope="col">Nama</th>
              <th scope="col">Jenis</th>
              <th scope="col" class="text-end">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @foreach($categories as $category)
              <tr>
                <td class="fw-semibold">{{ $category->nama }}</td>
                <td>{{ $category->deskripsi ?? 'Belum ditentukan' }}</td>
                <td class="text-end">
                  <div class="d-inline-flex flex-wrap justify-content-end gap-2">
                    <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?')">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                    </form>
                  </div>
                </td>
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
  if (!document.getElementById('tableCategories')) return;
  $('#tableCategories').DataTable({
    pageLength: 10,
    ordering: true,
    info: false,
    language: {
      search: 'Cari kategori:',
      lengthMenu: 'Tampilkan _MENU_ data',
      paginate: { previous: 'Sebelumnya', next: 'Berikutnya' },
      zeroRecords: 'Kategori tidak ditemukan'
    },
    columnDefs: [{ orderable: false, targets: [2] }]
  });
});
</script>
@endpush
