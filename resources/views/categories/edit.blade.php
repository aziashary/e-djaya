@extends('layouts.main')

@section('judul', 'Edit Kategori | e-Djaya')

@section('content')
<section aria-labelledby="category-edit-title">
  @section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('categories.index') }}">Kategori</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit</li>
  @endsection
  @include('layouts.partials.breadcrumb')

  <header class="page-header">
    <div class="page-heading">
      <p class="page-kicker">Master produk</p>
      <h1 id="category-edit-title" class="page-title">Edit kategori</h1>
      <p class="page-description">Perubahan kategori akan terlihat pada susunan produk di POS.</p>
    </div>
  </header>

  <div class="card task-panel">
    <div class="card-body">
      <form action="{{ route('categories.update', $category->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row g-4">
          <div class="col-lg-7">
            <label for="nama" class="form-label">Nama kategori</label>
            <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama', $category->nama) }}" required autofocus autocomplete="off">
            @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="col-lg-5">
            <label for="deskripsi" class="form-label">Jenis produk</label>
            <select name="deskripsi" id="deskripsi" class="form-select" required>
              <option value="Makanan" @selected(old('deskripsi', $category->deskripsi) === 'Makanan')>Makanan</option>
              <option value="Minuman" @selected(old('deskripsi', $category->deskripsi) === 'Minuman')>Minuman</option>
            </select>
          </div>
        </div>

        <div class="page-actions mt-4">
          <button type="submit" class="btn btn-primary">Simpan perubahan</button>
          <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">Batal</a>
        </div>
      </form>
    </div>
  </div>
</section>
@endsection
