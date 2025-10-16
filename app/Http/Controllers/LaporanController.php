<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index()
    {
        // Default langsung buka laporan keuangan
        return redirect()->route('laporan.keuangan');
    }

    // 💰 LAPORAN KEUANGAN
    public function keuangan(Request $request)
{
    $start = $request->start_date ?? now()->subDays(7)->toDateString();
    $end   = $request->end_date ?? now()->toDateString();

    $query = \App\Models\Transaksi::with('kasir')
        ->whereBetween(DB::raw('DATE(created_at)'), [$start, $end])
        ->orderByDesc('created_at');

    if ($request->search) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('kode_transaksi', 'like', "%$search%")
              ->orWhereHas('kasir', fn($q2) => $q2->where('name', 'like', "%$search%"));
        });
    }

    $laporan = $query->get();

    $totalTransaksi = $laporan->count();
    $totalNilai = $laporan->sum('total');

    // 🔥 Tambahan: total uang per metode pembayaran
    $totalCash = $laporan->where('metode_pembayaran', 'cash')->sum('total');
    $totalQris = $laporan->where('metode_pembayaran', 'qris')->sum('total');

    return view('laporan.keuangan', compact(
        'laporan', 'start', 'end', 'totalTransaksi', 'totalNilai', 'totalCash', 'totalQris'
    ));
}


// Detail transaksi
    public function detail($kode)
    {
        $transaksi = \App\Models\Transaksi::with(['items.barang', 'kasir'])->where('kode_transaksi', $kode)->first();

        if (!$transaksi) {
            return response()->json(['status' => false, 'message' => 'Transaksi tidak ditemukan.']);
        }

        $items = $transaksi->items->map(function ($item) {
            return [
                'nama' => $item->barang->nama ?? '-',
                'qty' => $item->qty,
                'harga' => $item->harga,
                'subtotal' => $item->qty * $item->harga
            ];
        });

        return response()->json([
            'status' => true,
            'data' => [
                'kode' => $transaksi->kode_transaksi,
                'kasir' => $transaksi->kasir->name ?? '-',
                'tanggal' => $transaksi->created_at->format('d/m/Y H:i'),
                'total' => $transaksi->total,
                'metode_pembayaran' => $transaksi->metode_pembayaran,
                'catatan' => $transaksi->catatan,
                'items' => $items
            ]
        ]);
    }                   





    // 📦 LAPORAN PRODUK
    public function produk(Request $request)
        {
            $start = $request->start_date ?? now()->subDays(7)->toDateString();
            $end   = $request->end_date ?? now()->toDateString();
            $search = $request->search ?? '';

            $query = \App\Models\TransaksiItem::with(['barang.category'])
                ->whereHas('transaksi', function ($q) use ($start, $end) {
                    $q->whereBetween(DB::raw('DATE(created_at)'), [$start, $end]);
                });

            if ($search) {
                $query->whereHas('barang', function ($q) use ($search) {
                    $q->where('nama', 'like', "%$search%");
                });
            }

            $produkLaku = $query
                ->selectRaw('barang_id, SUM(qty) as total_qty, SUM(subtotal) as total_nilai')
                ->groupBy('barang_id')
                ->with(['barang.category'])
                ->orderByDesc('total_qty')
                ->get();

            // --- Total kategori makanan & minuman (cards)
            $totalMakanan = $produkLaku->filter(fn($p) => strtolower($p->barang->category->deskripsi ?? '') == 'makanan')->sum('total_nilai');
            $totalMinuman = $produkLaku->filter(fn($p) => strtolower($p->barang->category->deskripsi ?? '') == 'minuman')->sum('total_nilai');
            $jumlahMakanan = $produkLaku->filter(fn($p) => strtolower($p->barang->category->deskripsi ?? '') == 'makanan')->count();
            $jumlahMinuman = $produkLaku->filter(fn($p) => strtolower($p->barang->category->deskripsi ?? '') == 'minuman')->count();

            // --- Hitung kategori paling laku
            $kategoriLaku = $produkLaku
                ->groupBy(fn($p) => strtolower($p->barang->category->nama ?? 'lainnya'))
                ->map(function ($group) {
                    return [
                        'nama' => ucfirst($group->first()->barang->category->nama ?? 'Lainnya'),
                        'total_qty' => $group->sum('total_qty'),
                        'total_nilai' => $group->sum('total_nilai'),
                    ];
                })
                ->sortByDesc('total_qty');

            return view('laporan.produk', compact(
                'produkLaku', 'kategoriLaku',
                'start', 'end', 'search',
                'totalMakanan', 'totalMinuman',
                'jumlahMakanan', 'jumlahMinuman'
            ));
        }                       


}
