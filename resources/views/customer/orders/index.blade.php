{{-- SEMENTARA (e2e): daftar pesanan online customer. --}}
@extends('layouts.customer-sementara')
@section('title', 'Pesanan Saya')
@section('content')
    <h1>Pesanan Saya</h1>

    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tanggal</th>
                    <th class="num">Item</th>
                    <th class="num">Total</th>
                    <th>Pembayaran</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($orders as $order)
                    <tr>
                        <td>{{ $order->id_penjualan }}</td>
                        <td>{{ $order->tanggal_penjualan->format('d/m/Y H:i') }}</td>
                        <td class="num">{{ $order->detailPenjualan->sum('jumlah') }}</td>
                        <td class="num">Rp{{ number_format($order->nominal_bayar, 0, ',', '.') }}</td>
                        <td><span class="badge">{{ $order->status_pembayaran }}</span></td>
                        <td><span class="badge">{{ $order->status_penjualan }}</span></td>
                        <td class="num">
                            <a href="{{ route('customer.orders.show', $order->id_penjualan) }}">Detail</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="muted">Belum ada pesanan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{ $orders->links('pagination::simple-default') }}
    </div>
@endsection
