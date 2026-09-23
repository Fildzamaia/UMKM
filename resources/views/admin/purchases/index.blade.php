@extends('layouts.admin')
@section('title', 'Pembelian Produk')
@section('content')
    <div class="page-head">
        <div>
            <h1>Pembelian Produk</h1>
        </div>
        <a class="btn" href="{{ route('admin.purchases.create') }}">+ Pembelian Baru</a>
    </div>
    <div class="table-wrap">
        <table>
            <tr>
                <th>ID</th>
                <th>Supplier</th>
                <th>Admin</th>
                <th>Tanggal</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
            @foreach ($purchases as $p)
                <tr>
                    <td>{{ $p->id_pembelian }}</td>
                    <td>{{ $p->nama_supplier }}</td>
                    <td>{{ $p->admin_nama }}</td>
                    <td>{{ $p->tanggal_pembelian }}</td>
                    <td>
                        @php
                            $purchaseBadge = match ($p->status_pembelian) {
                                'DITERIMA' => 'ok',
                                'BATAL' => 'bad',
                                default => 'warn',
                            };
                        @endphp
                        <span class="badge {{ $purchaseBadge }}">
                            {{ $p->status_pembelian }}
                        </span>
                    </td>
                    <td>
                        <a class="btn" href="{{ route('admin.purchases.show', $p->id_pembelian) }}">Detail</a>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
@endsection
