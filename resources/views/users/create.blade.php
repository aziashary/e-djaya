@extends('layouts.main')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
  <h4 class="fw-bold mb-4">Tambah User</h4>

  <div class="card p-4">
    <form action="{{ route('users.store') }}" method="POST">
      @csrf
      <div class="row">
        <div class="col-md-6 mb-3">
          <label>Nama Lengkap</label>
          <input type="text" name="name" class="form-control" required>
        </div>
        <div class="col-md-6 mb-3">
          <label>Username</label>
          <input type="text" name="username" class="form-control" required>
        </div>
        <div class="col-md-6 mb-3">
          <label>Email</label>
          <input type="email" name="email" class="form-control" required>
        </div>
        <div class="col-md-6 mb-3">
          <label>Level</label>
          <select name="level" class="form-select" required>
            <option value="admin">Admin</option>
            <option value="kasir">Kasir</option>
            <option value="staff">Staff</option>
          </select>
        </div>
        <div class="col-md-6 mb-3">
          <label>Password</label>
          <input type="password" name="password" class="form-control" required>
        </div>
      </div>
      <button type="submit" class="btn btn-primary">Simpan</button>
      <a href="{{ route('users.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
  </div>
</div>
@endsection
