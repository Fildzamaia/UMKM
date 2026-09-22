<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index()
    {
        $products = DB::table('produk')
            ->join(
                'kategori_produk',
                'produk.id_kategori',
                '=',
                'kategori_produk.id_kategori'
            )
            ->leftJoin(
                'varian_produk',
                'produk.id_produk',
                '=',
                'varian_produk.id_produk'
            )
            ->select(
                'produk.id_produk',
                'produk.nama_produk',
                'produk.harga_jual',
                'produk.gambar_produk',
                'produk.status_produk',
                'kategori_produk.nama_kategori',

                DB::raw(
                    'COUNT(varian_produk.id_varian) as total_varian'
                ),

                DB::raw(
                    'COALESCE(SUM(varian_produk.stok), 0) as total_stok'
                )
            )
            ->groupBy(
                'produk.id_produk',
                'produk.nama_produk',
                'produk.harga_jual',
                'produk.gambar_produk',
                'produk.status_produk',
                'kategori_produk.nama_kategori'
            )
            ->orderBy('produk.id_produk')
            ->get();

        return view(
            'admin.products.index',
            compact('products')
        );
    }


    public function create()
    {
        $categories = DB::table('kategori_produk')
            ->orderBy('nama_kategori')
            ->get();

        return view(
            'admin.products.create',
            compact('categories')
        );
    }

    public function variants(string $idProduk)
    {
        $product = DB::table('produk')
            ->where(
                'id_produk',
                $idProduk
            )
            ->first();

        abort_if(!$product, 404);

        $variants = DB::table('varian_produk')
            ->where(
                'id_produk',
                $idProduk
            )
            ->orderBy('warna')
            ->orderByRaw("
                FIELD(
                    ukuran,
                    'S',
                    'M',
                    'L',
                    'XL'
                )
            ")
            ->get();

        return view(
            'admin.products.variants',
            compact(
                'product',
                'variants'
            )
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_kategori' => [
                'required',
                'exists:kategori_produk,id_kategori'
            ],

            'nama_produk' => [
                'required',
                'max:255'
            ],

            'harga_jual' => [
                'required',
                'numeric',
                'min:1'
            ],

            'deskripsi' => [
                'nullable'
            ],

            'gambar_produk' => [
                'nullable',
                'max:255'
            ],

            'ukuran' => [
                'required',
                'array',
                'min:1'
            ],

            'ukuran.*' => [
                'in:S,M,L,XL'
            ],

            'warna' => [
                'required'
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | NORMALISASI WARNA
        |--------------------------------------------------------------------------
        */

        $warnaInput = explode(
            ',',
            $validated['warna']
        );

        $warnaList = [];

        foreach ($warnaInput as $warna) {

            $warna = strtolower(
                trim(
                    preg_replace(
                        '/\s+/',
                        ' ',
                        $warna
                    )
                )
            );

            if ($warna !== '') {
                $warnaList[] = $warna;
            }
        }

        $warnaList = array_values(
            array_unique($warnaList)
        );


        if (count($warnaList) === 0) {

            return back()
                ->withErrors([
                    'warna' => 'Minimal satu warna harus diisi.'
                ])
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | GENERATE ID PRODUK
        |--------------------------------------------------------------------------
        */

        $lastProduct = DB::table('produk')
            ->orderByRaw(
                'CAST(SUBSTRING(id_produk, 4) AS UNSIGNED) DESC'
            )
            ->first();

        $nextProductNumber = $lastProduct
            ? ((int) substr($lastProduct->id_produk, 3)) + 1
            : 1;

        $idProduk = 'PRD' . str_pad(
            $nextProductNumber,
            3,
            '0',
            STR_PAD_LEFT
        );


        /*
        |--------------------------------------------------------------------------
        | GENERATE VARIANT START NUMBER
        |--------------------------------------------------------------------------
        */

        $lastVariant = DB::table('varian_produk')
            ->orderByRaw(
                'CAST(SUBSTRING(id_varian, 4) AS UNSIGNED) DESC'
            )
            ->first();

        $nextVariantNumber = $lastVariant
            ? ((int) substr($lastVariant->id_varian, 3)) + 1
            : 1;


        /*
        |--------------------------------------------------------------------------
        | SIMPAN PRODUK + VARIAN SECARA ATOMIK
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (
            $validated,
            $warnaList,
            $idProduk,
            &$nextVariantNumber
        ) {

            $gambar = null;

            if (!empty($validated['gambar_produk'])) {

                $gambar =
                    'images/products/' .
                    basename(
                        $validated['gambar_produk']
                    );
            }


            DB::table('produk')->insert([
                'id_produk' => $idProduk,

                'id_kategori' =>
                    $validated['id_kategori'],

                'nama_produk' =>
                    $validated['nama_produk'],

                'harga_jual' =>
                    $validated['harga_jual'],

                'deskripsi' =>
                    $validated['deskripsi'] ?? null,

                'gambar_produk' =>
                    $gambar,

                'status_produk' =>
                    'AKTIF',

                'created_at' => now(),
                'updated_at' => now(),
            ]);


            foreach ($warnaList as $warna) {

                foreach (
                    $validated['ukuran']
                    as $ukuran
                ) {

                    $idVarian =
                        'VAR' .
                        str_pad(
                            $nextVariantNumber,
                            3,
                            '0',
                            STR_PAD_LEFT
                        );

                    DB::table(
                        'varian_produk'
                    )->insert([
                        'id_varian' =>
                            $idVarian,

                        'id_produk' =>
                            $idProduk,

                        'ukuran' =>
                            $ukuran,

                        'warna' =>
                            $warna,

                        /*
                        Stok awal varian akan ditambahkan melalui pembelian produk.
                        */
                        'stok' => 0,

                        'stok_minimum' => 5,

                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    $nextVariantNumber++;
                }
            }
        });


        return redirect()
            ->route('admin.products.index')
            ->with(
                'success',
                'Produk dan varian berhasil ditambahkan.'
            );
    }
}