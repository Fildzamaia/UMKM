<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'produk' => DB::table('produk')->count(),
            'varian' => DB::table('varian_produk')->count(),
            'bahan' => DB::table('bahan_baku')->count(),

            'low_stock_produk' => DB::table('varian_produk')
                ->whereColumn('stok', '<=', 'stok_minimum')
                ->count(),

            'supplier' => DB::table('supplier')->count(),
        ];

        return view(
            'admin.dashboard',
            compact('stats')
        );
    }
}