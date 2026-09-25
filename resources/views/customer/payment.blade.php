{{-- SEMENTARA (e2e): pembayaran SIMULASI, belum terhubung payment gateway. --}}
@extends('layouts.customer-sementara')
@section('title', 'Pembayaran '.$order->id_penjualan)
@section('content')
    <h1>Pembayaran {{ $order->id_penjualan }}</h1>

    <div class="card">
        <p>
            Total yang harus dibayar:
            <strong>Rp{{ number_format($order->nominal_bayar, 0, ',', '.') }}</strong>
        </p>

        <div
            style="display:flex;align-items:center;justify-content:center;width:200px;height:200px;margin:18px 0;border:2px dashed #bbb;border-radius:12px;color:#777;text-align:center"
        >
            QR simulasi<br>{{ $order->id_penjualan }}
        </div>

        <p class="muted">
            Belum ada payment gateway. Pilih hasil pembayaran untuk mensimulasikan
            respons dari penyedia pembayaran.
        </p>

        <div class="actions">
            <form method="POST" action="{{ route('customer.payment.pay', $order->id_penjualan) }}">
                @csrf
                <button type="submit" class="button">Simulasikan pembayaran berhasil</button>
            </form>

            <form method="POST" action="{{ route('customer.payment.fail', $order->id_penjualan) }}">
                @csrf
                <button type="submit" class="button danger">Simulasikan pembayaran gagal</button>
            </form>

            <a class="button light" href="{{ route('customer.orders.show', $order->id_penjualan) }}">
                Bayar nanti
            </a>
        </div>
    </div>
@endsection
