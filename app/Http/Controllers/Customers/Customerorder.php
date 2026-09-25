<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Models\MutasiStok;
use App\Models\Penjualan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 * SEMENTARA (e2e): daftar pesanan online, pembayaran SIMULASI (tanpa
 * payment gateway), gagal bayar, dan pembatalan oleh customer.
 * Ganti ketika integrasi pembayaran dan modul Customer resmi selesai.
 */
class Customerorder extends Controller
{
    use SementaraOnlineSale;

    public function index()
    {
        $orders = Penjualan::query()
            ->where('id_akun', Auth::id())
            ->where('kanal_penjualan', 'ONLINE')
            ->with('detailPenjualan')
            ->orderByDesc('tanggal_penjualan')
            ->orderByDesc('id_penjualan')
            ->paginate(10);

        return view(
            'customer.orders.index',
            compact('orders')
        );
    }

    public function show(string $idPenjualan)
    {
        $order = $this->ownOrderQuery($idPenjualan)
            ->with('detailPenjualan.varian.produk')
            ->firstOrFail();

        return view(
            'customer.orders.show',
            compact('order')
        );
    }

    public function payment(string $idPenjualan)
    {
        $order = $this->ownOrderQuery($idPenjualan)
            ->with('detailPenjualan.varian.produk')
            ->firstOrFail();

        if (!$this->isPending($order)) {
            return redirect()
                ->route('customer.orders.show', $idPenjualan)
                ->withErrors([
                    'status' => 'Pesanan ini tidak menunggu pembayaran.',
                ]);
        }

        return view(
            'customer.payment',
            compact('order')
        );
    }

    /**
     * Simulasi pembayaran berhasil: cek ulang stok dengan lock, kurangi stok,
     * catat mutasi KELUAR, beri poin, pesanan menjadi DIPROSES (siap Admin).
     */
    public function pay(string $idPenjualan)
    {
        DB::transaction(function () use ($idPenjualan) {
            $now = now();
            $customerId = Auth::id();

            $order = $this->ownOrderQuery($idPenjualan)
                ->lockForUpdate()
                ->firstOrFail();

            $this->ensurePending($order);

            $details = DB::table('detail_penjualan')
                ->where('id_penjualan', $idPenjualan)
                ->orderBy('id_varian')
                ->get();

            $variants = DB::table('varian_produk as v')
                ->whereIn('v.id_varian', $details->pluck('id_varian')->all())
                ->orderBy('v.id_varian')
                ->lockForUpdate()
                ->get()
                ->keyBy('id_varian');

            $problems = [];

            foreach ($details as $detail) {
                $stock = (int) $variants[$detail->id_varian]->stok;

                if ($stock < (int) $detail->jumlah) {
                    $problems[] = 'Stok varian '.$detail->id_varian
                        .' tersisa '.$stock.', dibutuhkan '.$detail->jumlah.'.';
                }
            }

            if ($problems !== []) {
                throw ValidationException::withMessages([
                    'stok' => $problems,
                ]);
            }

            $mutationNumber = $this->nextNumber(
                'mutasi_stok',
                'id_mutasi',
                'MUT'
            );

            foreach ($details as $detail) {
                $stockBefore = (int) $variants[$detail->id_varian]->stok;

                DB::table('varian_produk')
                    ->where('id_varian', $detail->id_varian)
                    ->decrement(
                        'stok',
                        $detail->jumlah,
                        ['updated_at' => $now]
                    );

                MutasiStok::create([
                    'id_mutasi' =>
                        $this->formatId('MUT', $mutationNumber++, 5),
                    'id_varian' => $detail->id_varian,
                    'id_akun' => $customerId,
                    'tanggal_mutasi' => $now,
                    'jenis_mutasi' => 'KELUAR',
                    'jumlah' => $detail->jumlah,
                    'stok_sebelum' => $stockBefore,
                    'stok_sesudah' => $stockBefore - (int) $detail->jumlah,
                    'sumber' => $idPenjualan,
                    'created_at' => $now,
                ]);
            }

            $order->update([
                'status_pembayaran' => 'BERHASIL',
                'status_penjualan' => 'DIPROSES',
                'referensi_pembayaran' => 'SIMULASI-QR-'.$idPenjualan,
                'poin_didapat' => (int) floor(
                    (float) $order->nominal_bayar / $this->rupiahPerPoin
                ),
            ]);
        });

        return redirect()
            ->route('customer.orders.show', $idPenjualan)
            ->with(
                'success',
                'Pembayaran berhasil. Pesanan sedang diproses.'
            );
    }

    public function fail(string $idPenjualan)
    {
        DB::transaction(function () use ($idPenjualan) {
            $order = $this->ownOrderQuery($idPenjualan)
                ->lockForUpdate()
                ->firstOrFail();

            $this->ensurePending($order);

            $order->update([
                'status_pembayaran' => 'GAGAL',
                'status_penjualan' => 'BATAL',
            ]);
        });

        return redirect()
            ->route('customer.orders.show', $idPenjualan)
            ->withErrors([
                'status' => 'Pembayaran gagal. Pesanan dibatalkan.',
            ]);
    }

    public function cancel(string $idPenjualan)
    {
        DB::transaction(function () use ($idPenjualan) {
            $order = $this->ownOrderQuery($idPenjualan)
                ->lockForUpdate()
                ->firstOrFail();

            $this->ensurePending($order);

            // Status pembayaran tetap MENUNGGU (belum pernah dibayar).
            $order->update([
                'status_penjualan' => 'BATAL',
            ]);
        });

        return redirect()
            ->route('customer.orders.show', $idPenjualan)
            ->with('success', 'Pesanan dibatalkan.');
    }

    private function ownOrderQuery(string $idPenjualan)
    {
        return Penjualan::query()
            ->where('id_penjualan', $idPenjualan)
            ->where('id_akun', Auth::id())
            ->where('kanal_penjualan', 'ONLINE');
    }

    private function isPending(Penjualan $order): bool
    {
        return $order->status_pembayaran === 'MENUNGGU'
            && $order->status_penjualan === 'BARU';
    }

    private function ensurePending(Penjualan $order): void
    {
        if (!$this->isPending($order)) {
            throw ValidationException::withMessages([
                'status' => 'Pesanan ini tidak lagi menunggu pembayaran.',
            ]);
        }
    }
}
