@extends('layouts.admin')
@section('title', 'Detail Pembelian')
@section('content')
    <div class="page-head">
        <div>
            <h1>{{ $purchase->id_pembelian }}</h1>
            <p class="muted">{{ $purchase->nama_supplier }} · dicatat {{ $purchase->admin_nama }}</p>
        </div>
        @php
            $purchaseBadge = match ($purchase->status_pembelian) {
                'DITERIMA' => 'ok',
                'BATAL' => 'bad',
                default => 'warn',
            };
        @endphp
        <span class="badge {{ $purchaseBadge }}">
            {{ $purchase->status_pembelian }}
        </span>
    </div>
    <div class="table-wrap">
        <table>
            <tr>
                <th>Produk</th>
                <th>Varian</th>
                <th>Jumlah</th>
                <th>Harga Beli</th>
                <th>Subtotal</th>
                <th>Stok Saat Ini</th>
            </tr>
            @foreach ($details as $d)
                <tr>
                    <td>{{ $d->nama_produk }}</td>
                    <td>{{ $d->ukuran }}/{{ $d->warna }}</td>
                    <td>{{ $d->jumlah }}</td>
                    <td>Rp{{ number_format($d->harga_satuan, 0, ',', '.') }}</td>
                    <td>Rp{{ number_format($d->jumlah * $d->harga_satuan, 0, ',', '.') }}</td>
                    <td>{{ $d->stok }}</td>
                </tr>
            @endforeach
        </table>
    </div>
    @if ($purchase->status_pembelian === 'DIPESAN')
        <div style="display:flex;gap:10px;margin-top:18px">
            <form method="POST" action="{{ route('admin.purchases.receive', $purchase->id_pembelian) }}">
                @csrf
                @method('PATCH')
                <button class="btn green">Barang Diterima → Tambah Stok</button>
            </form>
            <form method="POST" action="{{ route('admin.purchases.cancel', $purchase->id_pembelian) }}">
                @csrf
                @method('PATCH')
                <button class="btn red">Batalkan Pembelian</button>
            </form>
        </div>
    @endif
@endsection
