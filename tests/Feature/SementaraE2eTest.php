<?php

namespace Tests\Feature;

use App\Models\Akun;
use Database\Seeders\PrototypeSeeder;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * SEMENTARA (e2e): menguji alur bisnis ujung ke ujung dengan kode sementara
 * (keranjang, checkout, pembayaran simulasi, pesanan, profil) serta perbaikan
 * sementara di seeder, VariantController, dan PurchaseController.
 */
class SementaraE2eTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $sqlite = DB::connection()->getDriverName() === 'sqlite';

        // Migration 150017 memakai ALTER TABLE ... MODIFY (khusus MySQL).
        // Di SQLite dilewati lalu perubahannya diterapkan manual di bawah.
        $paths = collect(glob(database_path('migrations/*.php')))
            ->reject(fn (string $path) => $sqlite && str_ends_with(
                $path,
                '_add_purchase_reference_to_pengeluaran_table.php'
            ))
            ->values()
            ->all();

        $this->assertSame(0, Artisan::call('migrate:fresh', [
            '--path' => $paths,
            '--realpath' => true,
        ]));

        if ($sqlite) {
            Schema::table('pengeluaran', function (Blueprint $table) {
                $table->string('id_pembelian')->nullable();
            });

            Schema::table('pengeluaran', function (Blueprint $table) {
                $table->string('jenis_pengeluaran')->change();
            });
        }

        // Seeder asli dipakai sebagai data; sekaligus memastikan tidak lagi
        // berhenti di bagian bahan_baku.
        $this->seed(PrototypeSeeder::class);
    }

    public function test_customer_buys_online_and_admin_completes_the_order(): void
    {
        $customer = Akun::findOrFail('CUST001');
        $admin = Akun::findOrFail('ADM001');

        $this->insertPromo('PRM001', 20, ['PRD001']);

        // Halaman customer yang sudah ada kini tersambung ke alur sementara
        $this->actingAs($customer)
            ->get(route('customer.dashboard'))
            ->assertOk()
            ->assertSee(route('customer.cart'))
            ->assertSee(route('customer.orders.index'))
            ->assertSee(route('customer.profile'));

        $this->actingAs($customer)
            ->get(route('customer.catalog'))
            ->assertOk()
            ->assertSee(route('customer.cart'));

        $this->actingAs($customer)
            ->get(route('customer.products.show', 'PRD001'))
            ->assertOk()
            ->assertSee(route('customer.cart.store'));

        // Keranjang
        $this->actingAs($customer)
            ->post(route('customer.cart.store'), ['id_varian' => 'VAR001', 'jumlah' => 2])
            ->assertRedirect(route('customer.cart'));

        $this->actingAs($customer)
            ->post(route('customer.cart.store'), ['id_varian' => 'VAR009'])
            ->assertRedirect(route('customer.cart'));

        $this->actingAs($customer)
            ->get(route('customer.cart'))
            ->assertOk()
            ->assertSee('Rp95.200')
            ->assertSee('Rp339.400');

        // Checkout: pesanan menunggu, stok belum berubah
        $this->actingAs($customer)
            ->get(route('customer.checkout'))
            ->assertOk();

        $this->actingAs($customer)
            ->post(route('customer.checkout.store'), ['alamat_pengiriman' => 'Jl. Uji 1, Surabaya'])
            ->assertRedirect(route('customer.payment', 'PJL0001'))
            ->assertSessionMissing('cart');

        $this->assertDatabaseHas('penjualan', [
            'id_penjualan' => 'PJL0001',
            'id_akun' => 'CUST001',
            'kanal_penjualan' => 'ONLINE',
            'status_pembayaran' => 'MENUNGGU',
            'status_penjualan' => 'BARU',
            'nominal_bayar' => 339400,
            'poin_didapat' => 0,
        ]);

        $this->assertDatabaseHas('detail_penjualan', [
            'id_penjualan' => 'PJL0001',
            'id_varian' => 'VAR001',
            'jumlah' => 2,
            'harga_satuan' => 95200,
        ]);

        $this->assertDatabaseHas('penjualan_member', [
            'id_penjualan' => 'PJL0001',
            'id_akun_customer' => 'CUST001',
        ]);

        $this->assertSame(12, $this->stock('VAR001'));
        $this->assertDatabaseCount('mutasi_stok', 0);

        // Pembayaran simulasi berhasil
        $this->actingAs($customer)
            ->get(route('customer.payment', 'PJL0001'))
            ->assertOk();

        $this->actingAs($customer)
            ->post(route('customer.payment.pay', 'PJL0001'))
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('customer.orders.show', 'PJL0001'));

        $this->assertDatabaseHas('penjualan', [
            'id_penjualan' => 'PJL0001',
            'status_pembayaran' => 'BERHASIL',
            'status_penjualan' => 'DIPROSES',
            'poin_didapat' => 33,
        ]);

        $this->assertSame(10, $this->stock('VAR001'));
        $this->assertSame(11, $this->stock('VAR009'));

        $this->assertDatabaseHas('mutasi_stok', [
            'id_varian' => 'VAR001',
            'id_akun' => 'CUST001',
            'jenis_mutasi' => 'KELUAR',
            'jumlah' => 2,
            'stok_sebelum' => 12,
            'stok_sesudah' => 10,
            'sumber' => 'PJL0001',
        ]);

        // Admin memproses pesanan online sampai selesai
        $this->actingAs($admin)
            ->get(route('admin.sales.show', 'PJL0001'))
            ->assertOk()
            ->assertSee('DIKIRIM');

        $this->actingAs($admin)
            ->patch(route('admin.sales.status', 'PJL0001'), ['status_penjualan' => 'DIKIRIM'])
            ->assertSessionHasNoErrors();

        $this->actingAs($admin)
            ->patch(route('admin.sales.status', 'PJL0001'), ['status_penjualan' => 'SELESAI'])
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('penjualan', [
            'id_penjualan' => 'PJL0001',
            'status_penjualan' => 'SELESAI',
        ]);

        // Customer melihat pesanan dan poinnya
        $this->actingAs($customer)
            ->get(route('customer.orders.index'))
            ->assertOk()
            ->assertSee('PJL0001')
            ->assertSee('SELESAI');

        $this->actingAs($customer)
            ->get(route('customer.profile'))
            ->assertOk()
            ->assertSee('33 poin');
    }

    public function test_failed_payment_cancels_order_without_touching_stock(): void
    {
        $customer = Akun::findOrFail('CUST001');
        $this->createPendingOrder($customer, 'VAR001', 1);

        $this->actingAs($customer)
            ->post(route('customer.payment.fail', 'PJL0001'))
            ->assertRedirect(route('customer.orders.show', 'PJL0001'));

        $this->assertDatabaseHas('penjualan', [
            'id_penjualan' => 'PJL0001',
            'status_pembayaran' => 'GAGAL',
            'status_penjualan' => 'BATAL',
            'poin_didapat' => 0,
        ]);

        $this->assertSame(12, $this->stock('VAR001'));
        $this->assertDatabaseCount('mutasi_stok', 0);

        // Pesanan yang sudah gagal tidak bisa dibayar lagi.
        $this->actingAs($customer)
            ->post(route('customer.payment.pay', 'PJL0001'))
            ->assertSessionHasErrors('status');

        $this->assertSame(12, $this->stock('VAR001'));
    }

    public function test_customer_can_cancel_a_pending_order(): void
    {
        $customer = Akun::findOrFail('CUST001');
        $this->createPendingOrder($customer, 'VAR001', 1);

        $this->actingAs($customer)
            ->patch(route('customer.orders.cancel', 'PJL0001'))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('penjualan', [
            'id_penjualan' => 'PJL0001',
            'status_pembayaran' => 'MENUNGGU',
            'status_penjualan' => 'BATAL',
        ]);

        $this->assertSame(12, $this->stock('VAR001'));
    }

    public function test_payment_is_rejected_when_stock_ran_out_after_checkout(): void
    {
        $customer = Akun::findOrFail('CUST001');
        $this->createPendingOrder($customer, 'VAR004', 3);

        // Stok habis dijual kasir sebelum customer membayar.
        DB::table('varian_produk')->where('id_varian', 'VAR004')->update(['stok' => 1]);

        $this->actingAs($customer)
            ->post(route('customer.payment.pay', 'PJL0001'))
            ->assertSessionHasErrors('stok');

        $this->assertDatabaseHas('penjualan', [
            'id_penjualan' => 'PJL0001',
            'status_pembayaran' => 'MENUNGGU',
            'status_penjualan' => 'BARU',
        ]);

        $this->assertSame(1, $this->stock('VAR004'));
        $this->assertDatabaseCount('mutasi_stok', 0);
    }

    public function test_customers_cannot_see_other_orders_or_exceed_stock_in_cart(): void
    {
        $customer = Akun::findOrFail('CUST001');
        $this->createPendingOrder($customer, 'VAR001', 1);

        $this->post('/logout');

        $this->post('/register', [
            'nama' => 'Pembeli Lain',
            'username' => 'pembeli2',
            'no_telp' => '0813',
            'password' => 'rahasia1',
            'password_confirmation' => 'rahasia1',
        ])->assertRedirect(route('customer.dashboard'));

        $other = Akun::where('username', 'pembeli2')->firstOrFail();

        $this->actingAs($other)
            ->get(route('customer.orders.show', 'PJL0001'))
            ->assertNotFound();

        $this->actingAs($other)
            ->post(route('customer.payment.pay', 'PJL0001'))
            ->assertNotFound();

        $this->actingAs($other)
            ->post(route('customer.cart.store'), ['id_varian' => 'VAR004', 'jumlah' => 5])
            ->assertSessionHasErrors('jumlah');
    }

    public function test_admin_can_add_several_variants_and_receiving_stock_logs_mutation(): void
    {
        $admin = Akun::findOrFail('ADM001');

        foreach (['M', 'L'] as $size) {
            $this->actingAs($admin)
                ->post(route('admin.variants.store', 'PRD001'), [
                    'ukuran' => $size,
                    'warna' => 'hijau',
                    'stok_minimum' => 2,
                ])
                ->assertSessionHasNoErrors();
        }

        $this->assertDatabaseHas('varian_produk', ['id_varian' => 'VAR073', 'ukuran' => 'M']);
        $this->assertDatabaseHas('varian_produk', ['id_varian' => 'VAR074', 'ukuran' => 'L']);

        $this->actingAs($admin)
            ->post(route('admin.purchases.store'), [
                'id_supplier' => 'SUP001',
                'id_varian' => ['VAR073'],
                'jumlah' => [20],
                'harga_satuan' => [60000],
            ])
            ->assertSessionHasNoErrors();

        $this->actingAs($admin)
            ->patch(route('admin.purchases.receive', 'PB0001'))
            ->assertSessionHasNoErrors();

        $this->assertSame(20, $this->stock('VAR073'));

        $this->assertDatabaseHas('mutasi_stok', [
            'id_mutasi' => 'MUT00001',
            'id_varian' => 'VAR073',
            'id_akun' => 'ADM001',
            'jenis_mutasi' => 'MASUK',
            'jumlah' => 20,
            'stok_sebelum' => 0,
            'stok_sesudah' => 20,
            'sumber' => 'PB0001',
        ]);

        // Stok hasil pembelian bisa langsung dijual kasir; nomor MUT berlanjut.
        $this->actingAs(Akun::findOrFail('KSR001'))
            ->post(route('kasir.store'), [
                'items' => [['id_varian' => 'VAR073', 'jumlah' => 5]],
                'tipe_pembeli' => 'guest',
                'metode_pembayaran' => 'TUNAI',
            ])
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('mutasi_stok', [
            'id_mutasi' => 'MUT00002',
            'jenis_mutasi' => 'KELUAR',
            'stok_sebelum' => 20,
            'stok_sesudah' => 15,
        ]);
    }

    private function createPendingOrder(Akun $customer, string $variantId, int $qty): void
    {
        $this->actingAs($customer)
            ->post(route('customer.cart.store'), ['id_varian' => $variantId, 'jumlah' => $qty])
            ->assertSessionHasNoErrors();

        $this->actingAs($customer)
            ->post(route('customer.checkout.store'), ['alamat_pengiriman' => 'Surabaya'])
            ->assertRedirect(route('customer.payment', 'PJL0001'));
    }

    private function stock(string $variantId): int
    {
        return (int) DB::table('varian_produk')
            ->where('id_varian', $variantId)
            ->value('stok');
    }

    private function insertPromo(string $id, float $percent, array $productIds): void
    {
        DB::table('promo')->insert([
            'id_promo' => $id,
            'nama_promo' => 'Promo '.$id,
            'persen_diskon' => $percent,
            'tanggal_mulai' => now()->subDay(),
            'tanggal_selesai' => now()->addDay(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        foreach ($productIds as $productId) {
            DB::table('promo_produk')->insert([
                'id_promo' => $id,
                'id_produk' => $productId,
            ]);
        }
    }
}
