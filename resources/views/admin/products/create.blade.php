<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>
        Tambah Produk
    </title>

    <style>

        body {
            font-family: Arial;
            background: #f5f6f8;

            margin: 0;
            padding: 40px;
        }

        .card {
            max-width: 650px;

            margin: auto;

            background: white;

            padding: 30px;

            border-radius: 10px;
        }

        label {
            display: block;

            margin-top: 16px;
            margin-bottom: 6px;

            font-weight: bold;
        }

        input,
        select,
        textarea {
            width: 100%;

            padding: 11px;

            box-sizing: border-box;
        }

        .sizes {
            display: flex;
            gap: 20px;
        }

        .sizes label {
            font-weight: normal;
        }

        .sizes input {
            width: auto;
        }

        button {
            margin-top: 25px;

            padding: 12px 20px;

            background: #222;
            color: white;

            border: 0;
            border-radius: 6px;

            cursor: pointer;
        }

        .error {
            background: #ffdede;

            padding: 12px;

            margin-bottom: 20px;

            border-radius: 6px;
        }

    </style>

</head>

<body>


<div class="card">

    <a href="{{ route('admin.products.index') }}">
        ← Kembali
    </a>


    <h1>
        Tambah Produk Baru
    </h1>


    @if($errors->any())

        <div class="error">

            @foreach($errors->all() as $error)

                <div>
                    {{ $error }}
                </div>

            @endforeach

        </div>

    @endif


    <form
        method="POST"
        action="{{ route('admin.products.store') }}"
    >

        @csrf


        <label>
            Kategori
        </label>

        <select
            name="id_kategori"
            required
        >

            <option value="">
                Pilih kategori
            </option>

            @foreach($categories as $category)

                <option
                    value="{{ $category->id_kategori }}"
                >
                    {{ $category->nama_kategori }}
                </option>

            @endforeach

        </select>


        <label>
            Nama Produk
        </label>

        <input
            type="text"
            name="nama_produk"
            value="{{ old('nama_produk') }}"
            required
        >


        <label>
            Harga Jual
        </label>

        <input
            type="number"
            name="harga_jual"
            value="{{ old('harga_jual') }}"
            required
        >


        <label>
            Deskripsi
        </label>

        <textarea
            name="deskripsi"
            rows="4"
        >{{ old('deskripsi') }}</textarea>


        <label>
            Nama File Gambar
        </label>

        <input
            type="text"
            name="gambar_produk"
            value="{{ old('gambar_produk') }}"
            placeholder="contoh: long-sleeve.jpg"
        >


        <label>
            Ukuran
        </label>

        <div class="sizes">

            @foreach(
                ['S', 'M', 'L', 'XL']
                as $size
            )

                <label>

                    <input
                        type="checkbox"
                        name="ukuran[]"
                        value="{{ $size }}"
                    >

                    {{ $size }}

                </label>

            @endforeach

        </div>


        <label>
            Warna
        </label>

        <input
            type="text"
            name="warna"
            value="{{ old('warna') }}"
            placeholder="contoh: hitam, merah"
            required
        >

        <small>
            Pisahkan beberapa warna dengan koma.
        </small>


        <button type="submit">
            Simpan Produk
        </button>

    </form>

</div>

</body>

</html>