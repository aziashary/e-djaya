<?php

namespace App\Http\Controllers\POS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\TransaksiItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'subtotal' => 'required|numeric',
            'diskon' => 'nullable|numeric',
            'total' => 'required|numeric',
            'metode_pembayaran' => 'required|in:cash,qris',
            'catatan' => 'nullable|string',
            'nama_customer' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.barang_id' => 'nullable|integer',
            'items.*.nama' => 'required|string',
            'items.*.harga' => 'required|numeric',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.subtotal' => 'required|numeric',
        ]);

        try {
            DB::beginTransaction();

            $transaksi = Transaksi::create([
                'kasir_id' => Auth::id(),
                'subtotal' => $data['subtotal'],
                'diskon' => $data['diskon'] ?? 0,
                'total' => $data['total'],
                'metode_pembayaran' => $data['metode_pembayaran'],
                'nama_customer' => $data['nama_customer'] ?? null,
                'catatan' => $data['catatan'] ?? null,
                'status' => 'selesai',
            ]);

            foreach ($data['items'] as $item) {
                $transaksi->items()->create($item);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil disimpan',
                'kode_transaksi' => $transaksi->kode_transaksi,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

        public function sukses($kode)
    {
        $transaksi = \App\Models\Transaksi::with('items', 'kasir')
            ->where('kode_transaksi', $kode)
            ->firstOrFail();

        return view('pos.sukses', compact('transaksi'));
    }


    public function print($kode)
    {
        $transaksi = \App\Models\Transaksi::with('items', 'kasir')->where('kode_transaksi', $kode)->firstOrFail();

        return view('pos.print', compact('transaksi'));
    }

    public function riwayat(Request $request)
{
    $end = $request->end_date ? \Carbon\Carbon::parse($request->end_date) : now();
    $start = $request->start_date ? \Carbon\Carbon::parse($request->start_date) : now()->subDays(7);

    if ($start->diffInMonths($end) > 2) {
        $start = $end->copy()->subMonths(2);
    }

    $transaksi = \App\Models\Transaksi::with('kasir')
        ->whereBetween('created_at', [$start->startOfDay(), $end->endOfDay()])
        ->orderByDesc('created_at')
        ->get();

    return view('pos.riwayat', [
        'transaksi' => $transaksi,
        'start' => $start->format('Y-m-d'),
        'end' => $end->format('Y-m-d'),
    ]);
}

public function detail($kode)
{
        $transaksi = \App\Models\Transaksi::with(['items.barang', 'kasir'])
            ->where('kode_transaksi', $kode)
            ->first();

        if (!$transaksi) {
            return response()->json([
                'status' => false,
                'message' => 'Transaksi tidak ditemukan.'
            ]);
        }

        // Mapping data item
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
                'tanggal' => $transaksi->created_at->format('d/m/Y H:i'),
                'kasir' => $transaksi->kasir->name ?? '-',
                'total' => $transaksi->total,
                'metode_pembayaran' => $transaksi->metode_pembayaran ?? '-',
                'catatan' => $transaksi->catatan ?? '',
                'items' => $items,
            ]
        ]);
}




}
