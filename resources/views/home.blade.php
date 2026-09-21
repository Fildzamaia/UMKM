<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>UMKM Pakaian</title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            color: #222;
            background: #f8f8f8;
        }

        nav {
            height: 70px;
            background: white;

            display: flex;
            align-items: center;
            justify-content: space-between;

            padding: 0 7%;

            border-bottom: 1px solid #eee;

            position: sticky;
            top: 0;
            z-index: 100;
        }

        .brand {
            font-size: 22px;
            font-weight: bold;
        }

        nav a {
            text-decoration: none;
            color: #222;

            margin-left: 12px;
        }

        .login-btn {
            padding: 9px 17px;
            border: 1px solid #222;
            border-radius: 6px;
        }

        .register-btn {
            background: #222;
            color: white;

            padding: 10px 17px;
            border-radius: 6px;
        }

        .hero {
            min-height: 430px;

            background:
                linear-gradient(
                    rgba(0, 0, 0, .25),
                    rgba(0, 0, 0, .25)
                ),
                url('{{ asset("images/banners/hero-home.jpg") }}');

            background-size: cover;
            background-position: center;

            display: flex;
            align-items: center;

            padding: 7%;

            color: white;
        }

        .hero-content {
            max-width: 600px;
        }

        .hero h1 {
            font-size: 50px;
            margin-bottom: 15px;
        }

        .hero p {
            font-size: 19px;
            line-height: 1.6;
        }

        .shop-btn {
            display: inline-block;

            margin-top: 15px;

            background: white;
            color: #222;

            text-decoration: none;

            padding: 13px 22px;

            border-radius: 6px;
            font-weight: bold;
        }

        .section {
            padding: 60px 7%;
        }

        .section-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .section-header h2 {
            font-size: 32px;
            margin-bottom: 8px;
        }

        .section-header p {
            color: #777;
        }

        .product-grid {
            display: grid;

            grid-template-columns:
                repeat(
                    auto-fit,
                    minmax(220px, 1fr)
                );

            gap: 25px;
        }

        .product-card {
            background: white;

            border-radius: 10px;

            overflow: hidden;

            box-shadow:
                0 3px 15px
                rgba(0, 0, 0, .06);

            transition: .2s;
        }

        .product-card:hover {
            transform: translateY(-4px);
        }

        .product-card img {
            width: 100%;
            height: 280px;

            object-fit: cover;

            background: #eee;
        }

        .product-info {
            padding: 18px;
        }

        .category {
            color: #777;
            font-size: 13px;
        }

        .product-info h3 {
            margin:
                8px 0
                12px;
        }

        .price {
            font-weight: bold;
            font-size: 18px;
        }

        footer {
            background: #222;
            color: white;

            padding: 40px 7%;
            margin-top: 40px;

            display: flex;
            justify-content: space-between;

            align-items: end;
        }

        footer p {
            color: #bbb;
        }

        .staff-login {
            color: #bbb;
            font-size: 13px;
            text-decoration: none;
        }

        .staff-login:hover {
            color: white;
        }

    </style>

</head>

<body>

<nav>

    <div class="brand">
        UMKM PAKAIAN
    </div>

    <div>

        @guest

            <a
                href="{{ route('login') }}"
                class="login-btn"
            >
                Login
            </a>

            <a
                href="{{ route('register') }}"
                class="register-btn"
            >
                Register
            </a>

        @else

            @if(auth()->user()->tipe_akun === 'CUSTOMER')

                <a
                    href="{{ route('customer.dashboard') }}"
                    class="login-btn"
                >
                    Akun Saya
                </a>

            @elseif(auth()->user()->tipe_akun === 'ADMIN')

                <a
                    href="{{ route('admin.dashboard') }}"
                    class="login-btn"
                >
                    Dashboard Admin
                </a>

            @else

                <a
                    href="{{ route('kasir.dashboard') }}"
                    class="login-btn"
                >
                    Dashboard Kasir
                </a>

            @endif

        @endguest

    </div>

</nav>


<section class="hero">

    <div class="hero-content">

        <h1>
            Style Sederhana,
            Nyaman Setiap Hari.
        </h1>

        <p>
            Temukan koleksi pakaian pilihan
            untuk aktivitas sehari-hari.
        </p>

        <a
            href="#products"
            class="shop-btn"
        >
            Lihat Koleksi
        </a>

    </div>

</section>


<section
    class="section"
    id="products"
>

    <div class="section-header">

        <h2>
            Koleksi Kami
        </h2>

        <p>
            Pilihan produk terbaru dari UMKM Pakaian.
        </p>

    </div>

    <div class="product-grid">

        @foreach($products as $product)

            <div class="product-card">

                <img
                    src="{{ asset($product->gambar_produk) }}"
                    alt="{{ $product->nama_produk }}"
                >

                <div class="product-info">

                    <span class="category">
                        {{ $product->nama_kategori }}
                    </span>

                    <h3>
                        {{ $product->nama_produk }}
                    </h3>

                    <div class="price">

                        Rp
                        {{ number_format(
                            $product->harga_jual,
                            0,
                            ',',
                            '.'
                        ) }}

                    </div>

                </div>

            </div>

        @endforeach

    </div>

</section>


<footer>

    <div>

        <strong>
            UMKM Pakaian
        </strong>

        <p>
            Sistem penjualan dan
            pengelolaan UMKM pakaian.
        </p>

    </div>

    <div>

        <a
            href="{{ route('staff.login') }}"
            class="staff-login"
        >
            Staff Login
        </a>

    </div>

</footer>

</body>
</html>