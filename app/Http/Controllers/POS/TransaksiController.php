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

}
