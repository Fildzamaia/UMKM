<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class PrototypeSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        /*
        |--------------------------------------------------------------------------
        | AKUN
        |--------------------------------------------------------------------------
        */

        $akun = [
            [
                'id_akun' => 'ADM001',
                'nama' => 'Admin Utama',
                'username' => 'admin',
                'password_hash' => Hash::make('admin123'),
                'email' => 'admin@umkm.test',
                'no_telp' => '081234567801',
                'alamat' => 'Surabaya',
                'tipe_akun' => 'ADMIN',
                'status_akun' => 'AKTIF',
            ],
            [
                'id_akun' => 'KSR001',
                'nama' => 'Kasir Utama',
                'username' => 'kasir',
                'password_hash' => Hash::make('kasir123'),
                'email' => 'kasir@umkm.test',
                'no_telp' => '081234567802',
                'alamat' => 'Surabaya',
                'tipe_akun' => 'KASIR',
                'status_akun' => 'AKTIF',
            ],
            [
                'id_akun' => 'CUST001',
                'nama' => 'Customer Demo',
                'username' => 'customer',
                'password_hash' => Hash::make('customer123'),
                'email' => 'customer@umkm.test',
                'no_telp' => '081234567803',
                'alamat' => 'Surabaya',
                'tipe_akun' => 'CUSTOMER',
                'status_akun' => 'AKTIF',
            ],
        ];

        foreach ($akun as $data) {
            DB::table('akun')->updateOrInsert(
                ['id_akun' => $data['id_akun']],
                array_merge($data, [
                    'created_at' => $now,
                    'updated_at' => $now,
                ])
            );
        }

        /*
        |--------------------------------------------------------------------------
        | KATEGORI PRODUK
        |--------------------------------------------------------------------------
        */

        $kategori = [
            ['id_kategori' => 'KAT001', 'nama_kategori' => 'Kaos'],
            ['id_kategori' => 'KAT002', 'nama_kategori' => 'Hoodie'],
            ['id_kategori' => 'KAT003', 'nama_kategori' => 'Sweater'],
            ['id_kategori' => 'KAT004', 'nama_kategori' => 'Kemeja'],
            ['id_kategori' => 'KAT005', 'nama_kategori' => 'Celana'],
        ];

        foreach ($kategori as $data) {
            DB::table('kategori_produk')->updateOrInsert(
                ['id_kategori' => $data['id_kategori']],
                array_merge($data, [
                    'created_at' => $now,
                    'updated_at' => $now,
                ])
            );
        }

        /*
        |--------------------------------------------------------------------------
        | PRODUK
        |--------------------------------------------------------------------------
        |
        | Produk ke-10 (Long Sleeve T-Shirt) sengaja BELUM dimasukkan.
        | Produk tersebut akan digunakan untuk demo tambah produk oleh Admin.
        |
        */

        $produk = [
            [
                'id_produk' => 'PRD001',
                'id_kategori' => 'KAT001',
                'nama_produk' => 'Basic T-Shirt',
                'harga_jual' => 119000,
                'deskripsi' => 'Kaos basic nyaman untuk penggunaan sehari-hari.',
                'gambar_produk' => 'images/products/basic-tshirt.jpg',
                'warna' => ['hitam', 'putih'],
            ],
            [
                'id_produk' => 'PRD002',
                'id_kategori' => 'KAT001',
                'nama_produk' => 'Oversized T-Shirt',
                'harga_jual' => 149000,
                'deskripsi' => 'Kaos oversized dengan potongan modern.',
                'gambar_produk' => 'images/products/oversized-tshirt.jpg',
                'warna' => ['hitam', 'navy'],
            ],
            [
                'id_produk' => 'PRD003',
                'id_kategori' => 'KAT001',
                'nama_produk' => 'Pocket T-Shirt',
                'harga_jual' => 139000,
                'deskripsi' => 'Kaos casual dengan detail pocket.',
                'gambar_produk' => 'images/products/pocket-tshirt.jpg',
                'warna' => ['putih', 'abu'],
            ],
            [
                'id_produk' => 'PRD004',
                'id_kategori' => 'KAT002',
                'nama_produk' => 'Pullover Hoodie',
                'harga_jual' => 259000,
                'deskripsi' => 'Hoodie pullover untuk gaya casual.',
                'gambar_produk' => 'images/products/hoodie.jpg',
                'warna' => ['hitam', 'abu'],
            ],
            [
                'id_produk' => 'PRD005',
                'id_kategori' => 'KAT003',
                'nama_produk' => 'Crewneck Sweatshirt',
                'harga_jual' => 229000,
                'deskripsi' => 'Sweatshirt crewneck berbahan nyaman.',
                'gambar_produk' => 'images/products/crewneck.jpg',
                'warna' => ['hitam', 'navy'],
            ],
            [
                'id_produk' => 'PRD006',
                'id_kategori' => 'KAT004',
                'nama_produk' => 'Casual Shirt',
                'harga_jual' => 189000,
                'deskripsi' => 'Kemeja casual untuk aktivitas sehari-hari.',
                'gambar_produk' => 'images/products/casual-shirt.jpg',
                'warna' => ['putih', 'navy'],
            ],
            [
                'id_produk' => 'PRD007',
                'id_kategori' => 'KAT004',
                'nama_produk' => 'Overshirt',
                'harga_jual' => 219000,
                'deskripsi' => 'Overshirt casual dengan material twill.',
                'gambar_produk' => 'images/products/overshirt.jpg',
                'warna' => ['hitam', 'khaki'],
            ],
            [
                'id_produk' => 'PRD008',
                'id_kategori' => 'KAT005',
                'nama_produk' => 'Cargo Pants',
                'harga_jual' => 229000,
                'deskripsi' => 'Celana cargo dengan desain fungsional.',
                'gambar_produk' => 'images/products/cargo-pants.jpg',
                'warna' => ['hitam', 'khaki'],
            ],
            [
                'id_produk' => 'PRD009',
                'id_kategori' => 'KAT005',
                'nama_produk' => 'Straight Pants',
                'harga_jual' => 209000,
                'deskripsi' => 'Celana straight fit untuk gaya minimalis.',
                'gambar_produk' => 'images/products/straight-pants.jpg',
                'warna' => ['hitam', 'khaki'],
            ],
        ];

        foreach ($produk as $data) {
            DB::table('produk')->updateOrInsert(
                ['id_produk' => $data['id_produk']],
                [
                    'id_kategori' => $data['id_kategori'],
                    'nama_produk' => $data['nama_produk'],
                    'harga_jual' => $data['harga_jual'],
                    'deskripsi' => $data['deskripsi'],
                    'gambar_produk' => $data['gambar_produk'],
                    'status_produk' => 'AKTIF',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | VARIAN PRODUK
        |--------------------------------------------------------------------------
        */

        $ukuran = ['S', 'M', 'L', 'XL'];

        $stokPerUkuran = [
            'S' => 12,
            'M' => 10,
            'L' => 7,
            'XL' => 4,
        ];

        $nomorVarian = 1;

        foreach ($produk as $item) {
            foreach ($item['warna'] as $warna) {
                foreach ($ukuran as $size) {

                    $idVarian = 'VAR' . str_pad(
                        $nomorVarian,
                        3,
                        '0',
                        STR_PAD_LEFT
                    );

                    DB::table('varian_produk')->updateOrInsert(
                        ['id_varian' => $idVarian],
                        [
                            'id_produk' => $item['id_produk'],
                            'ukuran' => $size,
                            'warna' => strtolower($warna),
                            'stok' => $stokPerUkuran[$size],
                            'stok_minimum' => 5,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ]
                    );

                    $nomorVarian++;
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | SUPPLIER
        |--------------------------------------------------------------------------
        */

        $supplier = [
            [
                'id_supplier' => 'SUP001',
                'nama_supplier' => 'Surabaya Textile',
                'no_telp' => '081111111111',
                'alamat' => 'Surabaya',
            ],
            [
                'id_supplier' => 'SUP002',
                'nama_supplier' => 'Nusantara Fabric',
                'no_telp' => '082222222222',
                'alamat' => 'Sidoarjo',
            ],
            [
                'id_supplier' => 'SUP003',
                'nama_supplier' => 'Jaya Garment Supply',
                'no_telp' => '083333333333',
                'alamat' => 'Gresik',
            ],
        ];

        foreach ($supplier as $data) {
            DB::table('supplier')->updateOrInsert(
                ['id_supplier' => $data['id_supplier']],
                array_merge($data, [
                    'created_at' => $now,
                    'updated_at' => $now,
                ])
            );
        }

        /*
        |--------------------------------------------------------------------------
        | BAHAN BAKU
        |--------------------------------------------------------------------------
        */

        // SEMENTARA (e2e): migration bahan_baku sudah dihapus dari skema, jadi
        // bagian ini dilewati supaya migrate:fresh --seed tidak berhenti di sini.
        // Pemilik seeder perlu menghapus bagian bahan baku secara permanen.
        if (! Schema::hasTable('bahan_baku')) {
            $this->command?->warn(
                'PrototypeSeeder: tabel bahan_baku tidak ada, bagian bahan baku dilewati.'
            );

            return;
        }

        $bahan = [];

        $warnaCotton = ['hitam', 'putih', 'navy', 'abu', 'merah'];

        foreach ($warnaCotton as $warna) {
            $bahan[] = [
                'nama_bahan' => 'Cotton Combed',
                'warna' => $warna,
                'satuan' => 'meter',
            ];

            $bahan[] = [
                'nama_bahan' => 'Rib',
                'warna' => $warna,
                'satuan' => 'meter',
            ];
        }

        foreach (['hitam', 'navy', 'abu'] as $warna) {
            $bahan[] = [
                'nama_bahan' => 'French Terry',
                'warna' => $warna,
                'satuan' => 'meter',
            ];
        }

        foreach (['putih', 'navy'] as $warna) {
            $bahan[] = [
                'nama_bahan' => 'Poplin',
                'warna' => $warna,
                'satuan' => 'meter',
            ];
        }

        foreach (['hitam', 'khaki'] as $warna) {
            $bahan[] = [
                'nama_bahan' => 'Twill',
                'warna' => $warna,
                'satuan' => 'meter',
            ];
        }

        $bahanNetral = [
            ['nama_bahan' => 'Kancing', 'satuan' => 'pcs'],
            ['nama_bahan' => 'Zipper', 'satuan' => 'pcs'],
            ['nama_bahan' => 'Tali Hoodie', 'satuan' => 'pcs'],
            ['nama_bahan' => 'Label', 'satuan' => 'pcs'],
        ];

        foreach ($bahanNetral as $item) {
            $bahan[] = [
                'nama_bahan' => $item['nama_bahan'],
                'warna' => 'netral',
                'satuan' => $item['satuan'],
            ];
        }

        $nomorBahan = 1;

        foreach ($bahan as $data) {
            $idBahan = 'BHN' . str_pad(
                $nomorBahan,
                3,
                '0',
                STR_PAD_LEFT
            );

            DB::table('bahan_baku')->updateOrInsert(
                ['id_bahan' => $idBahan],
                [
                    'nama_bahan' => $data['nama_bahan'],
                    'warna' => strtolower($data['warna']),
                    'satuan' => $data['satuan'],
                    'stok' => 100,
                    'stok_minimum' => 20,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );

            $nomorBahan++;
        }

        $this->command->info('PrototypeSeeder berhasil dijalankan.');
    }
}