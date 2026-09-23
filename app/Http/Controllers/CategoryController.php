<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = DB::table('kategori_produk')
            ->orderBy('nama_kategori')
            ->get();

        return view(
            'admin.categories.index',
            compact('categories')
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kategori' =>
                'required|max:100|unique:kategori_produk,nama_kategori',
        ]);

        $lastId = DB::table('kategori_produk')
            ->orderByDesc('id_kategori')
            ->value('id_kategori');

        $number = $lastId
            ? ((int) substr($lastId, 3)) + 1
            : 1;

        DB::table('kategori_produk')->insert([
            'id_kategori' =>
                'KTG'.str_pad($number, 3, '0', STR_PAD_LEFT),
            'nama_kategori' => trim($validated['nama_kategori']),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Kategori ditambahkan.');
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'nama_kategori' =>
                'required|max:100|unique:kategori_produk,nama_kategori,'
                .$id.',id_kategori',
        ]);

        DB::table('kategori_produk')
            ->where('id_kategori', $id)
            ->update([
                'nama_kategori' => trim($validated['nama_kategori']),
                'updated_at' => now(),
            ]);

        return back()->with('success', 'Kategori diperbarui.');
    }

    public function destroy(string $id)
    {
        $category = DB::table('kategori_produk')
            ->where('id_kategori', $id)
            ->first();

        abort_if(!$category, 404);

        $isUsed = DB::table('produk')
            ->where('id_kategori', $id)
            ->exists();

        if ($isUsed) {
            return back()->withErrors([
                'kategori' =>
                    'Kategori tidak dapat dihapus karena masih digunakan oleh produk.',
            ]);
        }

        DB::table('kategori_produk')
            ->where('id_kategori', $id)
            ->delete();

        return back()->with('success', 'Kategori dihapus.');
    }
}
