@extends('layouts.admin')
@section('title','Tambah Supplier')
@section('content')
    <div class="page-head">
        <h1>Tambah Supplier</h1>
    </div>
    <form class="card" method="POST" action="{{ route('admin.suppliers.store') }}">
        @csrf
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
