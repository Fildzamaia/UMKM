<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>
        Varian Produk
    </title>

    <style>

        body {
            font-family: Arial;
            background: #f5f6f8;

            padding: 40px 6%;
        }

        table {
            width: 100%;

            border-collapse: collapse;

            background: white;

            margin-top: 25px;
        }

        th,
        td {
            padding: 14px;

            text-align: left;

            border-bottom:
                1px solid #eee;
        }

        .button {
            display: inline-block;

            padding: 8px 13px;

            background: #222;
            color: white;

            text-decoration: none;

            border-radius: 5px;
        }

        .zero {
            color: #c0392b;
            font-weight: bold;
        }

    </style>

</head>

<body>

<a href="{{ route('admin.products.index') }}">
    ← Kembali ke Produk
</a>


<h1>
    {{ $product->nama_produk }}
</h1>

<p>
    Daftar varian ukuran, warna, stok
    dan Bill of Materials.
</p>


<table>

    <thead>

        <tr>
            <th>ID Varian</th>
            <th>Ukuran</th>
            <th>Warna</th>
            <th>Stok</th>
            <th>Stok Minimum</th>
            <th>BOM</th>
        </tr>

    </thead>

    <tbody>

        @foreach($variants as $variant)

            <tr>

                <td>
                    {{ $variant->id_varian }}
                </td>

                <td>
                    {{ $variant->ukuran }}
                </td>

                <td>
                    {{ ucfirst($variant->warna) }}
                </td>

                <td
                    class="{{ $variant->stok == 0 ? 'zero' : '' }}"
                >
                    {{ $variant->stok }}
                </td>

                <td>
                    {{ $variant->stok_minimum }}
                </td>

                <td>

                    <a
                        class="button"

                        href="{{ route(
                            'admin.bom.edit',
                            $variant->id_varian
                        ) }}"
                    >
                        Atur BOM
                    </a>

                </td>

            </tr>

        @endforeach

    </tbody>

</table>

</body>
</html>