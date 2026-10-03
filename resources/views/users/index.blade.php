@extends('layouts.main')

@section('judul', 'Pengguna | e-Djaya')

@section('content')
<section aria-labelledby="users-title">
  <header class="page-header">
    <div class="page-heading">
      <p class="page-kicker">Pengaturan akses</p>
      <h1 id="users-title" class="page-title">Pengguna</h1>
      <p class="page-description">Kelola akun yang dapat mengakses dashboard, POS, dan laporan.</p>
    </div>
    <div class="page-actions">
      <a href="{{ route('users.create') }}" class="btn btn-primary">Tambah pengguna</a>
    </div>
  </header>

  <div class="card task-panel">
    @if($users->isEmpty())
      <div class="empty-state" role="status">
        <strong>Belum ada pengguna</strong>
        Tambahkan akun untuk memberi akses ke e-Djaya.
      </div>
    @else
      <div class="table-responsive" tabindex="0" aria-label="Tabel pengguna, geser jika diperlukan">
        <table class="table align-middle w-100" id="tableUsers">
          <thead>
            <tr>
              <th scope="col">Nama</th>
              <th scope="col">Username</th>
              <th scope="col">Email</th>
              <th scope="col">Akses</th>
              <th scope="col">Dibuat</th>
              <th scope="col" class="text-end">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @foreach($users as $user)
              <tr>
                <td class="fw-semibold">{{ $user->name }}</td>
                <td>{{ $user->username }}</td>
                <td>{{ $user->email }}</td>
                <td><span class="badge bg-label-primary">{{ ucfirst(strtolower($user->level)) }}</span></td>
                <td>{{ $user->created_at->translatedFormat('d M Y') }}</td>
                <td class="text-end">
                  <div class="d-inline-flex flex-wrap justify-content-end gap-2">
                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Hapus pengguna ini?')">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-sm btn-outline-danger" @disabled($user->id === 1) aria-label="{{ $user->id === 1 ? 'Akun utama tidak dapat dihapus' : 'Hapus pengguna ' . $user->name }}">Hapus</button>
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
  if (!document.getElementById('tableUsers')) return;
  $('#tableUsers').DataTable({
    pageLength: 10,
    ordering: true,
    info: false,
    language: {
      search: 'Cari pengguna:',
      lengthMenu: 'Tampilkan _MENU_ data',
      paginate: { previous: 'Sebelumnya', next: 'Berikutnya' },
      zeroRecords: 'Pengguna tidak ditemukan'
    },
    columnDefs: [{ orderable: false, targets: [5] }]
  });
});
</script>
@endpush
