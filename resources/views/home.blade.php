<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>KALA Studio — Made for Everyday</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin
    >

    <link
        href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet"
    >

    <style>
        :root {
            --charcoal: #22211f;
            --cream: #f5f1e9;
            --white: #fffdf9;
            --beige: #d8c7b3;
            --brown: #806b58;
            --muted: #756f68;
            --border: #ded8d0;
        }

        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            margin: 0;
            color: var(--charcoal);
            background: var(--cream);
            font-family: "Inter", sans-serif;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        .container {
            width: min(1180px, 90%);
            margin: 0 auto;
        }

        /* NAVBAR */

        .navbar {
            position: sticky;
            top: 0;
            z-index: 100;
            background: rgba(255, 253, 249, 0.96);
            border-bottom: 1px solid var(--border);
            backdrop-filter: blur(12px);
        }

        .navbar-inner {
            min-height: 78px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
        }

        .brand {
            font-family: "DM Serif Display", serif;
            font-size: 30px;
            letter-spacing: 2px;
        }

        .brand span {
            color: var(--brown);
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .nav-link {
            padding: 11px 19px;
            font-size: 14px;
            font-weight: 600;
            border-radius: 999px;
            transition: 0.2s ease;
        }

        .login-button {
            border: 1px solid var(--charcoal);
        }

        .login-button:hover {
            color: white;
            background: var(--charcoal);
        }

        .register-button,
        .dashboard-button {
            color: white;
            background: var(--charcoal);
            border: 1px solid var(--charcoal);
        }

        .register-button:hover,
        .dashboard-button:hover {
            background: var(--brown);
            border-color: var(--brown);
        }

        /* HERO */

        .hero {
            min-height: 680px;
            position: relative;
            display: flex;
            align-items: center;
            color: white;

            background:
                linear-gradient(
                    90deg,
                    rgba(25, 24, 22, 0.75) 0%,
                    rgba(25, 24, 22, 0.35) 48%,
                    rgba(25, 24, 22, 0.08) 100%
                ),
                url('{{ asset("images/banners/hero-home.jpg") }}')
                center / cover no-repeat;
        }

        .hero-content {
            max-width: 690px;
            padding: 90px 0;
        }

        .eyebrow {
            margin: 0 0 18px;
            color: var(--beige);
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 3px;
            text-transform: uppercase;
        }

        .hero h1 {
            margin: 0;
            font-family: "DM Serif Display", serif;
            font-size: clamp(54px, 7vw, 88px);
            font-weight: 400;
            line-height: 0.98;
        }

        .hero-description {
            max-width: 540px;
            margin: 26px 0 34px;
            color: #efebe5;
            font-size: 17px;
            line-height: 1.8;
        }

        .hero-button {
            min-height: 52px;
            padding: 0 25px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: var(--charcoal);
            background: var(--white);
            border-radius: 999px;
            font-size: 14px;
            font-weight: 700;
            transition: 0.2s ease;
        }

        .hero-button:hover {
            color: white;
            background: var(--brown);
            transform: translateY(-2px);
        }

        /* FEATURES */

        .features {
            padding: 34px 0;
            background: var(--charcoal);
        }

        .feature-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
        }

        .feature-item {
            padding: 12px 30px;
            color: white;
            text-align: center;
            border-right: 1px solid #4b4945;
        }

        .feature-item:last-child {
            border-right: none;
        }

        .feature-item strong {
            display: block;
            margin-bottom: 7px;
            font-size: 14px;
        }

        .feature-item span {
            color: #bbb5ae;
            font-size: 12px;
        }

        /* PRODUCTS */

        .products-section {
            padding: 100px 0;
        }

        .section-header {
            margin-bottom: 45px;
            display: flex;
            align-items: end;
            justify-content: space-between;
            gap: 30px;
        }

        .section-header h2 {
            margin: 0;
            font-family: "DM Serif Display", serif;
            font-size: clamp(40px, 5vw, 58px);
            font-weight: 400;
        }

        .section-header p {
            max-width: 510px;
            margin: 12px 0 0;
            color: var(--muted);
            line-height: 1.7;
        }

        .collection-label {
            padding-bottom: 6px;
            font-size: 13px;
            font-weight: 700;
            border-bottom: 1px solid var(--charcoal);
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 25px;
        }

        .product-card {
            overflow: hidden;
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 20px;
            transition: 0.25s ease;
        }

        .product-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 18px 40px rgba(34, 33, 31, 0.1);
        }

        .product-image {
            height: 390px;
            overflow: hidden;
            position: relative;
            background: #e4e0da;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            display: block;
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        .product-card:hover .product-image img {
            transform: scale(1.04);
        }

        .product-badge {
            position: absolute;
            top: 16px;
            left: 16px;
            padding: 8px 12px;
            color: white;
            background: rgba(34, 33, 31, 0.82);
            border-radius: 999px;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .product-info {
            padding: 23px;
        }

        .category {
            display: block;
            margin-bottom: 8px;
            color: var(--brown);
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        .product-info h3 {
            margin: 0 0 13px;
            font-family: "DM Serif Display", serif;
            font-size: 25px;
            font-weight: 400;
        }

        .price {
            font-size: 16px;
            font-weight: 700;
        }

        .empty-products {
            grid-column: 1 / -1;
            padding: 70px 30px;
            color: var(--muted);
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 20px;
            text-align: center;
        }

        /* PROMO */

        .promo {
            padding-bottom: 100px;
        }

        .promo-box {
            padding: 60px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 40px;
            color: white;
            background: var(--charcoal);
            border-radius: 28px;
        }

        .promo-box h2 {
            margin: 0 0 14px;
            font-family: "DM Serif Display", serif;
            font-size: clamp(36px, 5vw, 52px);
            font-weight: 400;
        }

        .promo-box p {
            max-width: 620px;
            margin: 0;
            color: #c9c4bd;
            line-height: 1.8;
        }

        .promo-button {
            flex-shrink: 0;
            padding: 14px 24px;
            color: var(--charcoal);
            background: var(--beige);
            border-radius: 999px;
            font-size: 14px;
            font-weight: 700;
        }

        /* FOOTER */

        footer {
            padding: 60px 0 28px;
            color: white;
            background: #181715;
        }

        .footer-main {
            padding-bottom: 45px;
            display: flex;
            justify-content: space-between;
            gap: 50px;
        }

        .footer-brand {
            margin-bottom: 12px;
            font-family: "DM Serif Display", serif;
            font-size: 30px;
            letter-spacing: 1px;
        }

        .footer-description {
            max-width: 420px;
            margin: 0;
            color: #a9a49d;
            font-size: 14px;
            line-height: 1.7;
        }

        .footer-links {
            display: flex;
            flex-direction: column;
            gap: 12px;
            font-size: 14px;
        }

        .footer-links strong {
            margin-bottom: 4px;
        }

        .footer-links a {
            color: #aaa59e;
        }

        .footer-links a:hover {
            color: white;
        }

        .footer-bottom {
            padding-top: 24px;
            color: #817d77;
            font-size: 12px;
            border-top: 1px solid #353330;
        }

        /* RESPONSIVE */

        @media (max-width: 900px) {
            .hero {
                min-height: 600px;
            }

            .feature-grid,
            .product-grid {
                grid-template-columns: 1fr;
            }

            .feature-item {
                padding: 19px;
                border-right: none;
                border-bottom: 1px solid #4b4945;
            }

            .feature-item:last-child {
                border-bottom: none;
            }

            .product-image {
                height: 500px;
            }

            .section-header,
            .promo-box,
            .footer-main {
                align-items: flex-start;
                flex-direction: column;
            }
        }

        @media (max-width: 560px) {
            .container {
                width: 92%;
            }

            .brand {
                font-size: 24px;
            }

            .nav-link {
                padding: 9px 13px;
                font-size: 12px;
            }

            .register-button {
                display: none;
            }

            .hero {
                min-height: 560px;
                background-position: 45% center;
            }

            .hero h1 {
                font-size: 52px;
            }

            .products-section {
                padding: 70px 0;
            }

            .product-image {
                height: 390px;
            }

            .promo-box {
                padding: 38px 27px;
            }
        }
    </style>
</head>

<body>

<nav class="navbar">
    <div class="container navbar-inner">

        <a href="{{ route('home') }}" class="brand">
            KALA <span>Studio</span>
        </a>

        <div class="nav-actions">

            @guest

                <a
                    href="{{ route('login') }}"
                    class="nav-link login-button"
                >
                    Login
                </a>

                <a
                    href="{{ route('register') }}"
                    class="nav-link register-button"
                >
                    Register
                </a>

            @else

                @if(auth()->user()->tipe_akun === 'CUSTOMER')

                    <a
                        href="{{ route('customer.dashboard') }}"
                        class="nav-link dashboard-button"
                    >
                        Akun Saya
                    </a>

                @elseif(auth()->user()->tipe_akun === 'ADMIN')

                    <a
                        href="{{ route('admin.dashboard') }}"
                        class="nav-link dashboard-button"
                    >
                        Dashboard Admin
                    </a>

                @elseif(auth()->user()->tipe_akun === 'KASIR')

                    <a
                        href="{{ route('kasir.dashboard') }}"
                        class="nav-link dashboard-button"
                    >
                        Dashboard Kasir
                    </a>

                @endif

            @endguest

        </div>

    </div>
</nav>

<main>

    <section class="hero">
        <div class="container">

            <div class="hero-content">
                <p class="eyebrow">
                    Everyday Collection 2026
                </p>

                <h1>
                    Style sederhana,<br>
                    untuk setiap hari.
                </h1>

                <p class="hero-description">
                    Temukan pakaian pilihan yang nyaman,
                    modern, dan mudah dipadukan untuk
                    menemani berbagai aktivitasmu.
                </p>

                <a href="#products" class="hero-button">
                    Jelajahi Koleksi
                </a>
            </div>

        </div>
    </section>

    <section class="features">
        <div class="container feature-grid">

            <div class="feature-item">
                <strong>Everyday Essentials</strong>
                <span>Nyaman digunakan sepanjang hari</span>
            </div>

            <div class="feature-item">
                <strong>Beragam Pilihan</strong>
                <span>Ukuran dan warna sesuai gayamu</span>
            </div>

            <div class="feature-item">
                <strong>Belanja Praktis</strong>
                <span>Proses pembelian yang lebih mudah</span>
            </div>

        </div>
    </section>

    <section class="products-section" id="products">
        <div class="container">

            <div class="section-header">
                <div>
                    <p class="eyebrow">
                        Curated Collection
                    </p>

                    <h2>Koleksi Pilihan</h2>

                    <p>
                        Koleksi pakaian terbaru dari KALA Studio,
                        dirancang untuk kenyamanan dan gaya
                        sehari-hari.
                    </p>
                </div>

                <span class="collection-label">
                    Made for Everyday
                </span>
            </div>

            <div class="product-grid">

                @forelse($products as $product)

                    <article class="product-card">

                        <div class="product-image">

                            <span class="product-badge">
                                New Collection
                            </span>

                            <img
                                src="{{ $product->gambar_produk
                                    ? asset($product->gambar_produk)
                                    : asset('images/products/oversized-tshirt.jpg') }}"
                                alt="{{ $product->nama_produk }}"
                            >

                        </div>

                        <div class="product-info">

                            <span class="category">
                                {{ $product->nama_kategori }}
                            </span>

                            <h3>
                                {{ $product->nama_produk }}
                            </h3>

                            <div class="price">
                                Rp{{ number_format(
                                    $product->harga_jual,
                                    0,
                                    ',',
                                    '.'
                                ) }}
                            </div>

                        </div>

                    </article>

                @empty

                    <div class="empty-products">
                        Belum ada produk yang tersedia.
                    </div>

                @endforelse

            </div>

        </div>
    </section>

    <section class="promo">
        <div class="container">

            <div class="promo-box">
                <div>
                    <p class="eyebrow">
                        KALA Member
                    </p>

                    <h2>Belanja dan kumpulkan poin.</h2>

                    <p>
                        Daftar sebagai customer untuk melakukan
                        pembelian, melihat riwayat pesanan, dan
                        mengumpulkan poin dari setiap transaksi.
                    </p>
                </div>

                @guest
                    <a
                        href="{{ route('register') }}"
                        class="promo-button"
                    >
                        Daftar Sekarang
                    </a>
                @else
                    <a
                        href="{{ route('customer.dashboard') }}"
                        class="promo-button"
                    >
                        Masuk ke Akun
                    </a>
                @endguest
            </div>

        </div>
    </section>

</main>

<footer>
    <div class="container">

        <div class="footer-main">

            <div>
                <div class="footer-brand">
                    KALA Studio
                </div>

                <p class="footer-description">
                    Pakaian sederhana dan nyaman untuk
                    menemani setiap aktivitas sehari-hari.
                </p>
            </div>

            <div class="footer-links">
                <strong>Akses</strong>

                <a href="#products">
                    Koleksi Produk
                </a>

                @guest
                    <a href="{{ route('login') }}">
                        Login Customer
                    </a>
                @endguest

            
            </div>

        </div>

        <div class="footer-bottom">
            © {{ date('Y') }} KALA Studio · Made for Everyday.
        </div>

    </div>
</footer>

</body>
</html>