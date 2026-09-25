<?php

namespace App\Http\Controllers\Customers;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * SEMENTARA (e2e): helper bersama untuk alur belanja online sementara
 * (keranjang di session, checkout, pembayaran simulasi). Dibuat supaya
 * proses bisnis bisa diuji end-to-end sebelum modul Customer resmi selesai.
 * Ganti/hapus ketika developer Customer menyelesaikan modulnya.
 */
trait SementaraOnlineSale
{
    // Sama dengan aturan Kasir: 1 poin per kelipatan Rp10.000.
    private int $rupiahPerPoin = 10000;

    /**
     * Isi keranjang session ['id_varian' => jumlah] beserta harga terkini.
     */
    private function cartLines(): Collection
    {
        $cart = session('cart', []);

        if ($cart === []) {
            return collect();
        }

        $variants = DB::table('varian_produk as v')
            ->join('produk as p', 'v.id_produk', '=', 'p.id_produk')
            ->whereIn('v.id_varian', array_keys($cart))
            ->select(
                'v.id_varian',
                'v.id_produk',
                'v.ukuran',
                'v.warna',
                'v.stok',
                'p.nama_produk',
                'p.harga_jual',
                'p.status_produk'
            )
            ->orderBy('p.nama_produk')
            ->orderBy('v.id_varian')
            ->get();

        $promos = $this->activePromos(
            $variants->pluck('id_produk')->unique()->all(),
            now()
        );

        return $variants->map(function ($variant) use ($cart, $promos) {
            $promo = $promos->get($variant->id_produk);

            $variant->jumlah = (int) $cart[$variant->id_varian];
            $variant->promo = $promo;
            $variant->harga_satuan = $this->discountedPrice(
                $variant->harga_jual,
                $promo->persen_diskon ?? 0
            );
            $variant->subtotal = $variant->harga_satuan * $variant->jumlah;

            return $variant;
        });
    }

    /**
     * Promo aktif per produk; kalau lebih dari satu, dipakai diskon terbesar.
     */
    private function activePromos(array $productIds, Carbon $at): Collection
    {
        if ($productIds === []) {
            return collect();
        }

        return DB::table('promo_produk as pp')
            ->join('promo as pr', 'pp.id_promo', '=', 'pr.id_promo')
            ->whereIn('pp.id_produk', $productIds)
            ->where('pr.tanggal_mulai', '<=', $at)
            ->where('pr.tanggal_selesai', '>=', $at)
            ->orderByDesc('pr.persen_diskon')
            ->orderBy('pr.id_promo')
            ->get([
                'pp.id_produk',
                'pr.id_promo',
                'pr.nama_promo',
                'pr.persen_diskon',
            ])
            ->unique('id_produk')
            ->keyBy('id_produk');
    }

    private function discountedPrice($hargaJual, $persenDiskon): float
    {
        return round(
            (float) $hargaJual * (100 - (float) $persenDiskon) / 100,
            2
        );
    }

    /**
     * Pola ID yang sama dengan KasirController (PJL/DPJ/MUT), supaya
     * penjualan online dan offline berbagi satu urutan nomor.
     */
    private function nextNumber(
        string $table,
        string $column,
        string $prefix
    ): int {
        $lastId = DB::table($table)
            ->where($column, 'like', $prefix.'%')
            ->orderByDesc($column)
            ->lockForUpdate()
            ->value($column);

        return $lastId
            ? ((int) substr($lastId, strlen($prefix))) + 1
            : 1;
    }

    private function formatId(
        string $prefix,
        int $number,
        int $digits
    ): string {
        return $prefix.str_pad($number, $digits, '0', STR_PAD_LEFT);
    }
}
