@extends('layouts.main')

@section('judul', 'Edit Pengguna | e-Djaya')

@section('content')
<section aria-labelledby="user-edit-title">
  @section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Pengguna</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit</li>
  @endsection
  @include('layouts.partials.breadcrumb')

  <header class="page-header">
    <div class="page-heading">
      <p class="page-kicker">Pengaturan akses</p>
      <h1 id="user-edit-title" class="page-title">Edit pengguna</h1>
      <p class="page-description">Perbarui identitas akun atau ubah hak akses pengguna.</p>
    </div>
  </header>

  <div class="card task-panel">
    <div class="card-body">
      <form action="{{ route('users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row g-4">
          <div class="col-md-6">
            <label for="name" class="form-label">Nama lengkap</label>
            <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
          </div>
          <div class="col-md-6">
            <label for="username" class="form-label">Username</label>
            <input type="text" id="username" name="username" class="form-control" value="{{ old('username', $user->username) }}" required autocomplete="username">
          </div>
          <div class="col-md-6">
            <label for="email" class="form-label">Email</label>
            <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required autocomplete="email">
          </div>
          <div class="col-md-6">
            <label for="level" class="form-label">Hak akses</label>
            @php $currentLevel = strtolower((string) old('level', $user->level)); @endphp
            <select id="level" name="level" class="form-select" required>
              <option value="admin" @selected($currentLevel === 'admin')>Admin</option>
              <option value="kasir" @selected($currentLevel === 'kasir')>Kasir</option>
              <option value="staff" @selected($currentLevel === 'staff')>Staff</option>
            </select>
          </div>
          <div class="col-md-6">
            <label for="password" class="form-label">Password baru</label>
            <input type="password" id="password" name="password" class="form-control" autocomplete="new-password" aria-describedby="password-hint">
            <p class="form-hint" id="password-hint">Kosongkan jika password tidak diubah.</p>
          </div>
        </div>

        <div class="page-actions mt-4">
          <button type="submit" class="btn btn-primary">Simpan perubahan</button>
          <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Batal</a>
        </div>
      </form>
    </div>
  </div>
</section>
@endsection
