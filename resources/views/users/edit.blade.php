@extends('layouts.main')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
  <h4 class="fw-bold mb-4">Edit User</h4>

  <div class="card p-4">
    <form action="{{ route('users.update', $user->id) }}" method="POST">
      @csrf
      @method('PUT')
      <div class="row">
        <div class="col-md-6 mb-3">
          <label>Nama Lengkap</label>
          <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
        </div>
        <div class="col-md-6 mb-3">
          <label>Username</label>
          <input type="text" name="username" class="form-control" value="{{ $user->username }}" required>
        </div>
        <div class="col-md-6 mb-3">
          <label>Email</label>
          <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
        </div>
        <div class="col-md-6 mb-3">
          <label>Level</label>
          <select name="level" class="form-select" required>
            <option value="admin" {{ $user->level == 'admin' ? 'selected' : '' }}>Admin</option>
            <option value="kasir" {{ $user->level == 'kasir' ? 'selected' : '' }}>Kasir</option>
            <option value="staff" {{ $user->level == 'staff' ? 'selected' : '' }}>Staff</option>
          </select>
        </div>
        <div class="col-md-6 mb-3">
          <label>Password Baru (Opsional)</label>
          <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak diubah">
        </div>
      </div>
      <button type="submit" class="btn btn-primary">Update</button>
      <a href="{{ route('users.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
  </div>
</div>
@endsection
