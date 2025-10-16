@extends('layouts.main')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold">Daftar User</h4>
    <a href="{{ route('users.create') }}" class="btn btn-primary">+ Tambah User</a>
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
  @endif

  <div class="card">
    <div class="table-responsive">
      <table class="table table-striped mb-0">
        <thead>
          <tr>
            <th>#</th>
            <th>Nama</th>
            <th>Username</th>
            <th>Email</th>
            <th>Level</th>
            <th>Dibuat</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($users as $index => $user)
            <tr>
              <td>{{ $index + 1 }}</td>
              <td>{{ $user->name }}</td>
              <td>{{ $user->username }}</td>
              <td>{{ $user->email }}</td>
              <td><span class="badge bg-label-primary">{{ ucfirst($user->level) }}</span></td>
              <td>{{ $user->created_at->format('d M Y') }}</td>
              <td>
                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-warning">Edit</a>
                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                  @csrf
                  @method('DELETE')
                  <button class="btn btn-sm btn-danger" {{ $user->id == 1 ? 'disabled' : '' }}>Hapus</button>
                </form>
              </td>
            </tr>
          @empty
            <tr><td colspan="7" class="text-center">Belum ada user.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection

@push('css')
<style>
.table th, .table td { vertical-align: middle; }
</style>
@endpush
