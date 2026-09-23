<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PurchaseController extends Controller
{
    public function index()
    {
        $purchases = DB::table('pembelian_produk as p')
            ->join(
                'supplier as s',
                'p.id_supplier',
                '=',
                's.id_supplier'
            )
            ->join('akun as a', 'p.id_akun', '=', 'a.id_akun')
            ->select(
                'p.*',
                's.nama_supplier',
                'a.nama as admin_nama'
            )
            ->orderByDesc('p.tanggal_pembelian')
            ->get();

        return view(
            'admin.purchases.index',
            compact('purchases')
        );
    }

    public function create()
    {
        $suppliers = DB::table('supplier')
            ->orderBy('nama_supplier')
            ->get();

        $variants = DB::table('varian_produk as v')
            ->join('produk as p', 'v.id_produk', '=', 'p.id_produk')
            ->select('v.*', 'p.nama_produk')
            ->orderBy('p.nama_produk')
            ->orderBy('v.warna')
            ->orderBy('v.ukuran')
            ->get();

        return view(
            'admin.purchases.create',
            compact('suppliers', 'variants')
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_supplier' => 'required|exists:supplier,id_supplier',
            'id_varian' => 'required|array|min:1',
            'id_varian.*' =>
                'required|distinct|exists:varian_produk,id_varian',
            'jumlah' => 'required|array',
            'jumlah.*' => 'required|integer|min:1',
            'harga_satuan' => 'required|array',
            'harga_satuan.*' => 'required|numeric|min:0',
        ]);

        $itemCount = count($validated['id_varian']);

        if (
            count($validated['jumlah']) !== $itemCount
            || count($validated['harga_satuan']) !== $itemCount
        ) {
            throw ValidationException::withMessages([
                'items' => 'Data item pembelian tidak lengkap.',
            ]);
        }

        $purchaseId = DB::transaction(function () use ($validated) {
            $lastId = DB::table('pembelian_produk')
                ->orderByDesc('id_pembelian')
                ->value('id_pembelian');

            $number = $lastId
                ? ((int) substr($lastId, 2)) + 1
                : 1;

            $purchaseId = 'PB'.str_pad(
                $number,
                4,
                '0',
                STR_PAD_LEFT
            );

            DB::table('pembelian_produk')->insert([
                'id_pembelian' => $purchaseId,
                'id_supplier' => $validated['id_supplier'],
                'id_akun' => Auth::id(),
                'tanggal_pembelian' => now(),
                'status_pembelian' => 'DIPESAN',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $lastDetailId = DB::table('detail_pembelian')
                ->orderByDesc('id_detail_pembelian')
                ->value('id_detail_pembelian');

            $detailNumber = $lastDetailId
                ? ((int) substr($lastDetailId, 3)) + 1
                : 1;

            foreach ($validated['id_varian'] as $index => $variantId) {
                DB::table('detail_pembelian')->insert([
                    'id_detail_pembelian' =>
                        'DPB'.str_pad(
                            $detailNumber++,
                            5,
                            '0',
                            STR_PAD_LEFT
                        ),
                    'id_pembelian' => $purchaseId,
                    'id_varian' => $variantId,
                    'jumlah' => $validated['jumlah'][$index],
                    'harga_satuan' =>
                        $validated['harga_satuan'][$index],
                ]);
            }

            return $purchaseId;
        });

        return redirect()
            ->route('admin.purchases.show', $purchaseId)
            ->with(
                'success',
                'Pembelian dibuat dengan status DIPESAN. Stok belum berubah.'
            );
    }

    public function show(string $id)
    {
        $purchase = DB::table('pembelian_produk as p')
            ->join(
                'supplier as s',
                'p.id_supplier',
                '=',
                's.id_supplier'
            )
            ->join('akun as a', 'p.id_akun', '=', 'a.id_akun')
            ->where('p.id_pembelian', $id)
            ->select(
                'p.*',
                's.nama_supplier',
                'a.nama as admin_nama'
            )
            ->first();

        abort_if(!$purchase, 404);

        $details = DB::table('detail_pembelian as d')
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
            ->where('d.id_pembelian', $id)
            ->select(
                'd.*',
                'v.ukuran',
                'v.warna',
                'v.stok',
                'pr.nama_produk'
            )
            ->get();

        return view(
            'admin.purchases.show',
            compact('purchase', 'details')
        );
    }

    public function receive(string $id)
    {
        DB::transaction(function () use ($id) {
            $purchase = DB::table('pembelian_produk')
                ->where('id_pembelian', $id)
                ->lockForUpdate()
                ->first();

            abort_if(!$purchase, 404);

            if ($purchase->status_pembelian !== 'DIPESAN') {
                throw ValidationException::withMessages([
                    'status' =>
                        'Hanya pembelian DIPESAN yang dapat diterima.',
                ]);
            }

            $details = DB::table('detail_pembelian')
                ->where('id_pembelian', $id)
                ->get();

            $purchaseTotal = 0;

            foreach ($details as $detail) {
                DB::table('varian_produk')
                    ->where('id_varian', $detail->id_varian)
                    ->increment(
                        'stok',
                        $detail->jumlah,
                        ['updated_at' => now()]
                    );

                $purchaseTotal +=
                    (float) $detail->jumlah * (float) $detail->harga_satuan;
            }

            DB::table('pembelian_produk')
                ->where('id_pembelian', $id)
                ->update([
                    'status_pembelian' => 'DITERIMA',
                    'updated_at' => now(),
                ]);

            $lastExpenseId = DB::table('pengeluaran')
                ->orderByDesc('id_pengeluaran')
                ->value('id_pengeluaran');

            $expenseNumber = $lastExpenseId
                ? ((int) substr($lastExpenseId, 3)) + 1
                : 1;

            DB::table('pengeluaran')->insert([
                'id_pengeluaran' =>
                    'PNG'.str_pad($expenseNumber, 4, '0', STR_PAD_LEFT),
                'id_akun' => Auth::id(),
                'id_pembelian' => $id,
                'tanggal_pengeluaran' => now(),
                'jenis_pengeluaran' => 'PEMBELIAN_PRODUK',
                'nominal' => $purchaseTotal,
                'keterangan' => 'Pembelian produk '.$id.' diterima',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });

        return back()->with(
            'success',
            'Barang diterima. Stok bertambah dan pengeluaran pembelian tercatat otomatis.'
        );
    }

    public function cancel(string $id)
    {
        $updated = DB::table('pembelian_produk')
            ->where('id_pembelian', $id)
            ->where('status_pembelian', 'DIPESAN')
            ->update([
                'status_pembelian' => 'BATAL',
                'updated_at' => now(),
            ]);

        if (!$updated) {
            throw ValidationException::withMessages([
                'status' =>
                    'Hanya pembelian DIPESAN yang dapat dibatalkan.',
            ]);
        }

        return back()->with('success', 'Pembelian dibatalkan.');
    }
}
