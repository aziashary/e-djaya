@extends('layouts.main')

@section('judul', 'Tambah Barang | e-Djaya')

@section('content')
<section aria-labelledby="barang-create-title">
  @section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('barang.index') }}">Barang</a></li>
    <li class="breadcrumb-item active" aria-current="page">Tambah</li>
  @endsection
  @include('layouts.partials.breadcrumb')

  <header class="page-header">
    <div class="page-heading">
      <p class="page-kicker">Master produk</p>
      <h1 id="barang-create-title" class="page-title">Tambah barang</h1>
      <p class="page-description">Barang aktif akan tersedia pada katalog POS.</p>
    </div>
  </header>

  <div class="card task-panel">
    <div class="card-body">
      <form action="{{ route('barang.store') }}" method="POST">
        @csrf
        <div class="row g-4">
          <div class="col-lg-7">
            <label for="nama" class="form-label">Nama barang</label>
            <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}" placeholder="Contoh: Kopi susu" required autofocus autocomplete="off">
            @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>

          <div class="col-lg-5">
            <label for="categories_id" class="form-label">Kategori</label>
            <select name="categories_id" id="categories_id" class="form-select" required>
              <option value="">Pilih kategori</option>
              @foreach($categories as $category)
                <option value="{{ $category->id }}" @selected(old('categories_id') == $category->id)>{{ $category->nama }}</option>
              @endforeach
            </select>
          </div>

          <div class="col-lg-7">
            <label for="harga_jual" class="form-label">Harga jual</label>
            <div class="input-group">
              <span class="input-group-text">Rp</span>
              <input type="number" name="harga_jual" id="harga_jual" class="form-control @error('harga_jual') is-invalid @enderror" value="{{ old('harga_jual') }}" min="0" step="1" inputmode="numeric" placeholder="0" required>
            </div>
            @error('harga_jual')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
          </div>

          <div class="col-12">
            <p class="form-hint mb-0">Kode SKU dibuat otomatis saat barang disimpan.</p>
          </div>
        </div>

        <div class="page-actions mt-4">
          <button type="submit" class="btn btn-primary">Simpan barang</button>
          <a href="{{ route('barang.index') }}" class="btn btn-outline-secondary">Batal</a>
        </div>
      </form>
    </div>
  </div>
</section>
@endsection
