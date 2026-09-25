{{-- SEMENTARA (e2e): halaman keranjang sementara (keranjang di session). --}}
@extends('layouts.customer-sementara')
@section('title', 'Keranjang')
@section('content')
    <h1>Keranjang</h1>

    @if ($lines->isEmpty())
        <div class="card">
            <p>Keranjang masih kosong.</p>
            <a class="button" href="{{ route('customer.catalog') }}">Lihat katalog</a>
        </div>
    @else
        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th class="num">Harga</th>
                        <th class="num">Jumlah</th>
                        <th class="num">Subtotal</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($lines as $line)
                        <tr>
                            <td>
                                <strong>{{ $line->nama_produk }}</strong>
                                <div class="muted">
                                    {{ $line->warna }} · Ukuran {{ $line->ukuran }} · Stok {{ $line->stok }}
                                </div>
                                @if ($line->promo)
                                    <div class="promo">
                                        {{ $line->promo->nama_promo }} −{{ (float) $line->promo->persen_diskon }}%
                                    </div>
                                @endif
                            </td>
                            <td class="num">Rp{{ number_format($line->harga_satuan, 0, ',', '.') }}</td>
                            <td class="num">
                                <form
                                    method="POST"
                                    action="{{ route('customer.cart.update', $line->id_varian) }}"
                                >
                                    @csrf
                                    @method('PATCH')
                                    <input
                                        type="number"
                                        name="jumlah"
                                        min="1"
                                        max="{{ $line->stok }}"
                                        value="{{ $line->jumlah }}"
                                        style="width: 76px"
                                        aria-label="Jumlah {{ $line->nama_produk }}"
                                    >
                                    <button type="submit" class="button light">Ubah</button>
                                </form>
                            </td>
                            <td class="num">Rp{{ number_format($line->subtotal, 0, ',', '.') }}</td>
                            <td class="num">
                                <form
                                    method="POST"
                                    action="{{ route('customer.cart.destroy', $line->id_varian) }}"
                                >
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="button link">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="total">
                <span>Total</span>
                <span>Rp{{ number_format($total, 0, ',', '.') }}</span>
            </div>

            <div class="actions">
                <a class="button light" href="{{ route('customer.catalog') }}">Lanjut belanja</a>
                <a class="button" href="{{ route('customer.checkout') }}">Checkout</a>
            </div>
        </div>
    @endif
@endsection
