<?php

namespace Tests\Feature;

use App\Models\Akun;
use App\Models\MutasiStok;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use RuntimeException;
use Tests\TestCase;

class KasirPosTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seedFixtures();
    }

    public function test_guest_sale_saves_header_details_and_stock_out_mutations(): void
    {
        $response = $this->checkout([
            'items' => [
                ['id_varian' => 'VAR001', 'jumlah' => 2],
                ['id_varian' => 'VAR002', 'jumlah' => 1],
            ],
            'tipe_pembeli' => 'guest',
            'metode_pembayaran' => 'TUNAI',
        ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('kasir.struk', 'PJL0001'));

        $this->assertDatabaseHas('penjualan', [
            'id_penjualan' => 'PJL0001',
            'id_akun' => 'KSR001',
            'kanal_penjualan' => 'OFFLINE',
            'metode_pembayaran' => 'TUNAI',
            'status_pembayaran' => 'BERHASIL',
            'status_penjualan' => 'SELESAI',
            'nominal_bayar' => 288000,
            'poin_didapat' => 0,
        ]);

        $this->assertDatabaseHas('detail_penjualan', [
            'id_detail_penjualan' => 'DPJ00001',
            'id_penjualan' => 'PJL0001',
            'id_varian' => 'VAR001',
            'jumlah' => 2,
            'harga_satuan' => 119000,
        ]);

        $this->assertDatabaseHas('detail_penjualan', [
            'id_detail_penjualan' => 'DPJ00002',
            'id_penjualan' => 'PJL0001',
            'id_varian' => 'VAR002',
            'jumlah' => 1,
            'harga_satuan' => 50000,
        ]);

        $this->assertDatabaseCount('penjualan_member', 0);

        $this->assertSame(8, $this->stock('VAR001'));
        $this->assertSame(4, $this->stock('VAR002'));

        $this->assertDatabaseCount('mutasi_stok', 2);

        $this->assertDatabaseHas('mutasi_stok', [
            'id_mutasi' => 'MUT00001',
            'id_varian' => 'VAR001',
            'id_akun' => 'KSR001',
            'jenis_mutasi' => 'KELUAR',
            'jumlah' => 2,
            'stok_sebelum' => 10,
            'stok_sesudah' => 8,
            'sumber' => 'PJL0001',
        ]);

        $this->assertDatabaseHas('mutasi_stok', [
            'id_mutasi' => 'MUT00002',
            'id_varian' => 'VAR002',
            'id_akun' => 'KSR001',
            'jenis_mutasi' => 'KELUAR',
            'jumlah' => 1,
            'stok_sebelum' => 5,
            'stok_sesudah' => 4,
            'sumber' => 'PJL0001',
        ]);
    }

    public function test_member_sale_creates_member_row_and_awards_points(): void
    {
        $this->checkout([
            'items' => [
                ['id_varian' => 'VAR001', 'jumlah' => 1],
                ['id_varian' => 'VAR002', 'jumlah' => 1],
            ],
            'tipe_pembeli' => 'member',
            'id_akun_customer' => 'CUST001',
            'metode_pembayaran' => 'QR',
            'referensi_pembayaran' => 'QRIS-123',
        ])
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('kasir.struk', 'PJL0001'));

        // 119.000 + 50.000 = 169.000 → floor(169.000 / 10.000) = 16 poin
        $this->assertDatabaseHas('penjualan', [
            'id_penjualan' => 'PJL0001',
            'metode_pembayaran' => 'QR',
            'referensi_pembayaran' => 'QRIS-123',
            'nominal_bayar' => 169000,
            'poin_didapat' => 16,
            'poin_digunakan' => 0,
        ]);

        $this->assertDatabaseHas('penjualan_member', [
            'id_penjualan' => 'PJL0001',
            'id_akun_customer' => 'CUST001',
        ]);

        $this->assertDatabaseHas('mutasi_stok', [
            'id_varian' => 'VAR001',
            'jenis_mutasi' => 'KELUAR',
            'jumlah' => 1,
            'stok_sebelum' => 10,
            'stok_sesudah' => 9,
            'sumber' => 'PJL0001',
        ]);

        $this->assertDatabaseHas('mutasi_stok', [
            'id_varian' => 'VAR002',
            'jenis_mutasi' => 'KELUAR',
            'jumlah' => 1,
            'stok_sebelum' => 5,
            'stok_sesudah' => 4,
            'sumber' => 'PJL0001',
        ]);
    }

    public function test_member_sale_requires_an_existing_customer_account(): void
    {
        $payload = [
            'items' => [
                ['id_varian' => 'VAR001', 'jumlah' => 1],
            ],
            'tipe_pembeli' => 'member',
            'metode_pembayaran' => 'TUNAI',
        ];

        $this->checkout($payload)
            ->assertSessionHasErrors('id_akun_customer');

        $this->checkout($payload + ['id_akun_customer' => 'KSR002'])
            ->assertSessionHasErrors('id_akun_customer');

        $this->assertNothingSaved();
    }

    public function test_insufficient_stock_rejects_the_whole_sale(): void
    {
        $this->checkout([
            'items' => [
                ['id_varian' => 'VAR001', 'jumlah' => 2],
                ['id_varian' => 'VAR002', 'jumlah' => 6],
            ],
            'tipe_pembeli' => 'member',
            'id_akun_customer' => 'CUST001',
            'metode_pembayaran' => 'TUNAI',
        ])->assertSessionHasErrors('items');

        $this->checkout([
            'items' => [
                ['id_varian' => 'VAR003', 'jumlah' => 1],
            ],
            'tipe_pembeli' => 'guest',
            'metode_pembayaran' => 'TUNAI',
        ])->assertSessionHasErrors('items');

        // Varian dari produk NONAKTIF juga ditolak.
        $this->checkout([
            'items' => [
                ['id_varian' => 'VAR004', 'jumlah' => 1],
            ],
            'tipe_pembeli' => 'guest',
            'metode_pembayaran' => 'TUNAI',
        ])->assertSessionHasErrors('items');

        $this->assertNothingSaved();
    }

    public function test_failure_midway_rolls_back_every_write_including_mutations(): void
    {
        // Gagal saat mencatat mutasi item kedua: header, detail, member,
        // pengurangan stok dan mutasi item pertama sudah tertulis.
        MutasiStok::creating(function (MutasiStok $mutation) {
            if ($mutation->id_varian === 'VAR002') {
                throw new RuntimeException('Simulasi kegagalan database.');
            }
        });

        $this->checkout([
            'items' => [
                ['id_varian' => 'VAR001', 'jumlah' => 2],
                ['id_varian' => 'VAR002', 'jumlah' => 1],
            ],
            'tipe_pembeli' => 'member',
            'id_akun_customer' => 'CUST001',
            'metode_pembayaran' => 'TUNAI',
        ])->assertServerError();

        $this->assertNothingSaved();
    }

    public function test_active_promo_reduces_the_unit_price(): void
    {
        $this->insertPromo('PRM001', 'Diskon Kaos', 20, now()->subDay(), now()->addDay(), ['PRD001']);
        $this->insertPromo('PRM002', 'Diskon Kecil', 10, now()->subDay(), now()->addDay(), ['PRD001']);
        $this->insertPromo('PRM003', 'Promo Kedaluwarsa', 50, now()->subDays(10), now()->subDay(), ['PRD002']);
        $this->insertPromo('PRM004', 'Promo Mendatang', 30, now()->addDay(), now()->addDays(5), ['PRD002']);

        $this->actingAs(Akun::findOrFail('KSR001'))
            ->get(route('kasir.pos'))
            ->assertOk()
            ->assertSee('Diskon Kaos')
            ->assertSee('Rp95.200')
            ->assertDontSee('Promo Kedaluwarsa')
            ->assertDontSee('Promo Mendatang');

        $this->checkout([
            'items' => [
                ['id_varian' => 'VAR001', 'jumlah' => 2],
                ['id_varian' => 'VAR002', 'jumlah' => 1],
            ],
            'tipe_pembeli' => 'guest',
            'metode_pembayaran' => 'TUNAI',
        ])->assertSessionHasNoErrors();

        // PRD001: 119.000 - 20% (diskon terbesar yang aktif) = 95.200
        $this->assertDatabaseHas('detail_penjualan', [
            'id_varian' => 'VAR001',
            'harga_satuan' => 95200,
        ]);

        // PRD002: promo kedaluwarsa dan yang belum mulai tidak berlaku
        $this->assertDatabaseHas('detail_penjualan', [
            'id_varian' => 'VAR002',
            'harga_satuan' => 50000,
        ]);

        $this->assertDatabaseHas('penjualan', [
            'id_penjualan' => 'PJL0001',
            'nominal_bayar' => 240400,
        ]);
    }

    public function test_riwayat_only_lists_offline_sales_of_the_logged_in_cashier(): void
    {
        $guestSale = [
            'items' => [
                ['id_varian' => 'VAR001', 'jumlah' => 1],
            ],
            'tipe_pembeli' => 'guest',
            'metode_pembayaran' => 'TUNAI',
        ];

        $this->checkout($guestSale, 'KSR001')
            ->assertRedirect(route('kasir.struk', 'PJL0001'));

        $this->checkout($guestSale, 'KSR002')
            ->assertRedirect(route('kasir.struk', 'PJL0002'));

        // ID lanjut dari nomor terakhir, termasuk detail dan mutasi.
        $this->assertDatabaseHas('mutasi_stok', [
            'id_mutasi' => 'MUT00002',
            'id_akun' => 'KSR002',
            'stok_sebelum' => 9,
            'stok_sesudah' => 8,
            'sumber' => 'PJL0002',
        ]);

        DB::table('penjualan')->insert([
            'id_penjualan' => 'ONL0001',
            'id_akun' => 'KSR001',
            'tanggal_penjualan' => now(),
            'kanal_penjualan' => 'ONLINE',
            'metode_pembayaran' => 'QR',
            'status_pembayaran' => 'BERHASIL',
            'nominal_bayar' => 119000,
            'status_penjualan' => 'DIPROSES',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $kasir = Akun::findOrFail('KSR001');

        // Buang flash "Transaksi PJL0002 berhasil" dari request KSR002 di atas;
        // di browser, tiap kasir punya session sendiri.
        $this->flushSession();

        $this->actingAs($kasir)
            ->get(route('kasir.riwayat'))
            ->assertOk()
            ->assertSee('PJL0001')
            ->assertDontSee('PJL0002')
            ->assertDontSee('ONL0001');

        $this->actingAs($kasir)
            ->get(route('kasir.struk', 'PJL0001'))
            ->assertOk()
            ->assertSee('Basic T-Shirt');

        $this->actingAs($kasir)
            ->get(route('kasir.struk', 'PJL0002'))
            ->assertNotFound();
    }

    public function test_pos_lists_active_products_and_disables_empty_variants(): void
    {
        $html = $this->actingAs(Akun::findOrFail('KSR001'))
            ->get(route('kasir.pos'))
            ->assertOk()
            ->assertSee('Basic T-Shirt')
            ->assertSee('Cargo Pants')
            ->assertDontSee('Produk Lama')
            ->assertSee('Habis')
            ->getContent();

        $this->assertMatchesRegularExpression(
            '/data-id="VAR003"[^>]*\sdisabled/',
            $html
        );

        $this->assertDoesNotMatchRegularExpression(
            '/data-id="VAR001"[^>]*\sdisabled/',
            $html
        );
    }

    public function test_only_kasir_accounts_can_open_kasir_pages(): void
    {
        $this->get(route('kasir.pos'))
            ->assertRedirect(route('login'));

        foreach (['ADM001', 'CUST001'] as $accountId) {
            $account = Akun::findOrFail($accountId);

            $this->actingAs($account)
                ->get(route('kasir.pos'))
                ->assertForbidden();

            $this->actingAs($account)
                ->get(route('kasir.riwayat'))
                ->assertForbidden();

            $this->actingAs($account)
                ->post(route('kasir.store'), [])
                ->assertForbidden();
        }

        $this->actingAs(Akun::findOrFail('KSR001'))
            ->get(route('kasir.dashboard'))
            ->assertOk()
            ->assertSee(route('kasir.pos'))
            ->assertSee(route('kasir.riwayat'));

        $this->assertNothingSaved();
    }

    private function checkout(array $payload, string $kasirId = 'KSR001')
    {
        return $this->actingAs(Akun::findOrFail($kasirId))
            ->post(route('kasir.store'), $payload);
    }

    private function stock(string $variantId): int
    {
        return (int) DB::table('varian_produk')
            ->where('id_varian', $variantId)
            ->value('stok');
    }

    private function assertNothingSaved(): void
    {
        $this->assertDatabaseCount('penjualan', 0);
        $this->assertDatabaseCount('detail_penjualan', 0);
        $this->assertDatabaseCount('penjualan_member', 0);
        $this->assertDatabaseCount('mutasi_stok', 0);

        $this->assertSame(10, $this->stock('VAR001'));
        $this->assertSame(5, $this->stock('VAR002'));
        $this->assertSame(0, $this->stock('VAR003'));
    }

    private function insertPromo(
        string $id,
        string $name,
        float $percent,
        $start,
        $end,
        array $productIds
    ): void {
        DB::table('promo')->insert([
            'id_promo' => $id,
            'nama_promo' => $name,
            'persen_diskon' => $percent,
            'tanggal_mulai' => $start,
            'tanggal_selesai' => $end,
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

    private function seedFixtures(): void
    {
        $now = now();
        $password = Hash::make('secret123');

        foreach ([
            ['KSR001', 'Kasir Satu', 'kasir1', 'KASIR'],
            ['KSR002', 'Kasir Dua', 'kasir2', 'KASIR'],
            ['CUST001', 'Customer Member', 'member1', 'CUSTOMER'],
            ['ADM001', 'Admin Utama', 'admin1', 'ADMIN'],
        ] as [$id, $name, $username, $type]) {
            DB::table('akun')->insert([
                'id_akun' => $id,
                'nama' => $name,
                'username' => $username,
                'password_hash' => $password,
                'email' => $username.'@umkm.test',
                'no_telp' => '0812000000',
                'tipe_akun' => $type,
                'status_akun' => 'AKTIF',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        DB::table('kategori_produk')->insert([
            ['id_kategori' => 'KAT001', 'nama_kategori' => 'Kaos', 'created_at' => $now, 'updated_at' => $now],
            ['id_kategori' => 'KAT002', 'nama_kategori' => 'Celana', 'created_at' => $now, 'updated_at' => $now],
        ]);

        DB::table('produk')->insert([
            ['id_produk' => 'PRD001', 'id_kategori' => 'KAT001', 'nama_produk' => 'Basic T-Shirt', 'harga_jual' => 119000, 'status_produk' => 'AKTIF', 'created_at' => $now, 'updated_at' => $now],
            ['id_produk' => 'PRD002', 'id_kategori' => 'KAT002', 'nama_produk' => 'Cargo Pants', 'harga_jual' => 50000, 'status_produk' => 'AKTIF', 'created_at' => $now, 'updated_at' => $now],
            ['id_produk' => 'PRD003', 'id_kategori' => 'KAT001', 'nama_produk' => 'Produk Lama', 'harga_jual' => 75000, 'status_produk' => 'NONAKTIF', 'created_at' => $now, 'updated_at' => $now],
        ]);

        DB::table('varian_produk')->insert([
            ['id_varian' => 'VAR001', 'id_produk' => 'PRD001', 'ukuran' => 'M', 'warna' => 'hitam', 'stok' => 10, 'stok_minimum' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['id_varian' => 'VAR002', 'id_produk' => 'PRD002', 'ukuran' => 'L', 'warna' => 'khaki', 'stok' => 5, 'stok_minimum' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['id_varian' => 'VAR003', 'id_produk' => 'PRD002', 'ukuran' => 'XL', 'warna' => 'khaki', 'stok' => 0, 'stok_minimum' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['id_varian' => 'VAR004', 'id_produk' => 'PRD003', 'ukuran' => 'M', 'warna' => 'putih', 'stok' => 3, 'stok_minimum' => 2, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
