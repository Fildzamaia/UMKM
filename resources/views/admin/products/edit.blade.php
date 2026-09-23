@extends('layouts.admin')
@section('title','Edit Produk')
@section('content')
    <div class="page-head">
        <div>
            <h1>Edit Produk</h1>
            <p class="muted">{{ $product->id_produk }}</p>
        </div>
        <a class="btn light" href="{{ route('admin.products.index') }}">Kembali</a>
    </div>
    <form
        class="card"
        method="POST"
        enctype="multipart/form-data"
        action="{{ route('admin.products.update', $product->id_produk) }}"
    >
        @csrf
        @method('PUT')
        <div class="form-grid">
            <div>
                <label>Kategori</label>
                <select name="id_kategori" required>
                    @foreach($categories as $c)
                        <option
                            value="{{ $c->id_kategori }}"
                            @selected(old('id_kategori', $product->id_kategori) == $c->id_kategori)
                        >
                            {{ $c->nama_kategori }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label>Nama Produk</label>
                <input name="nama_produk" value="{{ old('nama_produk',$product->nama_produk) }}" required>
            </div>
            <div>
                <label>Harga Jual</label>
                <input
                    type="number"
                    name="harga_jual"
                    min="1"
                    value="{{ old('harga_jual', $product->harga_jual) }}"
                    required
                >
            </div>
            <div>
                <label>Status</label>
                <select name="status_produk">
                    <option @selected(old('status_produk',$product->status_produk)==='AKTIF')>AKTIF</option>
                    <option @selected(old('status_produk',$product->status_produk)==='NONAKTIF')>NONAKTIF</option>
                </select>
            </div>
            <div class="full">
                <label>Ganti Gambar (opsional)</label>
                <input type="file" name="gambar" accept="image/*">
                @if($product->gambar_produk)
                    <small class="muted">Saat ini: {{ $product->gambar_produk }}</small>
                @endif
            </div>
            <div class="full">
                <label>Deskripsi</label>
                <textarea name="deskripsi">{{ old('deskripsi',$product->deskripsi) }}</textarea>
            </div>
        </div>
        <br>
        <button class="btn">Simpan Perubahan</button>
    </form>
@endsection
