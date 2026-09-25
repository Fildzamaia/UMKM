{{-- SEMENTARA (e2e): checkout sementara, pembayaran QR simulasi. --}}
@extends('layouts.customer-sementara')
@section('title', 'Checkout')
@section('content')
    <h1>Checkout</h1>

    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>Produk</th>
                    <th class="num">Jumlah</th>
                    <th class="num">Harga</th>
                    <th class="num">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($lines as $line)
                    <tr>
                        <td>
                            {{ $line->nama_produk }}
                            <div class="muted">{{ $line->warna }} · Ukuran {{ $line->ukuran }}</div>
                        </td>
                        <td class="num">{{ $line->jumlah }}</td>
                        <td class="num">Rp{{ number_format($line->harga_satuan, 0, ',', '.') }}</td>
                        <td class="num">Rp{{ number_format($line->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total">
            <span>Total bayar</span>
            <span>Rp{{ number_format($total, 0, ',', '.') }}</span>
        </div>

        <p class="muted">
            Estimasi poin setelah pembayaran berhasil: {{ (int) floor($total / 10000) }} poin.
        </p>

        <form method="POST" action="{{ route('customer.checkout.store') }}">
            @csrf

            <p><strong><label for="alamat">Alamat pengiriman</label></strong></p>
            <textarea
                id="alamat"
                name="alamat_pengiriman"
                maxlength="1000"
                required
            >{{ old('alamat_pengiriman', $akun->alamat) }}</textarea>

            <p class="muted">Metode pembayaran: QR (simulasi).</p>

            <div class="actions">
                <a class="button light" href="{{ route('customer.cart') }}">Kembali ke keranjang</a>
                <button type="submit" class="button">Buat pesanan</button>
            </div>
        </form>
    </div>
@endsection
