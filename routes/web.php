<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\POS\PosController;
use App\Http\Controllers\POS\TransaksiController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\UserController;  
use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('barang', BarangController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('users', UserController::class);

});


Route::middleware(['auth'])->prefix('pos')->name('pos.')->group(function () {
    // halaman utama POS
    Route::get('/', [PosController::class, 'index'])->name('index');

    // simpan transaksi
    Route::post('/checkout', [TransaksiController::class, 'store'])->name('store');

    // riwayat transaksi
    Route::get('/riwayat', [TransaksiController::class, 'riwayat'])->name('riwayat');

    // simpan transaksi
        Route::post('/transaksi', [TransaksiController::class, 'store'])->name('transaksi.store');

    // print struk
        Route::get('/print/{kode}', [TransaksiController::class, 'print'])->name('print');

    // halaman sukses
        Route::get('/sukses/{kode}', [App\Http\Controllers\POS\TransaksiController::class, 'sukses'])
        ->name('sukses');

    // detail transaksi
    Route::get('/detail/{kode}', [TransaksiController::class, 'detail'])->name('detail');

});

// Route::middleware(['auth'])
//     ->prefix('pos')
//     ->name('pos.')
//     ->group(function () {
//         // halaman utama POS (opsional, kalau ada)
//         // Route::get('/', [PosController::class, 'index'])->name('index');

//         // simpan transaksi
//         Route::post('/transaksi', [TransaksiController::class, 'store'])->name('transaksi.store');

//         // print struk
//         Route::get('/print/{kode}', [TransaksiController::class, 'print'])->name('print');

//         // halaman sukses
//         Route::get('/sukses/{kode}', [App\Http\Controllers\POS\TransaksiController::class, 'sukses'])
//         ->name('sukses');

//     });

Route::middleware(['auth'])->prefix('laporan')->group(function () {
    // halaman utama laporan
    Route::get('/', [LaporanController::class, 'index'])->name('laporan.index');

    // laporan keuangan
    Route::get('/keuangan', [LaporanController::class, 'keuangan'])->name('laporan.keuangan');

    // laporan produk
    Route::get('/produk', [LaporanController::class, 'produk'])->name('laporan.produk');

    // detail transaksi
    Route::get('/detail/{kode}', [LaporanController::class, 'detail'])->name('laporan.detail');

});

    



require __DIR__.'/auth.php';
