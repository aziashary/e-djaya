@extends('layouts.main')

@section('judul', 'Daftar Barang | e-Djaya')

@section('content')
<section aria-labelledby="barang-title">
  <header class="page-header">
    <div class="page-heading">
      <p class="page-kicker">Master produk</p>
      <h1 id="barang-title" class="page-title">Daftar barang</h1>
      <p class="page-description">Kelola produk yang tersedia di POS beserta kategori dan statusnya.</p>
    </div>
    <div class="page-actions">
      <a href="{{ route('barang.create') }}" class="btn btn-primary">Tambah barang</a>
    </div>
  </header>

  <div class="card task-panel">
    @if($barang->isEmpty())
      <div class="empty-state" role="status">
        <strong>Belum ada barang</strong>
        Tambahkan barang pertama agar produk dapat dipilih di POS.
      </div>
    @else
      <div class="table-responsive" tabindex="0" aria-label="Tabel barang, geser jika diperlukan">
        <table class="table align-middle w-100" id="tableBarang">
          <thead>
            <tr>
              <th scope="col">Nama</th>
              <th scope="col">Kategori</th>
              <th scope="col">Status</th>
              <th scope="col" class="text-end">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @foreach($barang as $item)
              <tr>
                <td class="fw-semibold">{{ $item->nama }}</td>
                <td>{{ $item->category->nama ?? 'Tanpa kategori' }}</td>
                <td>
                  <span class="badge {{ $item->is_active ? 'bg-success' : 'bg-danger' }}">
                    {{ $item->is_active ? 'Aktif' : 'Nonaktif' }}
                  </span>
                </td>
                <td class="text-end">
                  <div class="d-inline-flex flex-wrap justify-content-end gap-2">
                    <a href="{{ route('barang.edit', $item->id) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('barang.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus barang ini?')">
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
  if (!document.getElementById('tableBarang')) return;
  $('#tableBarang').DataTable({
    pageLength: 10,
    ordering: true,
    info: false,
    language: {
      search: 'Cari barang:',
      lengthMenu: 'Tampilkan _MENU_ data',
      paginate: { previous: 'Sebelumnya', next: 'Berikutnya' },
      zeroRecords: 'Barang tidak ditemukan'
    },
    columnDefs: [{ orderable: false, targets: [3] }]
  });
});
</script>
@endpush
