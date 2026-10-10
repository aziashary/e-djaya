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
            ->completed()
            ->whereBetween(DB::raw('DATE(tanggal)'), [$start, $end])
            ->orderByDesc('tanggal');

        $userLevel = strtolower((string) auth()->user()->level);
        if ($userLevel === 'staff' || $userLevel === 'kasir') {
            $query->whereHas('kasir', function($q) use ($userLevel) {
                $q->where('level', $userLevel);
            });
        }

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

        $totalNilaiWarkop = 0;
        $totalNilaiRanu = 0;

        if ($userLevel === 'admin') {
            $totalNilaiWarkop = $laporan->filter(fn($t) => $t->kasir && in_array(strtolower((string) $t->kasir->level), ['kasir', 'admin'], true))->sum('total');
            $totalNilaiRanu = $laporan->filter(fn($t) => $t->kasir && strtolower((string) $t->kasir->level) === 'staff')->sum('total');
        }

        // 🔥 Tambahan: total uang per metode pembayaran
        $totalCash = $laporan->where('metode_pembayaran', 'cash')->sum('total');
        $totalQris = $laporan->where('metode_pembayaran', 'qris')->sum('total');

        // === REKAP HARIAN (tanggal, penghasilan, jumlah transaksi) ===
        $queryRekap = \App\Models\Transaksi::selectRaw("DATE(tanggal) as tanggal, SUM(total) as penghasilan, COUNT(*) as jumlah_transaksi")
            ->completed()
            ->whereBetween(DB::raw('DATE(tanggal)'), [$start, $end]);

        if ($userLevel === 'staff' || $userLevel === 'kasir') {
            $queryRekap->whereHas('kasir', function($q) use ($userLevel) {
                $q->where('level', $userLevel);
            });
        }

        $rekapHarian = $queryRekap->groupBy(DB::raw('DATE(tanggal)'))
            ->orderByDesc('tanggal')
            ->get()
            ->map(function ($r) {
                $carbon = \Carbon\Carbon::parse($r->tanggal);

                // Mapping hari Inggris -> Indonesia
                $hariIndo = [
                    'Monday'    => 'Senin',
                    'Tuesday'   => 'Selasa',
                    'Wednesday' => 'Rabu',
                    'Thursday'  => 'Kamis',
                    'Friday'    => 'Jumat',
                    'Saturday'  => 'Sabtu',
                    'Sunday'    => 'Minggu',
                ];

                $r->hari = $hariIndo[$carbon->format('l')]; // nama hari dalam Bahasa Indonesia
                $r->tanggal_formatted = $carbon->format('d/m/Y');

                return $r;
            });


        return view('laporan.keuangan', compact(
            'laporan', 'start', 'end',
            'totalTransaksi', 'totalNilai', 'totalNilaiWarkop', 'totalNilaiRanu', 'totalCash', 'totalQris',
            'rekapHarian'
        ));
}


    // Riwayat Transaksi
    public function transaksi(Request $request)
    {
        $query = \App\Models\Transaksi::with('kasir')
            ->completed()
            ->orderByDesc('tanggal')
            ->orderByDesc('id');

        $tanggal = $request->input('tanggal');

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $tanggal);
        } elseif ($request->filled('start_date') && $request->filled('end_date')) {
            $start = $request->start_date;
            $end   = $request->end_date;
            $query->whereBetween(DB::raw('DATE(tanggal)'), [$start, $end]);
        }
        $userLevel = strtolower((string) auth()->user()->level);
        if ($userLevel === 'staff' || $userLevel === 'kasir') {
            $query->whereHas('kasir', function($q) use ($userLevel) {
                $q->where('level', $userLevel);
            });
        }

        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_transaksi', 'like', "%$search%")
                ->orWhereHas('kasir', fn($q2) => $q2->where('name', 'like', "%$search%"));
            });
        }

        $totalNilai = (clone $query)->sum('total');
        $laporan = $query->paginate(10)->withQueryString();
        $totalTransaksi = $laporan->total();

        return view('laporan.transaksi', compact(
            'laporan', 'tanggal', 'totalTransaksi', 'totalNilai'
        ));
    }


// Detail transaksi
    public function detail($kode)
    {
        $transaksi = \App\Models\Transaksi::query()
            ->whereIn('status', [
                \App\Models\Transaksi::STATUS_COMPLETED,
                \App\Models\Transaksi::STATUS_PENDING,
            ])
            ->visibleTo(auth()->user())
            ->with(['items.barang', 'kasir'])
            ->where('kode_transaksi', $kode)
            ->first();

        if (!$transaksi) {
            return response()->json(['status' => false, 'message' => 'Transaksi tidak ditemukan.']);
        }

        $items = $transaksi->items->map(function ($item) {
            return [
                'nama' => $item->nama ?? $item->barang->nama ?? '-',
                'qty' => (int) $item->qty,
                'harga' => (float) $item->harga,
                'subtotal' => (float) ($item->subtotal ?? ($item->qty * $item->harga)),
            ];
        });

        return response()->json([
            'status' => true,
            'data' => [
                'kode' => $transaksi->kode_transaksi,
                'status' => $transaksi->status,
                'kasir' => $transaksi->kasir->name ?? '-',
                'level' => $transaksi->kasir->level ?? 'kasir',
                'tanggal' => $transaksi->tanggal->format('d/m/Y H:i'),
                'subtotal' => (float) $transaksi->subtotal,
                'diskon' => (float) $transaksi->diskon,
                'total' => (float) $transaksi->total,
                'makan_dimana' => $transaksi->makan_dimana ?? 'Dine in',
                'metode_pembayaran' => $transaksi->metode_pembayaran,
                'catatan' => $transaksi->catatan ?? '',
                'nama_customer' => $transaksi->nama_customer ?? '-',
                'items' => $items,
            ],
        ]);
    }                   





    // 📦 LAPORAN PRODUK
    public function produk(Request $request)
        {
            $start = $request->start_date ?? now()->subDays(7)->toDateString();
            $end   = $request->end_date ?? now()->toDateString();
            $search = $request->search ?? '';

            $userLevel = strtolower((string) auth()->user()->level);

            $query = \App\Models\TransaksiItem::with(['barang.category'])
                ->whereHas('transaksi', function ($q) use ($start, $end, $userLevel) {
                    $q->completed()
                        ->whereBetween(DB::raw('DATE(tanggal)'), [$start, $end]);
                    if ($userLevel === 'staff' || $userLevel === 'kasir') {
                        $q->whereHas('kasir', function($q2) use ($userLevel) {
                            $q2->where('level', $userLevel);
                        });
                    }
                });

            if (strtolower((string) auth()->user()->level) === 'staff') {
                $query->whereHas('barang.category', function($q) {
                    $q->where('nama', 'like', '%Ranu Atas%');
                });
            }

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
