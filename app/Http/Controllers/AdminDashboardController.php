<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalSales = (float) DB::table('penjualan')
            ->where('status_pembayaran', 'BERHASIL')
            ->sum('nominal_bayar');

        $transactions = DB::table('penjualan')->count();
        $products = DB::table('produk')->count();
        $customers = DB::table('akun')
            ->where('tipe_akun', 'CUSTOMER')
            ->count();

        $lowStock = DB::table('varian_produk as v')
            ->join('produk as p', 'v.id_produk', '=', 'p.id_produk')
            ->where('p.status_produk', 'AKTIF')
            ->whereColumn('v.stok', '<=', 'v.stok_minimum')
            ->select('v.*', 'p.nama_produk', 'p.gambar_produk')
            ->orderBy('v.stok')
            ->orderBy('p.nama_produk')
            ->limit(8)
            ->get();

        $recent = DB::table('penjualan as s')
            ->join('akun as a', 's.id_akun', '=', 'a.id_akun')
            ->select('s.*', 'a.nama')
            ->orderByDesc('s.tanggal_penjualan')
            ->limit(8)
            ->get();

        $rawDaily = DB::table('penjualan')
            ->where('status_pembayaran', 'BERHASIL')
            ->where(
                'tanggal_penjualan',
                '>=',
                now()->subDays(6)->startOfDay()
            )
            ->selectRaw(
                'DATE(tanggal_penjualan) as d, SUM(nominal_bayar) as total'
            )
            ->groupByRaw('DATE(tanggal_penjualan)')
            ->pluck('total', 'd');

        $daily = collect(range(6, 0))
            ->map(function (int $daysAgo) use ($rawDaily) {
                $date = now()->subDays($daysAgo);
                $key = $date->toDateString();

                return (object) [
                    'd' => $key,
                    'total' => (float) ($rawDaily[$key] ?? 0),
                ];
            });

        return view(
            'admin.dashboard',
            compact(
                'totalSales',
                'transactions',
                'products',
                'customers',
                'lowStock',
                'recent',
                'daily'
            )
        );
    }
}
