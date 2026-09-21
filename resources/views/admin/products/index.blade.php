<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Kelola Produk
    </title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f5f6f8;

            margin: 0;
            padding: 40px 6%;

            color: #222;
        }

        .back-link {
            display: inline-block;

            margin-bottom: 20px;

            color: #222;

            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .top {
            display: flex;

            justify-content: space-between;
            align-items: center;

            gap: 20px;
        }

        .top h1 {
            margin-bottom: 8px;
        }

        .top p {
            margin-top: 0;

            color: #666;
        }

        .button {
            display: inline-block;

            background: #222;
            color: white;

            padding: 11px 17px;

            text-decoration: none;

            border-radius: 6px;

            white-space: nowrap;
        }

        .button:hover {
            background: #444;
        }

        .button-small {
            display: inline-block;

            background: #222;
            color: white;

            padding: 8px 12px;

            text-decoration: none;

            border-radius: 5px;

            font-size: 13px;

            white-space: nowrap;
        }

        .button-small:hover {
            background: #444;
        }

        .success {
            margin-top: 20px;

            padding: 12px 15px;

            background: #dff5e4;

            border-radius: 6px;

            color: #246b35;
        }

        .table-wrapper {
            margin-top: 30px;

            overflow-x: auto;

            background: white;

            border-radius: 10px;

            box-shadow:
                0 3px 12px
                rgba(0, 0, 0, 0.05);
        }

        table {
            width: 100%;

            border-collapse: collapse;

            min-width: 1000px;
        }

        th {
            background: #f0f1f3;

            font-size: 14px;

            color: #444;
        }

        th,
        td {
            padding: 14px;

            border-bottom:
                1px solid #eee;

            text-align: left;

            vertical-align: middle;
        }

        tbody tr:hover {
            background: #fafafa;
        }

        img {
            width: 70px;
            height: 70px;

            object-fit: cover;

            border-radius: 6px;

            background: #eee;
        }

        .product-name {
            font-weight: bold;
        }

        .status {
            display: inline-block;

            padding: 5px 10px;

            border-radius: 20px;

            font-size: 12px;

            font-weight: bold;
        }

        .status-active {
            background: #dff5e4;
            color: #246b35;
        }

        .status-inactive {
            background: #f8dddd;
            color: #9b2c2c;
        }

        .stock-zero {
            color: #c0392b;
            font-weight: bold;
        }

        .empty {
            text-align: center;

            color: #777;

            padding: 30px;
        }

    </style>

</head>

<body>


<a
    href="{{ route('admin.dashboard') }}"
    class="back-link"
>
    ← Dashboard
</a>


<div class="top">

    <div>

        <h1>
            Kelola Produk
        </h1>

        <p>
            Daftar produk, varian, dan stok
            yang tersedia pada sistem.
        </p>

    </div>


    <a
        href="{{ route('admin.products.create') }}"
        class="button"
    >
        + Tambah Produk
    </a>

</div>


@if(session('success'))

    <div class="success">

        {{ session('success') }}

    </div>

@endif


<div class="table-wrapper">

    <table>

        <thead>

            <tr>

                <th>
                    Gambar
                </th>

                <th>
                    ID
                </th>

                <th>
                    Produk
                </th>

                <th>
                    Kategori
                </th>

                <th>
                    Harga
                </th>

                <th>
                    Varian
                </th>

                <th>
                    Total Stok
                </th>

                <th>
                    Status
                </th>

                <th>
                    Aksi
                </th>

            </tr>

        </thead>


        <tbody>

            @forelse($products as $product)

                <tr>

                    <td>

                        @if($product->gambar_produk)

                            <img
                                src="{{ asset($product->gambar_produk) }}"
                                alt="{{ $product->nama_produk }}"
                            >

                        @else

                            -

                        @endif

                    </td>


                    <td>

                        {{ $product->id_produk }}

                    </td>


                    <td>

                        <span class="product-name">

                            {{ $product->nama_produk }}

                        </span>

                    </td>


                    <td>

                        {{ $product->nama_kategori }}

                    </td>


                    <td>

                        Rp
                        {{ number_format(
                            $product->harga_jual,
                            0,
                            ',',
                            '.'
                        ) }}

                    </td>


                    <td>

                        {{ $product->total_varian }}

                    </td>


                    <td
                        class="{{
                            $product->total_stok == 0
                                ? 'stock-zero'
                                : ''
                        }}"
                    >

                        {{ $product->total_stok }}

                    </td>


                    <td>

                        @if($product->status_produk === 'AKTIF')

                            <span
                                class="
                                    status
                                    status-active
                                "
                            >
                                AKTIF
                            </span>

                        @else

                            <span
                                class="
                                    status
                                    status-inactive
                                "
                            >
                                NONAKTIF
                            </span>

                        @endif

                    </td>


                    <td>

                        <a
                            href="{{ route(
                                'admin.products.variants',
                                $product->id_produk
                            ) }}"
                            class="button-small"
                        >
                            Varian / BOM
                        </a>

                    </td>

                </tr>

            @empty

                <tr>

                    <td
                        colspan="9"
                        class="empty"
                    >
                        Belum ada produk.
                    </td>

                </tr>

            @endforelse

        </tbody>

    </table>

</div>


</body>

</html>