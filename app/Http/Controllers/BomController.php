<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BomController extends Controller
{
    public function edit(string $idVarian)
    {
        $variant = DB::table('varian_produk')
            ->join(
                'produk',
                'varian_produk.id_produk',
                '=',
                'produk.id_produk'
            )
            ->where(
                'varian_produk.id_varian',
                $idVarian
            )
            ->select(
                'varian_produk.*',
                'produk.nama_produk'
            )
            ->first();

        abort_if(!$variant, 404);

        $materials = DB::table('bahan_baku')
            ->orderBy('nama_bahan')
            ->orderBy('warna')
            ->get();

        $bom = DB::table('komposisi_produk')
            ->where('id_varian', $idVarian)
            ->pluck(
                'jumlah_per_unit',
                'id_bahan'
            );

        return view(
            'admin.bom.edit',
            compact(
                'variant',
                'materials',
                'bom'
            )
        );
    }


    public function update(Request $request, string $idVarian)
    {
        $variantExists = DB::table('varian_produk')
            ->where('id_varian', $idVarian)
            ->exists();

        abort_if(!$variantExists, 404);

        $request->validate([
            'bahan' => [
                'nullable',
                'array'
            ],

            'bahan.*' => [
                'nullable',
                'numeric',
                'min:0'
            ],
        ]);

        DB::transaction(function () use (
            $request,
            $idVarian
        ) {
            DB::table('komposisi_produk')
                ->where(
                    'id_varian',
                    $idVarian
                )
                ->delete();

            foreach (
                $request->input('bahan', [])
                as $idBahan => $jumlah
            ) {
                if (
                    $jumlah !== null &&
                    $jumlah !== '' &&
                    (float) $jumlah > 0
                ) {
                    DB::table(
                        'komposisi_produk'
                    )->insert([
                        'id_varian' =>
                            $idVarian,

                        'id_bahan' =>
                            $idBahan,

                        'jumlah_per_unit' =>
                            (float) $jumlah,
                    ]);
                }
            }
        });

        return redirect()
            ->route(
                'admin.bom.edit',
                $idVarian
            )
            ->with(
                'success',
                'BOM berhasil disimpan.'
            );
    }
}