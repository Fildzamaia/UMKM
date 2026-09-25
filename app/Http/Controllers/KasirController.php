<?php

namespace App\Http\Controllers;

use App\Models\DetailPenjualan;
use App\Models\MutasiStok;
use App\Models\Penjualan;
use App\Models\PenjualanMember;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class KasirController extends Controller
{
    // Member mendapat 1 poin untuk setiap kelipatan nominal ini.
    private const RUPIAH_PER_POIN = 10000;

    public function pos()
    {
        // Kategori lewat Query Builder, bukan model KategoriProduk.
        $categories = DB::table('kategori_produk')
            ->orderBy('nama_kategori')
            ->get();

        $products = DB::table('produk as p')
            ->join(
                'kategori_produk as k',
                'p.id_kategori',
                '=',
                'k.id_kategori'
            )
            ->where('p.status_produk', 'AKTIF')
            ->select(
                'p.id_produk',
                'p.id_kategori',
                'p.nama_produk',
                'p.harga_jual',
                'k.nama_kategori'
            )
            ->orderBy('p.nama_produk')
            ->get();

        $productIds = $products->pluck('id_produk')->all();

        // Varian stok 0 tetap ikut, ditandai tidak bisa dipilih di view.
        $variants = DB::table('varian_produk')
            ->whereIn('id_produk', $productIds)
            ->orderBy('warna')
            ->orderBy('id_varian')
            ->get()
            ->groupBy('id_produk');

        $promos = $this->activePromos($productIds, now());

        foreach ($products as $product) {
            $promo = $promos->get($product->id_produk);

            $product->promo = $promo;
            $product->harga_final = $this->discountedPrice(
                $product->harga_jual,
                $promo->persen_diskon ?? 0
            );
        }

        $customers = DB::table('akun')
            ->where('tipe_akun', 'CUSTOMER')
            ->where('status_akun', 'AKTIF')
            ->orderBy('nama')
            ->get(['id_akun', 'nama', 'no_telp']);

        return view(
            'kasir.pos',
            compact('categories', 'products', 'variants', 'customers')
        );
    }

    public function store(Request $request)
    {
        $validated = $this->validateStorePenjualan($request);

        $saleId = DB::transaction(function () use ($validated) {
            $now = now();
            $kasirId = Auth::id();
            $isMember = $validated['tipe_pembeli'] === 'member';

            $quantities = collect($validated['items'])
                ->mapWithKeys(fn (array $item) => [
                    $item['id_varian'] => (int) $item['jumlah'],
                ]);

            // Kunci semua varian yang terlibat. Urutan id_varian dibuat tetap
            // supaya dua transaksi bersamaan mengunci dalam urutan yang sama.
            $variants = DB::table('varian_produk')
                ->whereIn('id_varian', $quantities->keys()->all())
                ->orderBy('id_varian')
                ->lockForUpdate()
                ->get()
                ->keyBy('id_varian');

            $products = DB::table('produk')
                ->whereIn(
                    'id_produk',
                    $variants->pluck('id_produk')->unique()->all()
                )
                ->get()
                ->keyBy('id_produk');

            // Semua item dicek dulu sebelum ada yang ditulis.
            $problems = [];

            foreach ($quantities as $variantId => $qty) {
                $variant = $variants[$variantId];
                $product = $products[$variant->id_produk];
                $label = $product->nama_produk
                    .' ('.$variant->ukuran.'/'.$variant->warna.')';

                if ($product->status_produk !== 'AKTIF') {
                    $problems[] = $label.' sudah tidak dijual.';

                    continue;
                }

                if ((int) $variant->stok < $qty) {
                    $problems[] = 'Stok '.$label.' tidak cukup: tersisa '
                        .$variant->stok.', diminta '.$qty.'.';
                }
            }

            if ($problems !== []) {
                throw ValidationException::withMessages([
                    'items' => $problems,
                ]);
            }

            $promos = $this->activePromos($products->keys()->all(), $now);

            $lines = [];
            $total = 0;

            foreach ($quantities as $variantId => $qty) {
                $variant = $variants[$variantId];
                $promo = $promos->get($variant->id_produk);

                $unitPrice = $this->discountedPrice(
                    $products[$variant->id_produk]->harga_jual,
                    $promo->persen_diskon ?? 0
                );

                $lines[] = [
                    'id_varian' => $variantId,
                    'jumlah' => $qty,
                    'harga_satuan' => $unitPrice,
                    'stok_sebelum' => (int) $variant->stok,
                ];

                $total += $unitPrice * $qty;
            }

            $total = round($total, 2);

            $points = $isMember
                ? (int) floor($total / self::RUPIAH_PER_POIN)
                : 0;

            $saleId = $this->formatId(
                'PJL',
                $this->nextNumber('penjualan', 'id_penjualan', 'PJL'),
                4
            );

            $detailNumber = $this->nextNumber(
                'detail_penjualan',
                'id_detail_penjualan',
                'DPJ'
            );

            $mutationNumber = $this->nextNumber(
                'mutasi_stok',
                'id_mutasi',
                'MUT'
            );

            Penjualan::create([
                'id_penjualan' => $saleId,
                'id_akun' => $kasirId,
                'tanggal_penjualan' => $now,
                'kanal_penjualan' => 'OFFLINE',
                'metode_pembayaran' => $validated['metode_pembayaran'],
                'status_pembayaran' => 'BERHASIL',
                'referensi_pembayaran' =>
                    $validated['referensi_pembayaran'] ?? null,
                'nominal_bayar' => $total,
                'status_penjualan' => 'SELESAI',
                'alamat_pengiriman' => null,
                'poin_didapat' => $points,
                'poin_digunakan' => 0,
                'diskon_poin' => 0,
            ]);

            foreach ($lines as $line) {
                DetailPenjualan::create([
                    'id_detail_penjualan' =>
                        $this->formatId('DPJ', $detailNumber++, 5),
                    'id_penjualan' => $saleId,
                    'id_varian' => $line['id_varian'],
                    'jumlah' => $line['jumlah'],
                    'harga_satuan' => $line['harga_satuan'],
                ]);
            }

            if ($isMember) {
                PenjualanMember::create([
                    'id_penjualan' => $saleId,
                    'id_akun_customer' => $validated['id_akun_customer'],
                ]);
            }

            // Pengurangan stok dan log mutasi di transaction yang sama.
            foreach ($lines as $line) {
                DB::table('varian_produk')
                    ->where('id_varian', $line['id_varian'])
                    ->decrement(
                        'stok',
                        $line['jumlah'],
                        ['updated_at' => $now]
                    );

                MutasiStok::create([
                    'id_mutasi' =>
                        $this->formatId('MUT', $mutationNumber++, 5),
                    'id_varian' => $line['id_varian'],
                    'id_akun' => $kasirId,
                    'tanggal_mutasi' => $now,
                    'jenis_mutasi' => 'KELUAR',
                    'jumlah' => $line['jumlah'],
                    'stok_sebelum' => $line['stok_sebelum'],
                    'stok_sesudah' =>
                        $line['stok_sebelum'] - $line['jumlah'],
                    'sumber' => $saleId,
                    'created_at' => $now,
                ]);
            }

            return $saleId;
        });

        return redirect()
            ->route('kasir.struk', $saleId)
            ->with('success', 'Transaksi '.$saleId.' berhasil disimpan.');
    }

    public function riwayat()
    {
        $sales = Penjualan::query()
            ->where('kanal_penjualan', 'OFFLINE')
            ->where('id_akun', Auth::id())
            ->with([
                'detailPenjualan',
                'penjualanMember.customer',
            ])
            ->orderByDesc('tanggal_penjualan')
            ->orderByDesc('id_penjualan')
            ->paginate(15);

        return view(
            'kasir.riwayat',
            compact('sales')
        );
    }

    public function show(string $idPenjualan)
    {
        // Kasir hanya boleh membuka struk transaksi offline miliknya sendiri.
        $sale = Penjualan::query()
            ->where('id_penjualan', $idPenjualan)
            ->where('kanal_penjualan', 'OFFLINE')
            ->where('id_akun', Auth::id())
            ->with([
                'akun',
                'detailPenjualan.varian.produk',
                'penjualanMember.customer',
            ])
            ->firstOrFail();

        return view(
            'kasir.struk',
            compact('sale')
        );
    }

    private function validateStorePenjualan(Request $request): array
    {
        return $request->validate([
            'items' => 'required|array|min:1',
            'items.*.id_varian' =>
                'required|distinct|exists:varian_produk,id_varian',
            'items.*.jumlah' => 'required|integer|min:1',
            'tipe_pembeli' => 'required|in:member,guest',
            'id_akun_customer' => [
                'exclude_unless:tipe_pembeli,member',
                'required_if:tipe_pembeli,member',
                Rule::exists('akun', 'id_akun')
                    ->where('tipe_akun', 'CUSTOMER'),
            ],
            'metode_pembayaran' => 'required|in:TUNAI,QR',
            'referensi_pembayaran' => 'nullable|max:255',
        ], [
            'items.required' => 'Keranjang masih kosong.',
            'items.min' => 'Keranjang masih kosong.',
            'items.*.id_varian.distinct' =>
                'Varian yang sama tidak boleh muncul dua kali di keranjang.',
            'items.*.id_varian.exists' => 'Varian tidak ditemukan.',
            'items.*.jumlah.min' => 'Jumlah setiap item minimal 1.',
            'id_akun_customer.required_if' =>
                'Pilih member untuk transaksi member.',
            'id_akun_customer.exists' =>
                'Member harus akun CUSTOMER yang terdaftar.',
        ]);
    }

    /**
     * Promo aktif per produk (tanggal_mulai <= $at <= tanggal_selesai).
     * Kalau satu produk punya beberapa promo aktif, dipakai diskon terbesar.
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
     * Nomor urut berikutnya dengan pola orderByDesc → +1 seperti controller
     * lain. lockForUpdate menahan pembuatan ID yang sama oleh transaksi
     * paralel sampai transaction ini selesai.
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
