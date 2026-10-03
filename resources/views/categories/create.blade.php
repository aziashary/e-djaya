@extends('layouts.main')

@section('judul', 'Tambah Kategori | e-Djaya')

@section('content')
<section aria-labelledby="category-create-title">
  @section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('categories.index') }}">Kategori</a></li>
    <li class="breadcrumb-item active" aria-current="page">Tambah</li>
  @endsection
  @include('layouts.partials.breadcrumb')

  <header class="page-header">
    <div class="page-heading">
      <p class="page-kicker">Master produk</p>
      <h1 id="category-create-title" class="page-title">Tambah kategori</h1>
      <p class="page-description">Gunakan nama yang mudah dikenali kasir saat memilih produk.</p>
    </div>
  </header>

  <div class="card task-panel">
    <div class="card-body">
      <form action="{{ route('categories.store') }}" method="POST">
        @csrf
        <div class="row g-4">
          <div class="col-lg-7">
            <label for="nama" class="form-label">Nama kategori</label>
            <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}" placeholder="Contoh: Kopi susu" required autofocus autocomplete="off">
            @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="col-lg-5">
            <label for="deskripsi" class="form-label">Jenis produk</label>
            <select name="deskripsi" id="deskripsi" class="form-select" required>
              <option value="">Pilih jenis produk</option>
              <option value="Makanan" @selected(old('deskripsi') === 'Makanan')>Makanan</option>
              <option value="Minuman" @selected(old('deskripsi') === 'Minuman')>Minuman</option>
            </select>
          </div>
        </div>

        <div class="page-actions mt-4">
          <button type="submit" class="btn btn-primary">Simpan kategori</button>
          <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">Batal</a>
        </div>
      </form>
    </div>
  </div>
</section>
@endsection
