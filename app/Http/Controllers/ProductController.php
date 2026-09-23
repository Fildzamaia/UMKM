<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('produk as p')
            ->join(
                'kategori_produk as k',
                'p.id_kategori',
                '=',
                'k.id_kategori'
            )
            ->leftJoin(
                'varian_produk as v',
                'p.id_produk',
                '=',
                'v.id_produk'
            )
            ->select(
                'p.*',
                'k.nama_kategori',
                DB::raw('COUNT(v.id_varian) as jumlah_varian'),
                DB::raw('COALESCE(SUM(v.stok), 0) as total_stok')
            )
            ->groupBy(
                'p.id_produk',
                'p.id_kategori',
                'p.nama_produk',
                'p.harga_jual',
                'p.deskripsi',
                'p.gambar_produk',
                'p.status_produk',
                'p.created_at',
                'p.updated_at',
                'k.nama_kategori'
            );

        if ($request->filled('q')) {
            $query->where(
                'p.nama_produk',
                'like',
                '%'.$request->q.'%'
            );
        }

        $products = $query
            ->orderBy('p.nama_produk')
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

    public function store(Request $request)
    {
        $validated = $this->validateProduct($request);

        $lastId = DB::table('produk')
            ->orderByDesc('id_produk')
            ->value('id_produk');

        $number = $lastId
            ? ((int) substr($lastId, 3)) + 1
            : 1;

        $productId = 'PRD'.str_pad(
            $number,
            3,
            '0',
            STR_PAD_LEFT
        );

        $imagePath = $this->storeImage(
            $request,
            $productId
        );

        DB::table('produk')->insert([
            'id_produk' => $productId,
            'id_kategori' => $validated['id_kategori'],
            'nama_produk' => $validated['nama_produk'],
            'harga_jual' => $validated['harga_jual'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'gambar_produk' => $imagePath,
            'status_produk' => $validated['status_produk'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()
            ->route('admin.variants.index', $productId)
            ->with(
                'success',
                'Produk dibuat. Tambahkan varian ukuran dan warna.'
            );
    }

    public function edit(string $id)
    {
        $product = DB::table('produk')
            ->where('id_produk', $id)
            ->first();

        abort_if(!$product, 404);

        $categories = DB::table('kategori_produk')
            ->orderBy('nama_kategori')
            ->get();

        return view(
            'admin.products.edit',
            compact('product', 'categories')
        );
    }

    public function update(Request $request, string $id)
    {
        $validated = $this->validateProduct($request);

        $data = [
            'id_kategori' => $validated['id_kategori'],
            'nama_produk' => $validated['nama_produk'],
            'harga_jual' => $validated['harga_jual'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'status_produk' => $validated['status_produk'],
            'updated_at' => now(),
        ];

        $newImage = $this->storeImage($request, $id);

        if ($newImage !== null) {
            $data['gambar_produk'] = $newImage;
        }

        DB::table('produk')
            ->where('id_produk', $id)
            ->update($data);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Produk diperbarui.');
    }

    public function destroy(string $id)
    {
        $product = DB::table('produk')
            ->where('id_produk', $id)
            ->first();

        abort_if(!$product, 404);

        $hasVariants = DB::table('varian_produk')
            ->where('id_produk', $id)
            ->exists();

        if ($hasVariants) {
            return back()->withErrors([
                'produk' => 'Produk tidak dapat dihapus karena sudah memiliki varian. Nonaktifkan produk jika tidak ingin menjualnya lagi.',
            ]);
        }

        DB::transaction(function () use ($id) {
            DB::table('promo_produk')
                ->where('id_produk', $id)
                ->delete();

            DB::table('produk')
                ->where('id_produk', $id)
                ->delete();
        });

        if (!empty($product->gambar_produk)) {
            $imagePath = public_path(
                'images/products/'.$product->gambar_produk
            );

            if (is_file($imagePath)) {
                @unlink($imagePath);
            }
        }

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }

    private function validateProduct(Request $request): array
    {
        return $request->validate([
            'id_kategori' =>
                'required|exists:kategori_produk,id_kategori',
            'nama_produk' => 'required|max:255',
            'harga_jual' => 'required|numeric|min:1',
            'deskripsi' => 'nullable',
            'gambar' => 'nullable|image|max:3072',
            'status_produk' => 'required|in:AKTIF,NONAKTIF',
        ]);
    }

    private function storeImage(
        Request $request,
        string $productId
    ): ?string {
        if (!$request->hasFile('gambar')) {
            return null;
        }

        $file = $request->file('gambar');
        $name = $productId
            .'-'.time()
            .'.'.$file->extension();

        $file->move(
            public_path('images/products'),
            $name
        );

        return $name;
    }
}
