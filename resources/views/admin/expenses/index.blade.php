@extends('layouts.admin')
@section('title','Pengeluaran')
@section('content')
    <div class="page-head">
        <div>
            <h1>Pengeluaran</h1>
            <p class="muted">
                Catat dan pantau seluruh pengeluaran operasional bisnis.
            </p>
        </div>
        <a class="btn" href="{{ route('admin.expenses.create') }}">+ Catat Operasional</a>
    </div>

    <div class="table-wrap">
        <table>
            <tr>
                <th>ID</th>
                <th>Tanggal</th>
                <th>Jenis</th>
                <th>Nominal</th>
                <th>Sumber</th>
                <th>Admin</th>
                <th>Keterangan</th>
                <th>Aksi</th>
            </tr>
            @foreach($expenses as $e)
                <tr>
                    <td>{{ $e->id_pengeluaran }}</td>
                    <td>{{ $e->tanggal_pengeluaran }}</td>
                    <td>
                        @if($e->jenis_pengeluaran === 'PEMBELIAN_PRODUK')
                            <span class="badge">PEMBELIAN PRODUK</span>
                        @else
                            {{ $e->jenis_pengeluaran }}
                        @endif
                    </td>
                    <td>Rp{{ number_format($e->nominal,0,',','.') }}</td>
                    <td>
                        @if($e->id_pembelian)
                            <a href="{{ route('admin.purchases.show',$e->id_pembelian) }}">
                                {{ $e->id_pembelian }}
                            </a>
                        @else
                            Manual
                        @endif
                    </td>
                    <td>{{ $e->admin_nama }}</td>
                    <td>{{ $e->keterangan }}</td>
                    <td>
                        @if($e->id_pembelian)
                            <span class="muted">Otomatis</span>
                        @else
                            <a
                                class="btn sm light"
                                href="{{ route('admin.expenses.edit',$e->id_pengeluaran) }}"
                            >
                                Edit
                            </a>
                        @endif
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
@endsection
