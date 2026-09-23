<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VariantController extends Controller
{
    public function index(string $idProduk)
    {
        $product = DB::table('produk')
            ->where('id_produk', $idProduk)
            ->first();

        abort_if(!$product, 404);

        $variants = DB::table('varian_produk')
            ->where('id_produk', $idProduk)
            ->orderBy('warna')
            ->orderBy('ukuran')
            ->get();

        return view(
            'admin.variants.index',
            compact('product', 'variants')
        );
    }

    public function store(Request $request, string $idProduk)
    {
        $validated = $request->validate([
            'ukuran' => 'required|max:10',
            'warna' => 'required|max:50',
            'stok_minimum' => 'required|integer|min:0',
        ]);

        $size = strtoupper(trim($validated['ukuran']));
        $color = preg_replace(
            '/\s+/',
            ' ',
            strtolower(trim($validated['warna']))
        );

        $exists = DB::table('varian_produk')
            ->where('id_produk', $idProduk)
            ->where('ukuran', $size)
            ->where('warna', $color)
            ->exists();

        if ($exists) {
            return back()->withErrors([
                'warna' => 'Kombinasi ukuran dan warna sudah ada.',
            ]);
        }

        $last = DB::table('varian_produk')
            ->orderByDesc('id_varian')
            ->value('id_varian');

        $number = $last
            ? ((int) substr($last, 3)) + 1
            : 1;

        DB::table('varian_produk')->insert([
            'id_varian' =>
                'VAR'.str_pad($number, 4, '0', STR_PAD_LEFT),
            'id_produk' => $idProduk,
            'ukuran' => $size,
            'warna' => $color,
            'stok' => 0,
            'stok_minimum' => $validated['stok_minimum'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with(
            'success',
            'Varian dibuat dengan stok awal 0. Tambah stok melalui Pembelian Produk.'
        );
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'ukuran' => 'required|max:10',
            'warna' => 'required|max:50',
            'stok_minimum' => 'required|integer|min:0',
        ]);

        $variant = DB::table('varian_produk')
            ->where('id_varian', $id)
            ->first();

        abort_if(!$variant, 404);

        $size = strtoupper(trim($validated['ukuran']));
        $color = preg_replace(
            '/\s+/',
            ' ',
            strtolower(trim($validated['warna']))
        );

        $duplicate = DB::table('varian_produk')
            ->where('id_produk', $variant->id_produk)
            ->where('ukuran', $size)
            ->where('warna', $color)
            ->where('id_varian', '!=', $id)
            ->exists();

        if ($duplicate) {
            return back()->withErrors([
                'warna' => 'Kombinasi ukuran dan warna sudah digunakan varian lain.',
            ]);
        }

        DB::table('varian_produk')
            ->where('id_varian', $id)
            ->update([
                'ukuran' => $size,
                'warna' => $color,
                'stok_minimum' => $validated['stok_minimum'],
                'updated_at' => now(),
            ]);

        return back()->with('success', 'Varian diperbarui.');
    }

    public function destroy(string $id)
    {
        $variant = DB::table('varian_produk')
            ->where('id_varian', $id)
            ->first();

        abort_if(!$variant, 404);

        $usedInPurchases = DB::table('detail_pembelian')
            ->where('id_varian', $id)
            ->exists();

        $usedInSales = DB::table('detail_penjualan')
            ->where('id_varian', $id)
            ->exists();

        if ($usedInPurchases || $usedInSales) {
            return back()->withErrors([
                'varian' =>
                    'Varian tidak dapat dihapus karena sudah memiliki histori pembelian atau penjualan.',
            ]);
        }

        DB::table('varian_produk')
            ->where('id_varian', $id)
            ->delete();

        return back()->with('success', 'Varian dihapus.');
    }
}
