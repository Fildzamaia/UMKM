@extends('layouts.admin')
@section('title','Promo')
@section('content')
    <div class="page-head">
        <h1>Promo</h1>
        <a class="btn" href="{{ route('admin.promos.create') }}">+ Tambah Promo</a>
    </div>
    <div class="table-wrap">
        <table>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Diskon</th>
                <th>Mulai</th>
                <th>Selesai</th>
                <th>Aksi</th>
            </tr>
            @foreach($promos as $p)
                <tr>
                    <td>{{ $p->id_promo }}</td>
                    <td>{{ $p->nama_promo }}</td>
                    <td>{{ $p->persen_diskon }}%</td>
                    <td>{{ $p->tanggal_mulai }}</td>
                    <td>{{ $p->tanggal_selesai }}</td>
                    <td>
                        <a class="btn" href="{{ route('admin.promos.edit',$p->id_promo) }}">Edit</a>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
@endsection
