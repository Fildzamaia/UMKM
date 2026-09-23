@extends('layouts.admin')
@section('title','Penjualan')
@section('content')
    <div class="page-head">
        <div>
            <h1>Penjualan</h1>
            <p class="muted">Monitoring transaksi online Customer dan offline Kasir.</p>
        </div>
    </div>
    <form method="GET" class="card" style="display:flex;gap:10px;margin-bottom:15px">
        <select name="kanal">
            <option value="">Semua Kanal</option>
            <option @selected(request('kanal')==='ONLINE')>ONLINE</option>
            <option @selected(request('kanal')==='OFFLINE')>OFFLINE</option>
        </select>
        <select name="status">
            <option value="">Semua Pembayaran</option>
            <option @selected(request('status')==='MENUNGGU')>MENUNGGU</option>
            <option @selected(request('status')==='BERHASIL')>BERHASIL</option>
            <option @selected(request('status')==='GAGAL')>GAGAL</option>
        </select>
        <button class="btn">Filter</button>
    </form>
    <div class="table-wrap">
        <table>
            <tr>
                <th>ID</th>
                <th>Tanggal</th>
                <th>Pelaksana</th>
                <th>Member</th>
                <th>Kanal</th>
                <th>Pembayaran</th>
                <th>Status</th>
                <th>Total</th>
                <th>
                </th>
            </tr>
            @foreach($sales as $s)
                <tr>
                    <td>{{ $s->id_penjualan }}</td>
                    <td>{{ $s->tanggal_penjualan }}</td>
                    <td>{{ $s->pelaksana }}</td>
                    <td>{{ $s->member_nama ?: '-' }}</td>
                    <td>{{ $s->kanal_penjualan }}</td>
                    <td>{{ $s->status_pembayaran }}</td>
                    <td>{{ $s->status_penjualan }}</td>
                    <td>Rp{{ number_format($s->nominal_bayar,0,',','.') }}</td>
                    <td>
                        <a class="btn sm" href="{{ route('admin.sales.show',$s->id_penjualan) }}">Detail</a>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
@endsection
