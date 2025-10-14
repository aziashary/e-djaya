<?php

namespace App\Http\Controllers\POS;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class PosController extends Controller
{
    public function index()
    {
        // Ambil kategori yang memiliki barang aktif, dengan eager load barang aktif
        $categories = Category::with(['barang' => function ($q) {
            $q->where('is_active', 1);
        }])->whereHas('barang', function ($q) {
            $q->where('is_active', 1);
        })->get();

        /**
         * Struktur $data yang dihasilkan:
         * [
         *   'Minuman' => [
         *       'Kopi' => Collection([...barang]),
         *       'Teh'  => Collection([...barang]),
         *   ],
         *   'Makanan' => [
         *       'Nasi' => Collection([...barang]),
         *   ],
         * ]
         */
        $data = $categories
            ->groupBy(function ($cat) {
                // pastikan ada deskripsi, fallback kalau null
                return $cat->deskripsi ?? 'Tanpa Deskripsi';
            })
            ->map(function ($catsInDeskripsi) {
                // untuk tiap deskripsi, ubah jadi map kategori_nama => barang collection
                return $catsInDeskripsi->mapWithKeys(function ($cat) {
                    // nama kategori: gunakan field 'nama' atau 'name' (sesuaikan dengan schema)
                    $kategoriName = $cat->nama ?? $cat->name ?? 'Tanpa Kategori';
                    return [$kategoriName => $cat->barang];
                });
            });

        return view('pos.index', compact('data'));
    }
}
