<?php

namespace App\Http\Controllers\POS;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $data = $this->validatedTransaction($request, requiresPayment: true);

        $transaction = DB::transaction(function () use ($data) {
            $transaction = Transaksi::create($this->transactionAttributes(
                $data,
                Transaksi::STATUS_COMPLETED,
                $data['metode_pembayaran']
            ));

            $this->replaceItems($transaction, $data['items']);

            return $transaction;
        });

        return $this->successResponse($transaction, 'Transaksi berhasil disimpan.');
    }

    public function storeOpenBill(Request $request): JsonResponse
    {
        $data = $this->validatedTransaction($request, requiresCustomer: true);

        $transaction = DB::transaction(function () use ($data) {
            $transaction = Transaksi::create($this->transactionAttributes(
                $data,
                Transaksi::STATUS_PENDING,
                Transaksi::PAYMENT_PENDING
            ));

            $this->replaceItems($transaction, $data['items']);

            return $transaction;
        });

        return $this->successResponse($transaction, 'Open bill berhasil disimpan.');
    }

    public function updateOpenBill(Request $request, string $kode): JsonResponse
    {
        $data = $this->validatedTransaction($request, requiresCustomer: true);

        $transaction = DB::transaction(function () use ($data, $kode) {
            $transaction = $this->lockedOpenBill($kode);
            $transaction->update($this->transactionAttributes(
                $data,
                Transaksi::STATUS_PENDING,
                Transaksi::PAYMENT_PENDING,
                assignCashier: false
            ));
            $this->replaceItems($transaction, $data['items']);

            return $transaction;
        });

        return $this->successResponse($transaction, 'Perubahan open bill berhasil disimpan.');
    }

    public function settleOpenBill(Request $request, string $kode): JsonResponse
    {
        $data = $this->validatedTransaction($request, requiresPayment: true, requiresCustomer: true);

        $transaction = DB::transaction(function () use ($data, $kode) {
            $transaction = $this->lockedOpenBill($kode);
            $attributes = $this->transactionAttributes(
                $data,
                Transaksi::STATUS_COMPLETED,
                $data['metode_pembayaran'],
                assignCashier: false
            );
            $attributes['tanggal'] = now();

            $transaction->update($attributes);
            $this->replaceItems($transaction, $data['items']);

            return $transaction;
        });

        return $this->successResponse($transaction, 'Open bill berhasil dibayar.');
    }

    public function cancelOpenBill(string $kode)
    {
        DB::transaction(function () use ($kode) {
            $transaction = $this->lockedOpenBill($kode);
            $transaction->update(['status' => Transaksi::STATUS_CANCELED]);
        });

        return redirect()->route('pos.open-bills')->with('success', 'Open bill berhasil dibatalkan.');
    }

    public function openBills()
    {
        $openBills = Transaksi::query()
            ->pending()
            ->visibleTo(Auth::user())
            ->with(['items', 'kasir'])
            ->orderByDesc('tanggal')
            ->get();

        return view('pos.open-bills', compact('openBills'));
    }

    public function sukses(string $kode)
    {
        $transaksi = Transaksi::query()
            ->completed()
            ->visibleTo(Auth::user())
            ->with(['items', 'kasir'])
            ->where('kode_transaksi', $kode)
            ->firstOrFail();

        return view('pos.sukses', compact('transaksi'));
    }

    public function destroy(string $kode)
    {
        $transaksi = Transaksi::findOrFail($kode);
        $transaksi->delete();

        return redirect()->back()->with('success', 'Transaksi berhasil dihapus.');
    }

    public function print(string $kode)
    {
        $transaksi = Transaksi::query()
            ->whereIn('status', [Transaksi::STATUS_COMPLETED, Transaksi::STATUS_PENDING])
            ->visibleTo(Auth::user())
            ->with(['items', 'kasir'])
            ->where('kode_transaksi', $kode)
            ->firstOrFail();

        return view('pos.print', compact('transaksi'));
    }

    public function riwayat(Request $request)
    {
        $query = Transaksi::query()
            ->completed()
            ->visibleTo(Auth::user())
            ->with('kasir')
            ->orderByDesc('tanggal')
            ->orderByDesc('id');

        $tanggal = $request->input('tanggal');

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $tanggal);
        } elseif ($request->filled('start_date') && $request->filled('end_date')) {
            $start = \Carbon\Carbon::parse($request->start_date);
            $end = \Carbon\Carbon::parse($request->end_date);
            $query->whereBetween('tanggal', [$start->copy()->startOfDay(), $end->copy()->endOfDay()]);
        }

        $transaksi = $query->get();

        return view('pos.riwayat', [
            'transaksi' => $transaksi,
            'tanggal' => $tanggal,
        ]);
    }

    public function detail(string $kode): JsonResponse
    {
        $transaksi = Transaksi::query()
            ->whereIn('status', [Transaksi::STATUS_COMPLETED, Transaksi::STATUS_PENDING])
            ->visibleTo(Auth::user())
            ->with(['items.barang', 'kasir'])
            ->where('kode_transaksi', $kode)
            ->first();

        if (!$transaksi) {
            return response()->json([
                'status' => false,
                'message' => 'Transaksi tidak ditemukan.',
            ]);
        }

        $items = $transaksi->items->map(fn ($item) => [
            'nama' => $item->nama,
            'qty' => $item->qty,
            'harga' => $item->harga,
            'subtotal' => $item->qty * $item->harga,
        ]);

        return response()->json([
            'status' => true,
            'data' => [
                'kode' => $transaksi->kode_transaksi,
                'status' => $transaksi->status,
                'tanggal' => $transaksi->tanggal->format('d/m/Y H:i'),
                'kasir' => $transaksi->kasir->name ?? '-',
                'level' => $transaksi->kasir->level ?? 'kasir',
                'subtotal' => (float) $transaksi->subtotal,
                'diskon' => (float) $transaksi->diskon,
                'total' => (float) $transaksi->total,
                'makan_dimana' => $transaksi->makan_dimana ?? 'Dine in',
                'metode_pembayaran' => $transaksi->metode_pembayaran,
                'nama_customer' => $transaksi->nama_customer ?? '-',
                'catatan' => $transaksi->catatan ?? '',
                'items' => $items,
            ],
        ]);
    }

    private function validatedTransaction(
        Request $request,
        bool $requiresPayment = false,
        bool $requiresCustomer = false
    ): array {
        $rules = [
            'diskon' => 'nullable|numeric|min:0|max:100',
            'catatan' => 'nullable|string|max:1000',
            'nama_customer' => ($requiresCustomer ? 'required' : 'nullable') . '|string|max:100',
            'makan_dimana' => 'required|in:Dine in,Takeaway',
            'items' => 'required|array|min:1',
            'items.*.barang_id' => 'required|integer',
            'items.*.nama' => 'required|string|max:100',
            'items.*.harga' => 'required|numeric|min:0',
            'items.*.qty' => 'required|integer|min:1',
        ];

        if ($requiresPayment) {
            $rules['metode_pembayaran'] = 'required|in:cash,qris';
        }

        $data = $request->validate($rules);
        $data['diskon'] = (float) ($data['diskon'] ?? 0);
        $data['subtotal'] = collect($data['items'])
            ->sum(fn (array $item) => (float) $item['harga'] * (int) $item['qty']);
        $data['total'] = $data['subtotal'] - ($data['subtotal'] * $data['diskon'] / 100);

        return $data;
    }

    private function transactionAttributes(
        array $data,
        string $status,
        string $paymentMethod,
        bool $assignCashier = true
    ): array {
        $attributes = [
            'subtotal' => $data['subtotal'],
            'diskon' => $data['diskon'],
            'total' => $data['total'],
            'metode_pembayaran' => $paymentMethod,
            'nama_customer' => $data['nama_customer'] ?? null,
            'makan_dimana' => $data['makan_dimana'],
            'catatan' => $data['catatan'] ?? null,
            'status' => $status,
        ];

        if ($assignCashier) {
            $attributes['kasir_id'] = Auth::id();
        }

        return $attributes;
    }

    private function replaceItems(Transaksi $transaction, array $items): void
    {
        $transaction->items()->delete();

        foreach ($items as $item) {
            $transaction->items()->create([
                'barang_id' => $item['barang_id'],
                'nama' => $item['nama'],
                'harga' => $item['harga'],
                'qty' => $item['qty'],
                'subtotal' => (float) $item['harga'] * (int) $item['qty'],
            ]);
        }
    }

    private function lockedOpenBill(string $kode): Transaksi
    {
        $transaction = Transaksi::query()
            ->visibleTo(Auth::user())
            ->where('kode_transaksi', $kode)
            ->lockForUpdate()
            ->firstOrFail();

        abort_unless(
            $transaction->status === Transaksi::STATUS_PENDING,
            409,
            'Open bill ini sudah tidak aktif.'
        );

        return $transaction;
    }

    private function successResponse(Transaksi $transaction, string $message): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'kode_transaksi' => $transaction->kode_transaksi,
        ]);
    }
}
