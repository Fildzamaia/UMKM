@extends('layouts.admin')
@section('title','Detail Penjualan')
@section('content')
    <div class="page-head">
        <div>
            <h1>{{ $sale->id_penjualan }}</h1>
            <p class="muted">{{ $sale->kanal_penjualan }} · pelaksana {{ $sale->pelaksana }}
                @if($sale->member_nama)
                    · member {{ $sale->member_nama }}
                @endif
            </p>
        </div>
        <div>
            <span class="badge">{{ $sale->status_pembayaran }}</span>
            <span class="badge">{{ $sale->status_penjualan }}</span>
        </div>
    </div>
    <div class="table-wrap">
        <table>
            <tr>
                <th>Produk</th>
                <th>Varian</th>
                <th>Qty</th>
                <th>Harga Final</th>
                <th>Total</th>
            </tr>
            @foreach($details as $d)
                <tr>
                    <td>{{ $d->nama_produk }}</td>
                    <td>{{ $d->ukuran }}/{{ $d->warna }}</td>
                    <td>{{ $d->jumlah }}</td>
                    <td>Rp{{ number_format($d->harga_satuan,0,',','.') }}</td>
                    <td>Rp{{ number_format($d->harga_satuan*$d->jumlah,0,',','.') }}</td>
                </tr>
            @endforeach
        </table>
    </div>
    <div class="card" style="margin-top:18px">
        <p>Diskon Poin: Rp{{ number_format($sale->diskon_poin,0,',','.') }}</p>
        <h2>Nominal Bayar: Rp{{ number_format($sale->nominal_bayar,0,',','.') }}</h2>
        <p>Poin didapat {{ $sale->poin_didapat }}, digunakan {{ $sale->poin_digunakan }}</p>
        @if (
            $sale->kanal_penjualan === 'ONLINE'
            && $sale->status_pembayaran === 'BERHASIL'
            && in_array($sale->status_penjualan, ['DIPROSES', 'DIKIRIM'])
        )
            <form method="POST" action="{{ route('admin.sales.status',$sale->id_penjualan) }}">
                @csrf
                @method('PATCH')
                <label>Update Status</label>
                <select name="status_penjualan">
                    @if($sale->status_penjualan==='DIPROSES')
                        <option>DIKIRIM</option>
                    @elseif($sale->status_penjualan==='DIKIRIM')
                        <option>SELESAI</option>
                    @endif
                </select>
                <button class="btn" style="margin-top:8px">Update</button>
            </form>
        @endif
    </div>
@endsection
