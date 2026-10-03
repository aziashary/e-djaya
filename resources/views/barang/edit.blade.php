@extends('layouts.main')

@section('judul', 'Edit Barang | e-Djaya')

@section('content')
<section aria-labelledby="barang-edit-title">
  @section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('barang.index') }}">Barang</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit</li>
  @endsection
  @include('layouts.partials.breadcrumb')

  <header class="page-header">
    <div class="page-heading">
      <p class="page-kicker">Master produk</p>
      <h1 id="barang-edit-title" class="page-title">Edit barang</h1>
      <p class="page-description">Perbarui detail produk dan tentukan apakah barang tersedia di POS.</p>
    </div>
  </header>

  <div class="card task-panel">
    <div class="card-body">
      <form action="{{ route('barang.update', $barang->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row g-4">
          <div class="col-lg-7">
            <label for="nama" class="form-label">Nama barang</label>
            <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama', $barang->nama) }}" required autofocus autocomplete="off">
            @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>

          <div class="col-lg-5">
            <label for="categories_id" class="form-label">Kategori</label>
            <select name="categories_id" id="categories_id" class="form-select" required>
              @foreach($allCategories as $category)
                <option value="{{ $category->id }}" @selected(old('categories_id', $barang->category_id) == $category->id)>{{ $category->nama }}</option>
              @endforeach
            </select>
          </div>

          <div class="col-lg-7">
            <label for="harga_jual" class="form-label">Harga jual</label>
            <div class="input-group">
              <span class="input-group-text">Rp</span>
              <input type="number" name="harga_jual" id="harga_jual" class="form-control @error('harga_jual') is-invalid @enderror" value="{{ old('harga_jual', $barang->harga_jual) }}" min="0" step="1" inputmode="numeric" required>
            </div>
            @error('harga_jual')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
          </div>

          <div class="col-lg-5">
            <label for="sku" class="form-label">SKU</label>
            <input type="text" name="sku" id="sku" class="form-control" value="{{ old('sku', $barang->sku) }}" autocomplete="off">
          </div>

          <div class="col-12">
            <div class="form-check form-switch d-flex align-items-center gap-2">
              <input type="checkbox" name="is_active" id="is_active" class="form-check-input" @checked(old('is_active', $barang->is_active))>
              <label class="form-check-label fw-semibold" for="is_active">Tampilkan barang di POS</label>
            </div>
          </div>
        </div>

        <div class="page-actions mt-4">
          <button type="submit" class="btn btn-primary">Simpan perubahan</button>
          <a href="{{ route('barang.index') }}" class="btn btn-outline-secondary">Batal</a>
        </div>
      </form>
    </div>
  </div>
</section>
@endsection
