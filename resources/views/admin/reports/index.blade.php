@extends('layouts.admin')
@section('title','Laporan')
@section('content')
    <div class="page-head">
        <div>
            <h1>Monitoring & Laporan</h1>
            <p class="muted">
                Lihat ringkasan pemasukan, pengeluaran, dan performa penjualan.
            </p>
        </div>
    </div>

    <div class="stats">
        <div class="card stat">
            Pemasukan
            <strong>Rp{{ number_format($income,0,',','.') }}</strong>
        </div>
        <div class="card stat">
            Pembelian Produk
            <strong>Rp{{ number_format($purchase,0,',','.') }}</strong>
        </div>
        <div class="card stat">
            Pengeluaran Operasional
            <strong>Rp{{ number_format($operational,0,',','.') }}</strong>
        </div>
        <div class="card stat">
            Total Pengeluaran
            <strong>Rp{{ number_format($totalExpense,0,',','.') }}</strong>
        </div>
    </div>

    <div class="card" style="margin-top:18px">
        <span class="muted">Selisih Arus Keuangan</span>
        <h2 style="margin-bottom:0">Rp{{ number_format($cashFlow,0,',','.') }}</h2>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-top:22px">
        <div class="card">
            <h3>Best Seller</h3>
            @forelse($best as $b)
                <p>
                    {{ $b->nama_produk }}
                    <b style="float:right">{{ $b->qty }} unit</b>
                </p>
            @empty
                <p class="muted">Belum ada data.</p>
            @endforelse
        </div>

        <div class="card">
            <h3>Low Stock</h3>
            @forelse($low as $v)
                <p>
                    {{ $v->nama_produk }} {{ $v->ukuran }}/{{ $v->warna }}
                    <b style="float:right">
                        {{ $v->stok }} ≤ {{ $v->stok_minimum }}
                    </b>
                </p>
            @empty
                <p class="muted">Tidak ada low stock.</p>
            @endforelse
        </div>
    </div>
@endsection
