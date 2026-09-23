<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        $income = (float) DB::table('penjualan')
            ->where('status_pembayaran', 'BERHASIL')
            ->sum('nominal_bayar');

        $purchase = (float) DB::table('pengeluaran')
            ->where('jenis_pengeluaran', 'PEMBELIAN_PRODUK')
            ->sum('nominal');

        $operational = (float) DB::table('pengeluaran')
            ->where('jenis_pengeluaran', '!=', 'PEMBELIAN_PRODUK')
            ->sum('nominal');

        $totalExpense = (float) DB::table('pengeluaran')
            ->sum('nominal');

        $cashFlow = $income - $totalExpense;

        $best = DB::table('detail_penjualan as d')
            ->join(
                'penjualan as s',
                'd.id_penjualan',
                '=',
                's.id_penjualan'
            )
            ->join(
                'varian_produk as v',
                'd.id_varian',
                '=',
                'v.id_varian'
            )
            ->join(
                'produk as p',
                'v.id_produk',
                '=',
                'p.id_produk'
            )
            ->where('s.status_pembayaran', 'BERHASIL')
            ->select(
                'p.id_produk',
                'p.nama_produk',
                DB::raw('SUM(d.jumlah) as qty')
            )
            ->groupBy('p.id_produk', 'p.nama_produk')
            ->orderByDesc('qty')
            ->limit(10)
            ->get();

        $low = DB::table('varian_produk as v')
            ->join('produk as p', 'v.id_produk', '=', 'p.id_produk')
            ->where('p.status_produk', 'AKTIF')
            ->whereColumn('v.stok', '<=', 'v.stok_minimum')
            ->select('v.*', 'p.nama_produk')
            ->orderBy('v.stok')
            ->get();

        return view(
            'admin.reports.index',
            compact(
                'income',
                'purchase',
                'operational',
                'totalExpense',
                'cashFlow',
                'best',
                'low'
            )
        );
    }
}
