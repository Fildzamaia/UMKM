<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Models\DetailPenjualan;
use App\Models\Penjualan;
use App\Models\PenjualanMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 * SEMENTARA (e2e): checkout online membuat pesanan MENUNGGU/BARU dengan
 * snapshot harga promo. Stok BELUM dikurangi di sini; stok dan poin baru
 * diproses saat pembayaran berhasil (Customerorder::pay), mengikuti skenario
 * di tests/Feature/FinalSystemSmokeTest. Redeem poin belum didukung.
 */
class Checkout extends Controller
{
    use SementaraOnlineSale;

    public function create()
    {
        $lines = $this->cartLines();

        if ($lines->isEmpty()) {
            return redirect()
                ->route('customer.cart')
                ->withErrors(['cart' => 'Keranjang masih kosong.']);
        }

        return view('customer.checkout', [
            'lines' => $lines,
            'total' => $lines->sum('subtotal'),
            'akun' => Auth::user(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'alamat_pengiriman' => 'required|string|max:1000',
        ]);

        if (session('cart', []) === []) {
            return redirect()
                ->route('customer.cart')
                ->withErrors(['cart' => 'Keranjang masih kosong.']);
        }

        $saleId = DB::transaction(function () use ($validated) {
            $now = now();
            $customerId = Auth::id();
            $lines = $this->cartLines();

            $problems = [];

            foreach ($lines as $line) {
                $label = $line->nama_produk
                    .' ('.$line->ukuran.'/'.$line->warna.')';

                if ($line->status_produk !== 'AKTIF') {
                    $problems[] = $label.' sudah tidak dijual.';
                } elseif ((int) $line->stok < $line->jumlah) {
                    $problems[] = 'Stok '.$label.' tersisa '.$line->stok.'.';
                }
            }

            if ($lines->isEmpty() || $problems !== []) {
                throw ValidationException::withMessages([
                    'cart' => $problems ?: ['Keranjang masih kosong.'],
                ]);
            }

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

            Penjualan::create([
                'id_penjualan' => $saleId,
                'id_akun' => $customerId,
                'tanggal_penjualan' => $now,
                'kanal_penjualan' => 'ONLINE',
                'metode_pembayaran' => 'QR',
                'status_pembayaran' => 'MENUNGGU',
                'referensi_pembayaran' => null,
                'nominal_bayar' => round($lines->sum('subtotal'), 2),
                'status_penjualan' => 'BARU',
                'alamat_pengiriman' => $validated['alamat_pengiriman'],
                'poin_didapat' => 0,
                'poin_digunakan' => 0,
                'diskon_poin' => 0,
            ]);

            foreach ($lines as $line) {
                DetailPenjualan::create([
                    'id_detail_penjualan' =>
                        $this->formatId('DPJ', $detailNumber++, 5),
                    'id_penjualan' => $saleId,
                    'id_varian' => $line->id_varian,
                    'jumlah' => $line->jumlah,
                    'harga_satuan' => $line->harga_satuan,
                ]);
            }

            // Pembeli online selalu akun CUSTOMER, jadi dicatat sebagai member.
            PenjualanMember::create([
                'id_penjualan' => $saleId,
                'id_akun_customer' => $customerId,
            ]);

            return $saleId;
        });

        session()->forget('cart');

        return redirect()
            ->route('customer.payment', $saleId)
            ->with(
                'success',
                'Pesanan '.$saleId.' dibuat. Selesaikan pembayaran.'
            );
    }
}
