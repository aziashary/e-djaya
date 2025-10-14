@extends('layouts.main')

@section('judul')
<title>Tambah Kategori</title>
@endsection

@section('content')
<div class="content-wrapper">
  <div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
      <div class="col-12 col-lg-8 mx-auto">
        <div class="card border-0 shadow-sm">
          <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="mb-0 fw-bold">Tambah Kategori</h5>
            <small class="text-body-secondary">Form untuk menambah kategori baru</small>
          </div>

          <div class="card-body">
            <form action="{{ route('categories.store') }}" method="POST">
              @csrf

              {{-- Nama Kategori --}}
              <div class="mb-3 row">
                <label for="nama" class="col-sm-3 col-form-label">Nama Kategori</label>
                <div class="col-sm-9">
                  <input
                    type="text"
                    name="nama"
                    id="nama"
                    class="form-control"
                    placeholder="Masukkan nama kategori"
                    required>
                </div>
              </div>

              {{-- Deskripsi --}}
              <div class="mb-4 row">
                <label for="deskripsi" class="col-sm-3 col-form-label">Deskripsi</label>
                <div class="col-sm-9">
                  <select name="deskripsi" id="deskripsi" class="form-select" required>
                    <option value="">-- Pilih categories --</option>
                      <option value="Makanan">Makanan</option>
                      <option value="Minuman">Minuman</option>
                  </select>
                </div>
              </div>

              {{-- Tombol --}}
              <div class="row justify-content-end">
                <div class="col-sm-9">
                  <button type="submit" class="btn btn-primary">Simpan</button>
                  <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary ms-2">Batal</a>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
