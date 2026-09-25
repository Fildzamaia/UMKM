{{-- SEMENTARA (e2e): detail pesanan online customer. --}}
@extends('layouts.customer-sementara')
@section('title', 'Pesanan '.$order->id_penjualan)
@section('content')
    <h1>Pesanan {{ $order->id_penjualan }}</h1>

    @php
        $isPending = $order->status_pembayaran === 'MENUNGGU'
            && $order->status_penjualan === 'BARU';
    @endphp

    <div class="card">
        <p>
            <span class="badge">Pembayaran: {{ $order->status_pembayaran }}</span>
            <span class="badge">Status: {{ $order->status_penjualan }}</span>
        </p>

        <p class="muted">
            {{ $order->tanggal_penjualan->format('d/m/Y H:i') }}
            · Dikirim ke: {{ $order->alamat_pengiriman }}
            @if ($order->referensi_pembayaran)
                · Ref. {{ $order->referensi_pembayaran }}
            @endif
        </p>

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
                @foreach ($order->detailPenjualan as $detail)
                    <tr>
                        <td>
                            {{ $detail->varian->produk->nama_produk }}
                            <div class="muted">
                                {{ $detail->varian->warna }} · Ukuran {{ $detail->varian->ukuran }}
                            </div>
                        </td>
                        <td class="num">{{ $detail->jumlah }}</td>
                        <td class="num">Rp{{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                        <td class="num">
                            Rp{{ number_format($detail->harga_satuan * $detail->jumlah, 0, ',', '.') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total">
            <span>Total</span>
            <span>Rp{{ number_format($order->nominal_bayar, 0, ',', '.') }}</span>
        </div>

        @if ($order->status_pembayaran === 'BERHASIL')
            <p class="muted">Poin didapat: {{ $order->poin_didapat }}</p>
        @endif

        <div class="actions">
            <a class="button light" href="{{ route('customer.orders.index') }}">Semua pesanan</a>

            @if ($isPending)
                <a class="button" href="{{ route('customer.payment', $order->id_penjualan) }}">Bayar sekarang</a>

                <form method="POST" action="{{ route('customer.orders.cancel', $order->id_penjualan) }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="button danger">Batalkan pesanan</button>
                </form>
            @endif
        </div>
    </div>
@endsection
