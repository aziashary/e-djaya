@extends('layouts.main')

@section('judul')
Input Data Barang
@endsection

@push('css')
<link rel="stylesheet" href="{{ asset('assets/vendor/fonts/iconify-icons.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />

<script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
<script src="{{ asset('assets/js/config.js') }}"></script>

<style>
  @media (max-width: 768px) {
    .card-body { padding: 1rem; }
    .btn { font-size: 0.85rem; padding: 0.4rem 0.8rem; }
  }
</style>
@endpush

@section('content')
<div class="content-wrapper">
  <div class="container-xxl flex-grow-1 container-p-y">

    <div class="row">
      <div class="col-12 col-lg-10 mx-auto">
        <div class="card border-0 shadow-sm">
          <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="mb-0 fw-bold">Tambah Data Barang</h5>
            <small class="text-body-secondary">Form input barang baru</small>
          </div>

          <div class="card-body">
            <form action="{{ route('barang.store') }}" method="POST">
              @csrf

              {{-- Nama Barang --}}
              <div class="mb-3 row">
                <label for="nama" class="col-sm-3 col-form-label">Nama Barang</label>
                <div class="col-sm-9">
                  <input type="text" name="nama" id="nama" class="form-control" placeholder="Masukkan nama barang" required>
                </div>
              </div>

              {{-- categories --}}
              <div class="mb-3 row">
                <label for="categories_id" class="col-sm-3 col-form-label">Kategori</label>
                <div class="col-sm-9">
                  <select name="categories_id" id="categories_id" class="form-select" required>
                    <option value="">-- Pilih categories --</option>
                    @foreach($categories as $k)
                      <option value="{{ $k->id }}">{{ $k->nama }}</option>
                    @endforeach
                  </select>
                </div>
              </div>

              {{-- Harga Beli --}}
              <div class="mb-3 row">
                <label for="harga_beli" class="col-sm-3 col-form-label">Harga Beli</label>
                <div class="col-sm-9">
                  <input type="number" name="harga_beli" id="harga_beli" class="form-control" placeholder="Masukkan harga beli" required>
                </div>
              </div>

              {{-- Harga Jual --}}
              <div class="mb-3 row">
                <label for="harga_jual" class="col-sm-3 col-form-label">Harga Jual</label>
                <div class="col-sm-9">
                  <input type="number" name="harga_jual" id="harga_jual" class="form-control" placeholder="Masukkan harga jual" required>
                </div>
              </div>

              {{-- SKU --}}
              <div class="mb-3 row">
                <label for="sku" class="col-sm-3 col-form-label">SKU</label>
                <div class="col-sm-9">
                  <input type="text" name="sku" id="sku" class="form-control" placeholder="Kode SKU (Ga Wajib)" >
                </div>
              </div>

              {{-- Deskripsi --}}
              <div class="mb-4 row">
                <label for="deskripsi" class="col-sm-3 col-form-label">Deskripsi</label>
                <div class="col-sm-9">
                  <textarea name="deskripsi" id="deskripsi" class="form-control" rows="3" placeholder="Masukkan deskripsi barang (opsional)"></textarea>
                </div>
              </div>

              {{-- Tombol --}}
              <div class="row justify-content-end">
                <div class="col-sm-9">
                  <button type="submit" class="btn btn-primary">Simpan</button>
                  <a href="{{ route('barang.index') }}" class="btn btn-outline-secondary ms-2">Batal</a>
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
