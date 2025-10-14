<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\POS\PosController;
use App\Http\Controllers\POS\TransaksiController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {
    return view('pages.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('barang', BarangController::class);
    Route::resource('categories', CategoryController::class);

});

Route::middleware(['auth'])->prefix('pos')->group(function () {
    // halaman utama POS
    Route::get('/', [PosController::class, 'index'])->name('pos.index');

    // simpan transaksi
    Route::post('/checkout', [TransaksiController::class, 'store'])->name('pos.store');

    // riwayat transaksi
    Route::get('/riwayat', [TransaksiController::class, 'index'])->name('pos.riwayat');
});

Route::middleware(['auth'])
    ->prefix('pos')
    ->name('pos.')
    ->group(function () {
        // halaman utama POS (opsional, kalau ada)
        // Route::get('/', [PosController::class, 'index'])->name('index');

        // simpan transaksi
        Route::post('/transaksi', [TransaksiController::class, 'store'])->name('transaksi.store');

        // print struk
        Route::get('/print/{kode}', [TransaksiController::class, 'print'])->name('print');
    });

    



require __DIR__.'/auth.php';
