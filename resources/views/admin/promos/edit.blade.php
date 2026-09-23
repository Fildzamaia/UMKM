@extends('layouts.admin')

@section('title', 'Edit Promo')

@section('content')
    <div class="page-head">
        <div>
            <h1>Edit Promo</h1>
            <p class="muted">Perbarui periode, diskon, dan produk yang mengikuti promo.</p>
        </div>
    </div>

    <form
        class="card"
        method="POST"
        action="{{ route('admin.promos.update', $promo->id_promo) }}"
    >
        @csrf
        @method('PUT')

        <div class="form-grid">
            <div>
                <label for="nama_promo">Nama Promo</label>
                <input
                    id="nama_promo"
                    name="nama_promo"
                    value="{{ old('nama_promo', $promo->nama_promo) }}"
                    required
                >
            </div>

            <div>
                <label for="persen_diskon">Persen Diskon</label>
                <input
                    id="persen_diskon"
                    type="number"
                    step="0.01"
                    min="0"
                    max="100"
                    name="persen_diskon"
                    value="{{ old('persen_diskon', $promo->persen_diskon) }}"
                    required
                >
            </div>

            <div>
                <label for="tanggal_mulai">Mulai</label>
                <input
                    id="tanggal_mulai"
                    type="datetime-local"
                    name="tanggal_mulai"
                    value="{{ old('tanggal_mulai', date('Y-m-d\\TH:i', strtotime($promo->tanggal_mulai))) }}"
                    required
                >
            </div>

            <div>
                <label for="tanggal_selesai">Selesai</label>
                <input
                    id="tanggal_selesai"
                    type="datetime-local"
                    name="tanggal_selesai"
                    value="{{ old('tanggal_selesai', date('Y-m-d\\TH:i', strtotime($promo->tanggal_selesai))) }}"
                    required
                >
            </div>

            <div class="full">
                <label>Produk yang Ikut Promo</label>

                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px">
                    @foreach ($products as $product)
                        <label style="font-weight: normal">
                            <input
                                type="checkbox"
                                name="produk[]"
                                value="{{ $product->id_produk }}"
                                style="width: auto"
                                @checked(in_array($product->id_produk, old('produk', $selected)))
                            >
                            {{ $product->nama_produk }}
                        </label>
                    @endforeach
                </div>
            </div>
        </div>

        <br>
        <button type="submit" class="btn">Simpan Perubahan</button>
    </form>
@endsection
