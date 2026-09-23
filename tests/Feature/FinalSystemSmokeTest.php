<?php

namespace Tests\Feature;

use App\Models\Akun;
use App\Services\PointService;
use App\Services\SaleService;
use Database\Seeders\PrototypeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class FinalSystemSmokeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(PrototypeSeeder::class);
    }

    public function test_one_login_redirects_each_role_to_the_correct_dashboard(): void
    {
        $this->post('/login', [
            'login' => 'CUST123',
            'password' => 'customer123',
        ])->assertRedirect(route('customer.dashboard'));

        $this->post('/logout');

        $this->post('/login', [
            'login' => 'KSR001',
            'password' => 'kasir123',
        ])->assertRedirect(route('kasir.dashboard'));

        $this->post('/logout');

        $this->post('/login', [
            'login' => 'ADM021',
            'password' => 'admin123',
        ])->assertRedirect(route('admin.dashboard'));
    }

    public function test_role_middleware_blocks_cross_role_access(): void
    {
        $customer = Akun::findOrFail('CUST123');
        $cashier = Akun::findOrFail('KSR001');
        $admin = Akun::findOrFail('ADM021');

        $this->actingAs($customer)
            ->get('/admin')
            ->assertForbidden();

        $this->actingAs($cashier)
            ->get('/admin')
            ->assertForbidden();

        $this->actingAs($admin)
            ->get('/customer')
            ->assertForbidden();
    }

    public function test_receiving_a_purchase_increases_stock_once_and_creates_one_expense(): void
    {
        $admin = Akun::findOrFail('ADM021');
        $variant = DB::table('varian_produk')->first();
        $before = (int) $variant->stok;

        DB::table('pembelian_produk')->insert([
            'id_pembelian' => 'PB9999',
            'id_supplier' => 'SUP001',
            'id_akun' => 'ADM021',
            'tanggal_pembelian' => now(),
            'status_pembelian' => 'DIPESAN',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('detail_pembelian')->insert([
            'id_detail_pembelian' => 'DPB99999',
            'id_pembelian' => 'PB9999',
            'id_varian' => $variant->id_varian,
            'jumlah' => 5,
            'harga_satuan' => 50000,
        ]);

        $this->actingAs($admin)
            ->patch(route('admin.purchases.receive', 'PB9999'))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('pembelian_produk', [
            'id_pembelian' => 'PB9999',
            'status_pembelian' => 'DITERIMA',
        ]);

        $this->assertSame(
            $before + 5,
            (int) DB::table('varian_produk')
                ->where('id_varian', $variant->id_varian)
                ->value('stok')
        );

        $this->assertDatabaseHas('pengeluaran', [
            'id_pembelian' => 'PB9999',
            'jenis_pengeluaran' => 'PEMBELIAN_PRODUK',
            'nominal' => 250000,
        ]);

        $this->actingAs($admin)
            ->patch(route('admin.purchases.receive', 'PB9999'))
            ->assertSessionHasErrors('status');

        $this->assertSame(
            $before + 5,
            (int) DB::table('varian_produk')
                ->where('id_varian', $variant->id_varian)
                ->value('stok')
        );

        $this->assertSame(
            1,
            DB::table('pengeluaran')
                ->where('id_pembelian', 'PB9999')
                ->count()
        );
    }

    public function test_points_are_revalidated_when_online_payment_is_finalized(): void
    {
        // Give the customer 20 available points from a previous successful sale.
        DB::table('penjualan')->insert([
            'id_penjualan' => 'PJ90000',
            'id_akun' => 'CUST123',
            'tanggal_penjualan' => now()->subDay(),
            'kanal_penjualan' => 'ONLINE',
            'metode_pembayaran' => 'QR',
            'status_pembayaran' => 'BERHASIL',
            'referensi_pembayaran' => 'QR-PJ90000',
            'nominal_bayar' => 200000,
            'status_penjualan' => 'SELESAI',
            'alamat_pengiriman' => 'Surabaya',
            'poin_didapat' => 20,
            'poin_digunakan' => 0,
            'diskon_poin' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $variantId = DB::table('varian_produk')
            ->where('stok', '>', 2)
            ->value('id_varian');

        $sales = app(SaleService::class);
        $points = app(PointService::class);
        $availablePoints = $points->balance('CUST123');

        $first = $sales->createOnlinePending(
            'CUST123',
            [$variantId => 1],
            'Surabaya',
            $availablePoints
        );

        $second = $sales->createOnlinePending(
            'CUST123',
            [$variantId => 1],
            'Surabaya',
            $availablePoints
        );

        $sales->payOnline($first, 'CUST123');

        $this->expectException(ValidationException::class);
        $sales->payOnline($second, 'CUST123');
    }
    public function test_failed_online_payment_does_not_change_stock_or_points(): void
    {
        $variant = DB::table('varian_produk')
            ->where('stok', '>', 0)
            ->first();

        $beforeStock = (int) $variant->stok;

        $sales = app(SaleService::class);

        $saleId = $sales->createOnlinePending(
            'CUST123',
            [$variant->id_varian => 1],
            'Surabaya',
            0
        );

        $sales->failOnlinePayment($saleId, 'CUST123');

        $sale = DB::table('penjualan')
            ->where('id_penjualan', $saleId)
            ->first();

        $this->assertSame('GAGAL', $sale->status_pembayaran);
        $this->assertSame('BATAL', $sale->status_penjualan);
        $this->assertSame(0, (int) $sale->poin_didapat);

        $this->assertSame(
            $beforeStock,
            (int) DB::table('varian_produk')
                ->where('id_varian', $variant->id_varian)
                ->value('stok')
        );
    }

    public function test_customer_can_cancel_pending_order_without_changing_stock(): void
    {
        $variant = DB::table('varian_produk')
            ->where('stok', '>', 0)
            ->first();

        $beforeStock = (int) $variant->stok;

        $sales = app(SaleService::class);

        $saleId = $sales->createOnlinePending(
            'CUST123',
            [$variant->id_varian => 1],
            'Surabaya',
            0
        );

        $sales->cancelPending($saleId, 'CUST123');

        $this->assertDatabaseHas('penjualan', [
            'id_penjualan' => $saleId,
            'status_pembayaran' => 'MENUNGGU',
            'status_penjualan' => 'BATAL',
        ]);

        $this->assertSame(
            $beforeStock,
            (int) DB::table('varian_produk')
                ->where('id_varian', $variant->id_varian)
                ->value('stok')
        );
    }

    public function test_offline_guest_has_no_member_row_and_no_points(): void
    {
        $variant = DB::table('varian_produk')
            ->where('stok', '>', 0)
            ->first();

        $beforeStock = (int) $variant->stok;

        $sales = app(SaleService::class);

        $saleId = $sales->checkoutOffline(
            'KSR001',
            [$variant->id_varian => 1],
            null,
            'TUNAI',
            999
        );

        $sale = DB::table('penjualan')
            ->where('id_penjualan', $saleId)
            ->first();

        $this->assertSame('KSR001', $sale->id_akun);
        $this->assertSame('OFFLINE', $sale->kanal_penjualan);
        $this->assertSame('BERHASIL', $sale->status_pembayaran);
        $this->assertSame(0, (int) $sale->poin_didapat);
        $this->assertSame(0, (int) $sale->poin_digunakan);

        $this->assertDatabaseMissing('penjualan_member', [
            'id_penjualan' => $saleId,
        ]);

        $this->assertSame(
            $beforeStock - 1,
            (int) DB::table('varian_produk')
                ->where('id_varian', $variant->id_varian)
                ->value('stok')
        );
    }

    public function test_offline_member_is_linked_to_customer_and_earns_points(): void
    {
        $variant = DB::table('varian_produk')
            ->where('stok', '>', 0)
            ->first();

        $sales = app(SaleService::class);

        $saleId = $sales->checkoutOffline(
            'KSR001',
            [$variant->id_varian => 1],
            'CUST123',
            'QR',
            0
        );

        $sale = DB::table('penjualan')
            ->where('id_penjualan', $saleId)
            ->first();

        $this->assertSame('KSR001', $sale->id_akun);
        $this->assertGreaterThanOrEqual(0, (int) $sale->poin_didapat);

        $this->assertDatabaseHas('penjualan_member', [
            'id_penjualan' => $saleId,
            'id_akun_customer' => 'CUST123',
        ]);
    }

    public function test_active_promo_is_saved_as_detail_price_snapshot(): void
    {
        $normalPrice = (float) DB::table('produk')
            ->where('id_produk', 'PRD001')
            ->value('harga_jual');

        $expectedPromoPrice = round($normalPrice * 0.90, 2);

        $sales = app(SaleService::class);

        $saleId = $sales->createOnlinePending(
            'CUST123',
            ['VAR0001' => 1],
            'Surabaya',
            0
        );

        $detailPrice = (float) DB::table('detail_penjualan')
            ->where('id_penjualan', $saleId)
            ->value('harga_satuan');

        $this->assertSame($expectedPromoPrice, $detailPrice);
    }


    public function test_unused_category_can_be_deleted_but_used_category_is_protected(): void
    {
        $admin = Akun::findOrFail('ADM021');

        DB::table('kategori_produk')->insert([
            'id_kategori' => 'KTG999',
            'nama_kategori' => 'Kategori Uji Hapus',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.categories.destroy', 'KTG999'))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseMissing('kategori_produk', [
            'id_kategori' => 'KTG999',
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.categories.destroy', 'KTG001'))
            ->assertSessionHasErrors('kategori');

        $this->assertDatabaseHas('kategori_produk', [
            'id_kategori' => 'KTG001',
        ]);
    }

    public function test_unused_variant_can_be_deleted_but_transaction_variant_is_protected(): void
    {
        $admin = Akun::findOrFail('ADM021');

        DB::table('varian_produk')->insert([
            'id_varian' => 'VAR9999',
            'id_produk' => 'PRD001',
            'ukuran' => 'XXL',
            'warna' => 'uji',
            'stok' => 0,
            'stok_minimum' => 5,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.variants.destroy', 'VAR9999'))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseMissing('varian_produk', [
            'id_varian' => 'VAR9999',
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.variants.destroy', 'VAR0001'))
            ->assertSessionHasErrors('varian');

        $this->assertDatabaseHas('varian_produk', [
            'id_varian' => 'VAR0001',
        ]);
    }

}
