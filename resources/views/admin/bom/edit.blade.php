<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>
        Atur BOM
    </title>

    <style>

        body {
            font-family: Arial;

            background: #f5f6f8;

            padding: 40px;
        }

        .card {
            max-width: 850px;

            margin: auto;

            background: white;

            padding: 30px;

            border-radius: 10px;
        }

        table {
            width: 100%;

            border-collapse: collapse;

            margin-top: 25px;
        }

        th,
        td {
            padding: 12px;

            text-align: left;

            border-bottom:
                1px solid #eee;
        }

        input {
            width: 120px;
            padding: 8px;
        }

        button {
            margin-top: 25px;

            background: #222;
            color: white;

            border: 0;

            padding: 12px 20px;

            border-radius: 5px;

            cursor: pointer;
        }

        .success {
            background: #dff5e4;

            padding: 12px;

            margin: 20px 0;

            border-radius: 6px;
        }

        .variant-info {
            background: #f5f5f5;

            padding: 15px;

            border-radius: 6px;

            margin-bottom: 20px;
        }

    </style>

</head>

<body>

<div class="card">

    <a
        href="{{ route(
            'admin.products.variants',
            $variant->id_produk
        ) }}"
    >
        ← Kembali ke Varian
    </a>


    <h1>
        Atur BOM
    </h1>


    <div class="variant-info">

        <strong>
            {{ $variant->nama_produk }}
        </strong>

        <br>

        ID:
        {{ $variant->id_varian }}

        <br>

        Ukuran:
        {{ $variant->ukuran }}

        <br>

        Warna:
        {{ ucfirst($variant->warna) }}

    </div>


    @if(session('success'))

        <div class="success">
            {{ session('success') }}
        </div>

    @endif


    <p>
        Isi kebutuhan bahan untuk menghasilkan
        <strong>1 unit</strong>
        produk varian ini.
    </p>


    <form
        method="POST"

        action="{{ route(
            'admin.bom.update',
            $variant->id_varian
        ) }}"
    >

        @csrf
        @method('PUT')


        <table>

            <thead>

                <tr>
                    <th>Bahan</th>
                    <th>Warna</th>
                    <th>Satuan</th>
                    <th>Stok Saat Ini</th>
                    <th>Kebutuhan / Unit</th>
                </tr>

            </thead>


            <tbody>

                @foreach($materials as $material)

                    <tr>

                        <td>
                            {{ $material->nama_bahan }}
                        </td>

                        <td>
                            {{ ucfirst($material->warna) }}
                        </td>

                        <td>
                            {{ $material->satuan }}
                        </td>

                        <td>
                            {{ $material->stok }}
                        </td>

                        <td>

                            <input
                                type="number"

                                name="bahan[
                                    {{ $material->id_bahan }}
                                ]"

                                step="0.001"

                                min="0"

                                value="{{
                                    $bom[
                                        $material->id_bahan
                                    ] ?? ''
                                }}"

                                placeholder="0"
                            >

                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>


        <button type="submit">
            Simpan BOM
        </button>

    </form>

</div>

</body>

</html>