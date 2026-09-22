<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Katalog Produk</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            color: #222;
            background: #f5f5f5;
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 18px 8%;
            background: white;
            border-bottom: 1px solid #ddd;
        }

        nav a {
            margin-left: 18px;
            color: #222;
            text-decoration: none;
        }

        .container {
            width: 84%;
            margin: 35px auto;
        }

        .filter {
            display: flex;
            gap: 12px;
            margin: 25px 0;
            padding: 18px;
            background: white;
            border-radius: 12px;
        }

        .filter input,
        .filter select,
        .filter button {
            padding: 11px 14px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }

        .filter input {
            flex: 1;
        }

        .filter button {
            color: white;
            background: #222;
            cursor: pointer;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 22px;
        }

        .card {
            overflow: hidden;
            background: white;
            border-radius: 14px;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.08);
        }

        .product-image,
        .no-image {
            width: 100%;
            height: 230px;
        }

        .product-image {
            object-fit: cover;
        }

        .no-image {
            display: flex;
            align-items: center;
            justify-content: center;
            color: #777;
            background: #e5e5e5;
        }

        .card-body {
            padding: 18px;
        }

        .category {
            color: #777;
            font-size: 13px;
        }

        .product-name {
            margin: 8px 0;
            font-size: 18px;
        }

        .price {
            margin: 10px 0;
            font-weight: bold;
        }

        .old-price {
            margin-right: 6px;
            color: #888;
            text-decoration: line-through;
        }

        .discount {
            display: inline-block;
            padding: 5px 8px;
            color: #a21b1b;
            background: #ffe4e4;
            border-radius: 6px;
            font-size: 12px;
        }

        .stock {
            margin-top: 10px;
            color: #555;
            font-size: 13px;
        }

        .detail-button {
            display: block;
            margin-top: 15px;
            padding: 10px;
            color: white;
            background: #222;
            border-radius: 8px;
            text-align: center;
            text-decoration: none;
        }

        .empty {
            padding: 40px;
            background: white;
            border-radius: 12px;
            text-align: center;
        }

        .pagination {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 30px;
        }

        .pagination a {
            padding: 10px 15px;
            color: white;
            background: #222;
            border-radius: 8px;
            text-decoration: none;
        }

        @media (max-width: 1000px) {
            .grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 600px) {
            .container {
                width: 92%;
            }

            .grid {
                grid-template-columns: 1fr;
            }

            .filter {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>
    <nav>
        <strong>UMKM Pakaian</strong>

        <div>
            <a href="{{ route('customer.dashboard') }}">
                Dashboard
            </a>

            <a href="{{ route('customer.catalog') }}">
                Katalog
            </a>
        </div>
    </nav>

    <main class="container">
        <h1>Katalog Produk</h1>
        <p>Pilih produk pakaian yang ingin dibeli.</p>

        <form
            class="filter"
            method="GET"
            action="{{ route('customer.catalog') }}"
        >
            <input
                type="text"
                name="search"
                placeholder="Cari produk..."
                value="{{ request('search') }}"
            >

            <select name="kategori">
                <option value="">Semua kategori</option>

                @foreach ($kategori as $itemKategori)
                    <option
                        value="{{ $itemKategori->id_kategori }}"
                        @selected(
                            request('kategori')
                            === $itemKategori->id_kategori
                        )
                    >
                        {{ $itemKategori->nama_kategori }}
                    </option>
                @endforeach
            </select>

            <button type="submit">
                Cari
            </button>
        </form>

        @if ($produk->count() > 0)
            <section class="grid">
                @foreach ($produk as $item)
                    @php
                        $promoAktif = $item->promo->first();
                        $totalStok = $item->varian->sum('stok');

                        $hargaDiskon = $promoAktif
                            ? $item->harga_jual
                                * (1 - $promoAktif->persen_diskon / 100)
                            : $item->harga_jual;
                    @endphp

                    <article class="card">
                        @if ($item->gambar_produk)
                            <img
                                class="product-image"
                                src="{{ asset(
                                    'storage/' . $item->gambar_produk
                                ) }}"
                                alt="{{ $item->nama_produk }}"
                            >
                        @else
                            <div class="no-image">
                                Belum ada gambar
                            </div>
                        @endif

                        <div class="card-body">
                            <span class="category">
                                {{ $item->kategori?->nama_kategori
                                    ?? 'Tanpa kategori'
                                }}
                            </span>

                            <h2 class="product-name">
                                {{ $item->nama_produk }}
                            </h2>

                            <div class="price">
                                @if ($promoAktif)
                                    <span class="old-price">
                                        Rp{{ number_format(
                                            $item->harga_jual,
                                            0,
                                            ',',
                                            '.'
                                        ) }}
                                    </span>

                                    Rp{{ number_format(
                                        $hargaDiskon,
                                        0,
                                        ',',
                                        '.'
                                    ) }}
                                @else
                                    Rp{{ number_format(
                                        $item->harga_jual,
                                        0,
                                        ',',
                                        '.'
                                    ) }}
                                @endif
                            </div>

                            @if ($promoAktif)
                                <span class="discount">
                                    Diskon
                                    {{ number_format(
                                        $promoAktif->persen_diskon,
                                        0
                                    ) }}%
                                </span>
                            @endif

                            <div class="stock">
                                @if ($totalStok > 0)
                                    Stok tersedia: {{ $totalStok }}
                                @else
                                    Stok habis
                                @endif
                            </div>

                            <a
                                class="detail-button"
                                href="{{ route(
                                    'customer.products.show',
                                    $item->id_produk
                                ) }}"
                            >
                                Lihat Detail
                            </a>
                        </div>
                    </article>
                @endforeach
            </section>

            <div class="pagination">
                @if ($produk->previousPageUrl())
                    <a href="{{ $produk->previousPageUrl() }}">
                        ← Sebelumnya
                    </a>
                @endif

                @if ($produk->nextPageUrl())
                    <a href="{{ $produk->nextPageUrl() }}">
                        Selanjutnya →
                    </a>
                @endif
            </div>
        @else
            <div class="empty">
                Produk tidak ditemukan.
            </div>
        @endif
    </main>
</body>
</html>