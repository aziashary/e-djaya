<?php

namespace App\Http\Controllers\POS;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class PosController extends Controller
{
    public function index(Request $request)
    {
        // Ambil kategori yang memiliki barang aktif, dengan eager load barang aktif
        $categoryQuery = Category::with(['barang' => function ($q) {
            $q->where('is_active', 1);
        }])->whereHas('barang', function ($q) {
            $q->where('is_active', 1);
        });

        if (strtolower((string) auth()->user()->level) === 'staff') {
            $categoryQuery->where('nama', 'like', '%R A N U promo pagi%');
        }

        $categories = $categoryQuery->get();

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

        $openBill = null;
        if ($request->filled('bill')) {
            $transaction = Transaksi::query()
                ->pending()
                ->visibleTo(auth()->user())
                ->with('items')
                ->where('kode_transaksi', $request->string('bill'))
                ->firstOrFail();

            $openBill = [
                'kode' => $transaction->kode_transaksi,
                'nama_customer' => $transaction->nama_customer,
                'makan_dimana' => $transaction->makan_dimana,
                'catatan' => $transaction->catatan,
                'diskon' => (float) $transaction->diskon,
                'items' => $transaction->items->map(fn ($item) => [
                    'id' => $item->barang_id,
                    'nama' => $item->nama,
                    'harga' => (float) $item->harga,
                    'qty' => (int) $item->qty,
                ])->values(),
            ];
        }

        return view('pos.index', compact('data', 'openBill'));
    }
}
