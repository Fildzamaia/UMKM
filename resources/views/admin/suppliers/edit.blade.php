@extends('layouts.admin')
@section('title','Edit Supplier')
@section('content')
    <div class="page-head">
        <h1>Edit Supplier</h1>
    </div>
    <form class="card" method="POST" action="{{ route('admin.suppliers.update',$supplier->id_supplier) }}">
        @csrf
        @method('PUT')
        <div class="form-grid">
            <div>
                <label>Nama Supplier</label>
                <input name="nama_supplier" value="{{ old('nama_supplier',$supplier->nama_supplier ?? '') }}" required>
            </div>
            <div>
                <label>No. Telp</label>
                <input name="no_telp" value="{{ old('no_telp',$supplier->no_telp ?? '') }}" required>
            </div>
            <div class="full">
                <label>Alamat</label>
                <textarea name="alamat">{{ old('alamat',$supplier->alamat ?? '') }}</textarea>
            </div>
        </div>
        <br>
        <button class="btn">Simpan</button>
    </form>
@endsection
