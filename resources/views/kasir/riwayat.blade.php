@extends('layouts.kasir')
@section('title', 'Riwayat Transaksi')
@section('content')
    <div class="page-head">
        <div>
            <h1>Riwayat Transaksi</h1>
            <p class="muted">
                Transaksi offline yang diproses oleh {{ auth()->user()->nama }}
                ({{ auth()->user()->id_akun }}).
            </p>
        </div>

        <a class="btn" href="{{ route('kasir.pos') }}">Transaksi Baru</a>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tanggal</th>
                    <th>Pembeli</th>
                    <th class="num">Item</th>
                    <th>Pembayaran</th>
                    <th class="num">Total</th>
                    <th class="num">Poin</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($sales as $sale)
                    <tr>
                        <td>{{ $sale->id_penjualan }}</td>
                        <td>{{ $sale->tanggal_penjualan->format('d/m/Y H:i') }}</td>
                        <td>
                            @if ($sale->penjualanMember)
                                {{ $sale->penjualanMember->customer?->nama ?? $sale->penjualanMember->id_akun_customer }}
                                <span class="badge">Member</span>
                            @else
                                Guest
                            @endif
                        </td>
                        <td class="num">{{ $sale->detailPenjualan->sum('jumlah') }}</td>
                        <td>{{ $sale->metode_pembayaran }}</td>
                        <td class="num">Rp{{ number_format($sale->nominal_bayar, 0, ',', '.') }}</td>
                        <td class="num">{{ $sale->poin_didapat }}</td>
                        <td>
                            <a
                                class="btn sm light"
                                href="{{ route('kasir.struk', $sale->id_penjualan) }}"
                            >
                                Struk
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="muted">Belum ada transaksi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $sales->links('pagination::default') }}
@endsection
