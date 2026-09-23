@extends('layouts.admin')
@section('title','Tambah Produk')
@section('content')
    <div class="page-head">
        <div>
            <h1>Tambah Produk</h1>
            <p class="muted">
                Produk baru dibuat tanpa stok. Setelah itu tambahkan varian
                dan lakukan Pembelian Produk untuk restock.
            </p>
        </div>
    </div>
    <form class="card" method="POST" enctype="multipart/form-data" action="{{ route('admin.products.store') }}">
        @csrf
        <div class="form-grid">
            <div>
                <label>Kategori</label>
                <select name="id_kategori" required>
                    @foreach($categories as $c)
                        <option
                            value="{{ $c->id_kategori }}"
                            @selected(old('id_kategori') == $c->id_kategori)
                        >
                            {{ $c->nama_kategori }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label>Nama Produk</label>
                <input name="nama_produk" value="{{ old('nama_produk') }}" required>
            </div>
            <div>
                <label>Harga Jual</label>
                <input type="number" name="harga_jual" value="{{ old('harga_jual') }}" min="1" required>
            </div>
            <div>
                <label>Status</label>
                <select name="status_produk">
                    <option @selected(old('status_produk')==='AKTIF')>AKTIF</option>
                    <option @selected(old('status_produk')==='NONAKTIF')>NONAKTIF</option>
                </select>
            </div>
            <div class="full">
                <label>Gambar Produk</label>
                <input type="file" name="gambar" accept="image/*">
                <small class="muted">Opsional. Jika kosong, tampilan memakai placeholder/default.</small>
            </div>
            <div class="full">
                <label>Deskripsi</label>
                <textarea name="deskripsi">{{ old('deskripsi') }}</textarea>
            </div>
        </div>
        <br>
        <button class="btn">Simpan & Atur Varian</button>
    </form>
@endsection
