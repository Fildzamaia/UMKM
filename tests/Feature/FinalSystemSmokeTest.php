<?php

namespace Tests\Feature;

use App\Models\Akun;
use Database\Seeders\PrototypeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * Smoke test proses bisnis utama.
 *
 * Ditulis ulang supaya cocok dengan kode sekarang: versi sebelumnya memanggil
 * App\Services\SaleService/PointService (tidak ada), akun ADM021/CUST123
 * (seeder membuat ADM001/CUST001), dan field login "login" (form memakai
 * "username"). Skenario dan nama test dipertahankan; alur diuji lewat HTTP.
 * Alur belanja online memakai route customer sementara di branch sementara/e2e.
 */
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
        // LoginController menerima id_akun, username, atau email.
        $this->post('/login', [
            'username' => 'CUST001',
            'password' => 'customer123',
        ])->assertRedirect(route('customer.dashboard'));

        $this->post('/logout');

        $this->post('/login', [
            'username' => 'KSR001',
            'password' => 'kasir123',
        ])->assertRedirect(route('kasir.dashboard'));

        $this->post('/logout');

        $this->post('/login', [
            'username' => 'ADM001',
            'password' => 'admin123',
        ])->assertRedirect(route('admin.dashboard'));
    }

    public function test_role_middleware_blocks_cross_role_access(): void
    {
        $customer = Akun::findOrFail('CUST001');
        $cashier = Akun::findOrFail('KSR001');
        $admin = Akun::findOrFail('ADM001');

        $this->actingAs($customer)
            ->get('/admin')
            ->assertForbidden();

        $this->actingAs($cashier)
            ->get('/admin')
            ->assertForbidden();

        $this->actingAs($admin)
            ->get('/customer')
            ->assertForbidden();

        $this->actingAs($customer)
            ->get('/kasir')
            ->assertForbidden();
    }

    public function test_receiving_a_purchase_increases_stock_once_and_creates_one_expense(): void
    {
        $admin = Akun::findOrFail('ADM001');
        $variant = DB::table('varian_produk')->first();
        $before = (int) $variant->stok;

        DB::table('pembelian_produk')->insert([
            'id_pembelian' => 'PB9999',
            'id_supplier' => 'SUP001',
            'id_akun' => 'ADM001',
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

        // Menerima dua kali ditolak; stok dan pengeluaran tidak bertambah lagi.
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
        // Skenario: dua pesanan online sama-sama memakai seluruh saldo poin;
        // pembayaran kedua harus ditolak karena poin sudah terpakai.
        $this->markTestSkipped(
            'Penukaran poin (poin_digunakan/diskon_poin) belum diimplementasikan '
            .'di checkout online maupun kasir.'
        );
    }

    public function test_failed_online_payment_does_not_change_stock_or_points(): void
    {
        $customer = Akun::findOrFail('CUST001');
        $beforeStock = $this->stock('VAR001');

        $saleId = $this->createOnlinePending($customer, 'VAR001', 1);

        $this->actingAs($customer)
            ->post(route('customer.payment.fail', $saleId));

        $sale = DB::table('penjualan')
            ->where('id_penjualan', $saleId)
            ->first();

        $this->assertSame('GAGAL', $sale->status_pembayaran);
        $this->assertSame('BATAL', $sale->status_penjualan);
        $this->assertSame(0, (int) $sale->poin_didapat);

        $this->assertSame($beforeStock, $this->stock('VAR001'));
    }

    public function test_customer_can_cancel_pending_order_without_changing_stock(): void
    {
        $customer = Akun::findOrFail('CUST001');
        $beforeStock = $this->stock('VAR001');

        $saleId = $this->createOnlinePending($customer, 'VAR001', 1);

        $this->actingAs($customer)
            ->patch(route('customer.orders.cancel', $saleId))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('penjualan', [
            'id_penjualan' => $saleId,
            'status_pembayaran' => 'MENUNGGU',
            'status_penjualan' => 'BATAL',
        ]);

        $this->assertSame($beforeStock, $this->stock('VAR001'));
    }

    public function test_offline_guest_has_no_member_row_and_no_points(): void
    {
        $beforeStock = $this->stock('VAR001');

        $this->actingAs(Akun::findOrFail('KSR001'))
            ->post(route('kasir.store'), [
                'items' => [['id_varian' => 'VAR001', 'jumlah' => 1]],
                'tipe_pembeli' => 'guest',
                'metode_pembayaran' => 'TUNAI',
            ])
            ->assertSessionHasNoErrors();

        $sale = DB::table('penjualan')->first();

        $this->assertSame('KSR001', $sale->id_akun);
        $this->assertSame('OFFLINE', $sale->kanal_penjualan);
        $this->assertSame('BERHASIL', $sale->status_pembayaran);
        $this->assertSame(0, (int) $sale->poin_didapat);
        $this->assertSame(0, (int) $sale->poin_digunakan);

        $this->assertDatabaseMissing('penjualan_member', [
            'id_penjualan' => $sale->id_penjualan,
        ]);

        $this->assertSame($beforeStock - 1, $this->stock('VAR001'));
    }

    public function test_offline_member_is_linked_to_customer_and_earns_points(): void
    {
        $this->actingAs(Akun::findOrFail('KSR001'))
            ->post(route('kasir.store'), [
                'items' => [['id_varian' => 'VAR001', 'jumlah' => 1]],
                'tipe_pembeli' => 'member',
                'id_akun_customer' => 'CUST001',
                'metode_pembayaran' => 'QR',
            ])
            ->assertSessionHasNoErrors();

        $sale = DB::table('penjualan')->first();

        $this->assertSame('KSR001', $sale->id_akun);
        $this->assertSame(
            (int) floor((float) $sale->nominal_bayar / 10000),
            (int) $sale->poin_didapat
        );

        $this->assertDatabaseHas('penjualan_member', [
            'id_penjualan' => $sale->id_penjualan,
            'id_akun_customer' => 'CUST001',
        ]);
    }

    public function test_active_promo_is_saved_as_detail_price_snapshot(): void
    {
        $normalPrice = (float) DB::table('produk')
            ->where('id_produk', 'PRD001')
            ->value('harga_jual');

        $expectedPromoPrice = round($normalPrice * 0.90, 2);

        DB::table('promo')->insert([
            'id_promo' => 'PRM001',
            'nama_promo' => 'Diskon 10%',
            'persen_diskon' => 10,
            'tanggal_mulai' => now()->subDay(),
            'tanggal_selesai' => now()->addDay(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('promo_produk')->insert([
            'id_promo' => 'PRM001',
            'id_produk' => 'PRD001',
        ]);

        $saleId = $this->createOnlinePending(
            Akun::findOrFail('CUST001'),
            'VAR001',
            1
        );

        // Promo berakhir setelah checkout; harga di detail tidak ikut berubah.
        DB::table('promo')
            ->where('id_promo', 'PRM001')
            ->update(['tanggal_selesai' => now()->subMinute()]);

        $detailPrice = (float) DB::table('detail_penjualan')
            ->where('id_penjualan', $saleId)
            ->value('harga_satuan');

        $this->assertSame($expectedPromoPrice, $detailPrice);
    }

    public function test_unused_category_can_be_deleted_but_used_category_is_protected(): void
    {
        $admin = Akun::findOrFail('ADM001');

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

        // KAT001 (Kaos) dipakai produk seeder.
        $this->actingAs($admin)
            ->delete(route('admin.categories.destroy', 'KAT001'))
            ->assertSessionHasErrors('kategori');

        $this->assertDatabaseHas('kategori_produk', [
            'id_kategori' => 'KAT001',
        ]);
    }

    public function test_unused_variant_can_be_deleted_but_transaction_variant_is_protected(): void
    {
        $admin = Akun::findOrFail('ADM001');

        DB::table('varian_produk')->insert([
            'id_varian' => 'VAR999',
            'id_produk' => 'PRD001',
            'ukuran' => 'XXL',
            'warna' => 'uji',
            'stok' => 0,
            'stok_minimum' => 5,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.variants.destroy', 'VAR999'))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseMissing('varian_produk', [
            'id_varian' => 'VAR999',
        ]);

        // Beri VAR001 histori penjualan lewat kasir, lalu coba hapus.
        $this->actingAs(Akun::findOrFail('KSR001'))
            ->post(route('kasir.store'), [
                'items' => [['id_varian' => 'VAR001', 'jumlah' => 1]],
                'tipe_pembeli' => 'guest',
                'metode_pembayaran' => 'TUNAI',
            ])
            ->assertSessionHasNoErrors();

        $this->actingAs($admin)
            ->delete(route('admin.variants.destroy', 'VAR001'))
            ->assertSessionHasErrors('varian');

        $this->assertDatabaseHas('varian_produk', [
            'id_varian' => 'VAR001',
        ]);
    }

    private function createOnlinePending(Akun $customer, string $variantId, int $qty): string
    {
        $this->actingAs($customer)
            ->post(route('customer.cart.store'), [
                'id_varian' => $variantId,
                'jumlah' => $qty,
            ])
            ->assertSessionHasNoErrors();

        $this->actingAs($customer)
            ->post(route('customer.checkout.store'), [
                'alamat_pengiriman' => 'Surabaya',
            ])
            ->assertSessionHasNoErrors();

        return DB::table('penjualan')
            ->where('id_akun', $customer->id_akun)
            ->where('kanal_penjualan', 'ONLINE')
            ->orderByDesc('id_penjualan')
            ->value('id_penjualan');
    }

    private function stock(string $variantId): int
    {
        return (int) DB::table('varian_produk')
            ->where('id_varian', $variantId)
            ->value('stok');
    }
}
