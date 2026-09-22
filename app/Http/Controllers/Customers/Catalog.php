<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Models\KategoriProduk;
use App\Models\Produk;
use Illuminate\Http\Request;

class Catalog extends Controller
{
    public function index(Request $request)
    {
        $query = Produk::query()
            ->with([
                'kategori',
                'varian',
                'promo' => function ($query) {
                    $query
                        ->where('tanggal_mulai', '<=', now())
                        ->where('tanggal_selesai', '>=', now());
                },
            ])
            ->where('status_produk', 'AKTIF');

        if ($request->filled('search')) {
            $search = $request->input('search');

            $query->where(function ($query) use ($search) {
                $query
                    ->where('nama_produk', 'like', '%' . $search . '%')
                    ->orWhere('deskripsi', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('kategori')) {
            $query->where(
                'id_kategori',
                $request->input('kategori')
            );
        }

        $produk = $query
            ->orderBy('nama_produk')
            ->paginate(12)
            ->withQueryString();

        $kategori = KategoriProduk::query()
            ->orderBy('nama_kategori')
            ->get();

        return view('customer.catalog', compact(
            'produk',
            'kategori'
        ));
    }

    public function show(string $idProduk)
    {
        $produk = Produk::query()
            ->with([
                'kategori',
                'varian' => function ($query) {
                    $query
                        ->orderBy('warna')
                        ->orderBy('ukuran');
                },
                'promo' => function ($query) {
                    $query
                        ->where('tanggal_mulai', '<=', now())
                        ->where('tanggal_selesai', '>=', now());
                },
            ])
            ->where('status_produk', 'AKTIF')
            ->where('id_produk', $idProduk)
            ->firstOrFail();

        return view(
            'customer.product-detail',
            compact('produk')
        );
    }
}

