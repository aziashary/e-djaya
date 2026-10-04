<?php

namespace Tests\Feature;

use App\Models\Barang;
use App\Models\Category;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OpenBillTest extends TestCase
{
    use RefreshDatabase;

    public function test_open_bill_is_updated_and_settled_without_creating_a_second_transaction(): void
    {
        $cashier = User::factory()->create(['level' => 'kasir']);
        $product = $this->createProduct();

        $createResponse = $this->actingAs($cashier)->postJson(route('pos.open-bills.store'), $this->payload($product));
        $createResponse->assertOk()->assertJson(['success' => true]);
        $code = $createResponse->json('kode_transaksi');

        $this->assertDatabaseHas('transaksi', [
            'kode_transaksi' => $code,
            'status' => Transaksi::STATUS_PENDING,
            'metode_pembayaran' => Transaksi::PAYMENT_PENDING,
            'total' => 12000,
        ]);

        $updatedPayload = $this->payload($product, quantity: 2);
        $this->putJson(route('pos.open-bills.update', $code), $updatedPayload)
            ->assertOk()
            ->assertJson(['kode_transaksi' => $code]);

        $this->assertDatabaseCount('transaksi', 1);
        $this->assertDatabaseHas('transaksi_items', [
            'transaksi_id' => Transaksi::where('kode_transaksi', $code)->value('id'),
            'qty' => 2,
            'subtotal' => 24000,
        ]);

        $settlePayload = [...$updatedPayload, 'metode_pembayaran' => 'cash'];
        $this->postJson(route('pos.open-bills.settle', $code), $settlePayload)
            ->assertOk()
            ->assertJson(['kode_transaksi' => $code]);

        $this->assertDatabaseCount('transaksi', 1);
        $this->assertDatabaseHas('transaksi', [
            'kode_transaksi' => $code,
            'status' => Transaksi::STATUS_COMPLETED,
            'metode_pembayaran' => 'cash',
            'total' => 24000,
        ]);

        $this->postJson(route('pos.open-bills.settle', $code), $settlePayload)->assertConflict();
        $this->get(route('pos.print', $code))->assertOk();
    }

    public function test_open_bills_are_shared_with_the_same_outlet_and_hidden_after_canceling(): void
    {
        $firstCashier = User::factory()->create(['level' => 'kasir']);
        $secondCashier = User::factory()->create(['level' => 'kasir']);
        $staff = User::factory()->create(['level' => 'staff']);
        $product = $this->createProduct();

        $response = $this->actingAs($firstCashier)->postJson(
            route('pos.open-bills.store'),
            $this->payload($product, customer: 'Meja Teras')
        );
        $code = $response->json('kode_transaksi');

        $this->actingAs($secondCashier)
            ->get(route('pos.open-bills'))
            ->assertOk()
            ->assertSee('Meja Teras')
            ->assertSee('btn-detail')
            ->assertSee('data-kode="' . $code . '"', false);

        $this->actingAs($secondCashier)
            ->putJson(route('pos.open-bills.update', $code), $this->payload($product, quantity: 2))
            ->assertOk();

        $this->assertDatabaseHas('transaksi', [
            'kode_transaksi' => $code,
            'kasir_id' => $firstCashier->id,
            'status' => Transaksi::STATUS_PENDING,
        ]);

        $this->actingAs($staff)
            ->get(route('pos.open-bills'))
            ->assertOk()
            ->assertDontSee('Meja Teras');

        $this->actingAs($secondCashier)
            ->post(route('pos.open-bills.cancel', $code))
            ->assertRedirect(route('pos.open-bills'));

        $this->assertDatabaseHas('transaksi', [
            'kode_transaksi' => $code,
            'status' => Transaksi::STATUS_CANCELED,
        ]);

        $this->actingAs($firstCashier)
            ->get(route('pos.open-bills'))
            ->assertOk()
            ->assertDontSee('Meja Teras');
    }

    public function test_pending_status_is_available_in_details_and_only_printed_for_open_bills(): void
    {
        $admin = User::factory()->create(['level' => 'admin']);
        $product = $this->createProduct();
        $pending = $this->createTransaction($admin, $product, Transaksi::STATUS_PENDING, 20000);
        $completed = $this->createTransaction($admin, $product, Transaksi::STATUS_COMPLETED, 30000);

        $this->actingAs($admin)
            ->getJson(route('pos.detail', $pending->kode_transaksi))
            ->assertOk()
            ->assertJsonPath('data.status', Transaksi::STATUS_PENDING);

        $this->getJson(route('laporan.detail', $pending->kode_transaksi))
            ->assertOk()
            ->assertJsonPath('data.status', Transaksi::STATUS_PENDING);

        $this->get(route('pos.print', $pending->kode_transaksi))
            ->assertOk()
            ->assertSee('Status Transaksi')
            ->assertSee('Open bill');

        $this->get(route('pos.print', $completed->kode_transaksi))
            ->assertOk()
            ->assertDontSee('Status Transaksi')
            ->assertDontSee('Open bill');
    }

    public function test_pending_and_canceled_bills_are_excluded_from_sales_totals(): void
    {
        $admin = User::factory()->create(['level' => 'admin']);
        $product = $this->createProduct();
        $completed = $this->createTransaction($admin, $product, Transaksi::STATUS_COMPLETED, 30000);
        $pending = $this->createTransaction($admin, $product, Transaksi::STATUS_PENDING, 20000);
        $canceled = $this->createTransaction($admin, $product, Transaksi::STATUS_CANCELED, 10000);
        $date = now()->toDateString();

        $this->actingAs($admin)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertViewHas('transaksi_hari_ini', 1)
            ->assertViewHas('nilai_hari_ini', fn ($value) => (float) $value === 30000.0)
            ->assertViewHas('transaksi_bulan_ini', 1)
            ->assertViewHas('nilai_bulan_ini', fn ($value) => (float) $value === 30000.0);

        $this->get(route('laporan.transaksi', ['start_date' => $date, 'end_date' => $date]))
            ->assertOk()
            ->assertSee($completed->kode_transaksi)
            ->assertDontSee($pending->kode_transaksi)
            ->assertDontSee($canceled->kode_transaksi)
            ->assertViewHas('totalTransaksi', 1)
            ->assertViewHas('totalNilai', fn ($value) => (float) $value === 30000.0);

        $this->get(route('laporan.keuangan', ['start_date' => $date, 'end_date' => $date]))
            ->assertOk()
            ->assertViewHas('totalTransaksi', 1)
            ->assertViewHas('totalNilai', fn ($value) => (float) $value === 30000.0);

        $this->get(route('laporan.produk', ['start_date' => $date, 'end_date' => $date]))
            ->assertOk()
            ->assertViewHas('produkLaku', function ($products) {
                return $products->count() === 1 && (int) $products->first()->total_qty === 1;
            });
    }

    private function payload(Barang $product, int $quantity = 1, string $customer = 'Meja 4'): array
    {
        return [
            'diskon' => 0,
            'nama_customer' => $customer,
            'makan_dimana' => 'Dine in',
            'catatan' => 'Tanpa gula',
            'items' => [[
                'barang_id' => $product->id,
                'nama' => $product->nama,
                'harga' => 12000,
                'qty' => $quantity,
            ]],
        ];
    }

    private function createProduct(): Barang
    {
        $category = Category::create([
            'nama' => 'Kopi',
            'deskripsi' => 'Minuman',
            'is_active' => true,
        ]);

        return Barang::create([
            'category_id' => $category->id,
            'nama' => 'Kopi Tubruk',
            'sku' => 'KOPI-TEST',
            'harga_beli' => 5000,
            'harga_jual' => 12000,
            'is_active' => true,
        ]);
    }

    private function createTransaction(
        User $cashier,
        Barang $product,
        string $status,
        int $total
    ): Transaksi {
        $transaction = Transaksi::create([
            'kasir_id' => $cashier->id,
            'subtotal' => $total,
            'diskon' => 0,
            'total' => $total,
            'metode_pembayaran' => $status === Transaksi::STATUS_PENDING ? Transaksi::PAYMENT_PENDING : 'cash',
            'makan_dimana' => 'Dine in',
            'nama_customer' => 'Uji laporan',
            'status' => $status,
        ]);

        $transaction->items()->create([
            'barang_id' => $product->id,
            'nama' => $product->nama,
            'harga' => $total,
            'qty' => 1,
            'subtotal' => $total,
        ]);

        return $transaction;
    }
}
