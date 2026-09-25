<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * SEMENTARA (e2e): keranjang disimpan di session (belum ada tabel keranjang).
 * Ganti ketika developer Customer menyelesaikan modul keranjang resmi.
 */
class Cart extends Controller
{
    use SementaraOnlineSale;

    public function index()
    {
        $lines = $this->cartLines();

        return view('customer.cart', [
            'lines' => $lines,
            'total' => $lines->sum('subtotal'),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_varian' => 'required|exists:varian_produk,id_varian',
            'jumlah' => 'nullable|integer|min:1',
        ], [
            'id_varian.required' => 'Pilih ukuran dan warna terlebih dahulu.',
        ]);

        $variant = $this->sellableVariant($validated['id_varian']);

        if (!$variant) {
            return back()->withErrors([
                'id_varian' => 'Produk ini sudah tidak dijual.',
            ]);
        }

        $cart = session('cart', []);
        $quantity = ($cart[$variant->id_varian] ?? 0)
            + (int) ($validated['jumlah'] ?? 1);

        if ($quantity > (int) $variant->stok) {
            return back()->withErrors([
                'jumlah' => 'Stok tidak cukup. Tersisa '.$variant->stok.'.',
            ]);
        }

        $cart[$variant->id_varian] = $quantity;
        session(['cart' => $cart]);

        return redirect()
            ->route('customer.cart')
            ->with('success', 'Produk ditambahkan ke keranjang.');
    }

    public function update(Request $request, string $idVarian)
    {
        $validated = $request->validate([
            'jumlah' => 'required|integer|min:1',
        ]);

        $cart = session('cart', []);
        abort_unless(isset($cart[$idVarian]), 404);

        $variant = $this->sellableVariant($idVarian);

        if (!$variant || (int) $validated['jumlah'] > (int) $variant->stok) {
            return back()->withErrors([
                'jumlah' => 'Stok tidak cukup. Tersisa '
                    .($variant->stok ?? 0).'.',
            ]);
        }

        $cart[$idVarian] = (int) $validated['jumlah'];
        session(['cart' => $cart]);

        return back()->with('success', 'Keranjang diperbarui.');
    }

    public function destroy(string $idVarian)
    {
        $cart = session('cart', []);
        unset($cart[$idVarian]);
        session(['cart' => $cart]);

        return back()->with('success', 'Item dihapus dari keranjang.');
    }

    private function sellableVariant(string $idVarian): ?object
    {
        return DB::table('varian_produk as v')
            ->join('produk as p', 'v.id_produk', '=', 'p.id_produk')
            ->where('v.id_varian', $idVarian)
            ->where('p.status_produk', 'AKTIF')
            ->select('v.id_varian', 'v.stok')
            ->first();
    }
}
