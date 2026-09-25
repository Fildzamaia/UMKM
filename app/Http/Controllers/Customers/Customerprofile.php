<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Models\Akun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // SEMENTARA (e2e)

class Customerprofile extends Controller
{
    public function show()
    {
        $akun = auth()->user();

        // SEMENTARA (e2e): saldo poin = poin didapat - poin digunakan dari
        // semua penjualan BERHASIL (online maupun kasir) atas nama member ini.
        $poin = (int) DB::table('penjualan as p')
            ->join(
                'penjualan_member as pm',
                'p.id_penjualan',
                '=',
                'pm.id_penjualan'
            )
            ->where('pm.id_akun_customer', $akun->id_akun)
            ->where('p.status_pembayaran', 'BERHASIL')
            ->selectRaw(
                'COALESCE(SUM(p.poin_didapat), 0) - COALESCE(SUM(p.poin_digunakan), 0) as saldo'
            )
            ->value('saldo');

        return view('customer.profile', compact('akun', 'poin'));
    }
}