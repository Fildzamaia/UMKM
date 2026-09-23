<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PromoController extends Controller
{
    public function index()
    {
        $promos = DB::table('promo')
            ->orderByDesc('tanggal_mulai')
            ->get();

        return view(
            'admin.promos.index',
            compact('promos')
        );
    }

    public function create()
    {
        $products = DB::table('produk')
            ->where('status_produk', 'AKTIF')
            ->orderBy('nama_produk')
            ->get();

        return view(
            'admin.promos.create',
            compact('products')
        );
    }

    public function store(Request $request)
    {
        $validated = $this->validatePromo($request);

        DB::transaction(function () use ($validated) {
            $lastId = DB::table('promo')
                ->orderByDesc('id_promo')
                ->value('id_promo');

            $number = $lastId
                ? ((int) substr($lastId, 3)) + 1
                : 1;

            $promoId = 'PRM'.str_pad(
                $number,
                3,
                '0',
                STR_PAD_LEFT
            );

            DB::table('promo')->insert([
                'id_promo' => $promoId,
                'nama_promo' => $validated['nama_promo'],
                'persen_diskon' => $validated['persen_diskon'],
                'tanggal_mulai' => $validated['tanggal_mulai'],
                'tanggal_selesai' => $validated['tanggal_selesai'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($validated['produk'] ?? [] as $productId) {
                DB::table('promo_produk')->insert([
                    'id_promo' => $promoId,
                    'id_produk' => $productId,
                ]);
            }
        });

        return redirect()
            ->route('admin.promos.index')
            ->with('success', 'Promo dibuat.');
    }

    public function edit(string $id)
    {
        $promo = DB::table('promo')
            ->where('id_promo', $id)
            ->first();

        abort_if(!$promo, 404);

        $products = DB::table('produk')
            ->orderBy('nama_produk')
            ->get();

        $selected = DB::table('promo_produk')
            ->where('id_promo', $id)
            ->pluck('id_produk')
            ->all();

        return view(
            'admin.promos.edit',
            compact('promo', 'products', 'selected')
        );
    }

    public function update(Request $request, string $id)
    {
        $validated = $this->validatePromo($request);

        DB::transaction(function () use ($validated, $id) {
            DB::table('promo')
                ->where('id_promo', $id)
                ->update([
                    'nama_promo' => $validated['nama_promo'],
                    'persen_diskon' => $validated['persen_diskon'],
                    'tanggal_mulai' => $validated['tanggal_mulai'],
                    'tanggal_selesai' => $validated['tanggal_selesai'],
                    'updated_at' => now(),
                ]);

            DB::table('promo_produk')
                ->where('id_promo', $id)
                ->delete();

            foreach ($validated['produk'] ?? [] as $productId) {
                DB::table('promo_produk')->insert([
                    'id_promo' => $id,
                    'id_produk' => $productId,
                ]);
            }
        });

        return redirect()
            ->route('admin.promos.index')
            ->with('success', 'Promo diperbarui.');
    }

    private function validatePromo(Request $request): array
    {
        return $request->validate([
            'nama_promo' => 'required|max:255',
            'persen_diskon' => 'required|numeric|min:0|max:100',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' =>
                'required|date|after_or_equal:tanggal_mulai',
            'produk' => 'nullable|array',
            'produk.*' => 'exists:produk,id_produk',
        ]);
    }
}
