<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $produk->nama_produk }}</title>

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

        .container {
            width: 84%;
            margin: 40px auto;
        }

        .back {
            display: inline-block;
            margin-bottom: 20px;
            color: #222;
            text-decoration: none;
        }

        .product {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            padding: 30px;
            background: white;
            border-radius: 16px;
        }

        .image,
        .no-image {
            width: 100%;
            height: 480px;
            border-radius: 12px;
        }

        .image {
            object-fit: cover;
        }

        .no-image {
            display: flex;
            align-items: center;
            justify-content: center;
            color: #777;
            background: #e5e5e5;
        }

        .category {
            color: #777;
        }

        .description {
            line-height: 1.6;
        }

        .price {
            margin: 20px 0;
            font-size: 24px;
            font-weight: bold;
        }

        .old-price {
            margin-bottom: 5px;
            color: #888;
            font-size: 16px;
            text-decoration: line-through;
        }

        .promo {
            display: inline-block;
            padding: 7px 10px;
            color: #a21b1b;
            background: #ffe4e4;
            border-radius: 7px;
        }

        .variant {
            width: 100%;
            margin: 12px 0 20px;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }

        .button {
            width: 100%;
            padding: 13px;
            color: white;
            background: #222;
            border: 0;
            border-radius: 8px;
            cursor: pointer;
        }

        .button:disabled {
            background: #999;
            cursor: not-allowed;
        }

        @media (max-width: 800px) {
            .container {
                width: 92%;
            }

            .product {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <main class="container">
        <a
            class="back"
            href="{{ route('customer.catalog') }}"
        >
            ← Kembali ke katalog
        </a>

        @php
            $promoAktif = $produk->promo->first();

            $hargaDiskon = $promoAktif
                ? $produk->harga_jual
                    * (1 - $promoAktif->persen_diskon / 100)
                : $produk->harga_jual;

            $totalStok = $produk->varian->sum('stok');
        @endphp

        <section class="product">
            <div>
                @if ($produk->gambar_produk)
                    <img
                        class="image"
                        src="{{ asset(
                            'storage/' . $produk->gambar_produk
                        ) }}"
                        alt="{{ $produk->nama_produk }}"
                    >
                @else
                    <div class="no-image">
                        Belum ada gambar
                    </div>
                @endif
            </div>

            <div>
                <span class="category">
                    {{ $produk->kategori?->nama_kategori
                        ?? 'Tanpa kategori'
                    }}
                </span>

                <h1>{{ $produk->nama_produk }}</h1>

                <p class="description">
                    {{ $produk->deskripsi
                        ?? 'Belum ada deskripsi produk.'
                    }}
                </p>

                <div class="price">
                    @if ($promoAktif)
                        <div class="old-price">
                            Rp{{ number_format(
                                $produk->harga_jual,
                                0,
                                ',',
                                '.'
                            ) }}
                        </div>

                        Rp{{ number_format(
                            $hargaDiskon,
                            0,
                            ',',
                            '.'
                        ) }}
                    @else
                        Rp{{ number_format(
                            $produk->harga_jual,
                            0,
                            ',',
                            '.'
                        ) }}
                    @endif
                </div>

                @if ($promoAktif)
                    <div class="promo">
                        {{ $promoAktif->nama_promo }}
                        — Diskon
                        {{ number_format(
                            $promoAktif->persen_diskon,
                            0
                        ) }}%
                    </div>
                @endif

                {{-- SEMENTARA (e2e): form disambungkan ke keranjang sementara. --}}
                @if ($errors->any())
                    <div class="promo" style="display:block;margin-top:14px">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form
                    method="POST"
                    action="{{ route('customer.cart.store') }}"
                >
                    @csrf

                    <p>
                        <strong>Pilih varian</strong>
                    </p>

                    <select
                        class="variant"
                        name="id_varian"
                        required
                    >
                        <option value="">
                            Pilih ukuran dan warna
                        </option>

                        @foreach ($produk->varian as $varian)
                            <option
                                value="{{ $varian->id_varian }}"
                                @disabled($varian->stok < 1)
                            >
                                {{ $varian->warna }}
                                — Ukuran {{ $varian->ukuran }}
                                — Stok {{ $varian->stok }}
                            </option>
                        @endforeach
                    </select>

                    {{-- SEMENTARA (e2e) --}}
                    <p>
                        <strong><label for="jumlah">Jumlah</label></strong>
                    </p>

                    <input
                        class="variant"
                        type="number"
                        id="jumlah"
                        name="jumlah"
                        min="1"
                        value="1"
                    >

                    <button
                        class="button"
                        type="submit"
                        @disabled($totalStok < 1)
                    >
                        {{ $totalStok > 0
                            ? 'Tambahkan ke Keranjang'
                            : 'Stok Habis'
                        }}
                    </button>
                </form>
            </div>
        </section>
    </main>
</body>
</html>