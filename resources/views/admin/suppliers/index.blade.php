@extends('layouts.admin')
@section('title','Supplier')
@section('content')
    <div class="page-head">
        <div>
            <h1>Supplier</h1>
        </div>
        <a class="btn" href="{{ route('admin.suppliers.create') }}">+ Tambah Supplier</a>
    </div>
    <div class="table-wrap">
        <table>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Telepon</th>
                <th>Alamat</th>
                <th>Aksi</th>
            </tr>
            @foreach($suppliers as $s)
                <tr>
                    <td>{{ $s->id_supplier }}</td>
                    <td>{{ $s->nama_supplier }}</td>
                    <td>{{ $s->no_telp }}</td>
                    <td>{{ $s->alamat }}</td>
                    <td>
                        <a class="btn" href="{{ route('admin.suppliers.edit',$s->id_supplier) }}">Edit</a>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
@endsection
