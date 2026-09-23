<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('penjualan as p')
            ->join('akun as a', 'p.id_akun', '=', 'a.id_akun')
            ->leftJoin(
                'penjualan_member as pm',
                'p.id_penjualan',
                '=',
                'pm.id_penjualan'
            )
            ->leftJoin(
                'akun as m',
                'pm.id_akun_customer',
                '=',
                'm.id_akun'
            )
            ->select(
                'p.*',
                'a.nama as pelaksana',
                'm.nama as member_nama'
            );

        if ($request->filled('kanal')) {
            $query->where('p.kanal_penjualan', $request->kanal);
        }

        if ($request->filled('status')) {
            $query->where('p.status_pembayaran', $request->status);
        }

        $sales = $query
            ->orderByDesc('p.tanggal_penjualan')
            ->get();

        return view(
            'admin.sales.index',
            compact('sales')
        );
    }

    public function show(string $id)
    {
        $sale = DB::table('penjualan as p')
            ->join('akun as a', 'p.id_akun', '=', 'a.id_akun')
            ->leftJoin(
                'penjualan_member as pm',
                'p.id_penjualan',
                '=',
                'pm.id_penjualan'
            )
            ->leftJoin(
                'akun as m',
                'pm.id_akun_customer',
                '=',
                'm.id_akun'
            )
            ->where('p.id_penjualan', $id)
            ->select(
                'p.*',
                'a.nama as pelaksana',
                'm.nama as member_nama'
            )
            ->first();

        abort_if(!$sale, 404);

        $details = DB::table('detail_penjualan as d')
            ->join(
                'varian_produk as v',
                'd.id_varian',
                '=',
                'v.id_varian'
            )
            ->join(
                'produk as pr',
                'v.id_produk',
                '=',
                'pr.id_produk'
            )
            ->where('d.id_penjualan', $id)
            ->select(
                'd.*',
                'v.ukuran',
                'v.warna',
                'pr.nama_produk'
            )
            ->get();

        return view(
            'admin.sales.show',
            compact('sale', 'details')
        );
    }

    public function updateStatus(Request $request, string $id)
    {
        $validated = $request->validate([
            'status_penjualan' =>
                'required|in:DIPROSES,DIKIRIM,SELESAI',
        ]);

        $sale = DB::table('penjualan')
            ->where('id_penjualan', $id)
            ->first();

        abort_if(!$sale, 404);

        if (
            $sale->kanal_penjualan !== 'ONLINE'
            || $sale->status_pembayaran !== 'BERHASIL'
        ) {
            return back()->withErrors([
                'status' =>
                    'Status hanya dapat diperbarui untuk pesanan online '
                    .'yang sudah dibayar.',
            ]);
        }

        $allowedTransitions = [
            'DIPROSES' => ['DIKIRIM'],
            'DIKIRIM' => ['SELESAI'],
            'SELESAI' => [],
        ];

        if (
            !in_array(
                $validated['status_penjualan'],
                $allowedTransitions[$sale->status_penjualan] ?? [],
                true
            )
        ) {
            return back()->withErrors([
                'status' => 'Transisi status tidak valid.',
            ]);
        }

        DB::table('penjualan')
            ->where('id_penjualan', $id)
            ->update([
                'status_penjualan' => $validated['status_penjualan'],
                'updated_at' => now(),
            ]);

        return back()->with(
            'success',
            'Status pesanan diperbarui.'
        );
    }
}
