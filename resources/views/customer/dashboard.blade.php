<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>KALA Studio — Beranda</title>

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
            --black: #1d1d1b;
            --cream: #f5f1e9;
            --white: #fffdf9;
            --beige: #e8dfd3;
            --brown: #917d68;
            --gray: #706d68;
            --border: #dfdbd4;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            color: var(--black);
            background: #ebe8e2;
            font-family: "Inter", sans-serif;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        button,
        input {
            font: inherit;
        }

        .page {
            width: min(1380px, 94%);
            margin: 28px auto;
            overflow: visible;
            background: var(--white);
            border: 1px solid #d3cfc8;
            border-radius: 14px;
            box-shadow: 0 20px 55px rgba(47, 42, 35, 0.13);
        }

        .browser-bar {
            height: 28px;
            padding: 0 15px;
            display: flex;
            align-items: center;
            gap: 7px;
            background: #242321;
            border-radius: 14px 14px 0 0;
        }

        .browser-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
        }

        .browser-dot.red {
            background: #f06c62;
        }

        .browser-dot.yellow {
            background: #eec35a;
        }

        .browser-dot.green {
            background: #73bb72;
        }

        /* NAVBAR */

        nav {
            min-height: 76px;
            padding: 0 42px;
            display: flex;
            align-items: center;
            gap: 42px;
            position: relative;
            z-index: 100;
            background: var(--white);
            border-bottom: 1px solid var(--border);
        }

        .brand {
            flex-shrink: 0;
            font-size: 25px;
            font-weight: 700;
            letter-spacing: 7px;
        }

        .nav-menu {
            display: flex;
            align-items: center;
            gap: 28px;
            font-size: 13px;
            font-weight: 500;
        }

        .nav-menu a {
            padding: 28px 0 24px;
            border-bottom: 2px solid transparent;
        }

        .nav-menu a:hover,
        .nav-menu a.active {
            border-bottom-color: var(--black);
        }

        .nav-right {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 18px;
        }

        /* SEARCH */

        .search-form {
            width: min(330px, 100%);
            position: relative;
        }

        .search-form span {
            position: absolute;
            top: 50%;
            left: 14px;
            color: var(--gray);
            transform: translateY(-50%);
        }

        .search-form input {
            width: 100%;
            height: 40px;
            padding: 0 15px 0 40px;
            background: #faf9f7;
            border: 1px solid var(--border);
            border-radius: 6px;
            outline: none;
        }

        .search-form input:focus {
            border-color: var(--black);
        }

        /* NAVBAR ICON */

        .icon-button {
            width: 38px;
            height: 38px;
            padding: 0;
            display: grid;
            place-items: center;
            position: relative;
            color: var(--black);
            background: transparent;
            border: none;
            border-radius: 50%;
            cursor: pointer;
        }

        .icon-button:hover {
            background: var(--cream);
        }

        .icon-button svg {
            width: 21px;
            height: 21px;
            fill: none;
            stroke: currentColor;
            stroke-width: 1.8;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .cart-count {
            width: 18px;
            height: 18px;
            position: absolute;
            top: -2px;
            right: -2px;
            display: grid;
            place-items: center;
            color: white;
            background: #765039;
            border-radius: 50%;
            font-size: 10px;
        }

        /* PROFILE DROPDOWN */

        .profile-wrapper {
            position: relative;
        }

        .profile-dropdown {
            width: 230px;
            padding: 10px;
            position: absolute;
            top: 49px;
            right: 0;
            z-index: 500;
            display: none;
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 10px;
            box-shadow: 0 18px 45px rgba(35, 31, 27, 0.18);
        }

        .profile-dropdown.show {
            display: block;
            animation: dropdownAppear 0.15s ease;
        }

        @keyframes dropdownAppear {
            from {
                opacity: 0;
                transform: translateY(-5px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .profile-header {
            padding: 12px;
            border-bottom: 1px solid var(--border);
        }

        .profile-header strong {
            display: block;
            margin-bottom: 5px;
            font-size: 13px;
        }

        .profile-header span {
            color: var(--gray);
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .dropdown-link {
            width: 100%;
            padding: 11px 12px;
            display: flex;
            align-items: center;
            gap: 11px;
            color: var(--black);
            background: transparent;
            border: none;
            border-radius: 6px;
            font-size: 12px;
            text-align: left;
            cursor: pointer;
        }

        .dropdown-link:hover {
            background: var(--cream);
        }

        .dropdown-link svg {
            width: 17px;
            height: 17px;
            fill: none;
            stroke: currentColor;
            stroke-width: 1.7;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .dropdown-divider {
            height: 1px;
            margin: 7px 0;
            background: var(--border);
        }

        .logout-link {
            color: #a33b32;
        }

        .profile-dropdown form {
            margin: 0;
        }

        /* HERO */

        .hero {
            min-height: 495px;
            display: grid;
            grid-template-columns: 1.15fr 0.85fr;
            background: #eee8df;
        }

        .hero-left {
            padding: 55px 0 42px 48px;
            display: grid;
            grid-template-columns: 1fr 320px;
            position: relative;
            overflow: hidden;
        }

        .hero-copy {
            position: relative;
            z-index: 2;
        }

        .eyebrow {
            margin: 0 0 22px;
            color: #67615a;
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 4px;
            text-transform: uppercase;
        }

        .hero h1 {
            max-width: 500px;
            margin: 0;
            font-family: "DM Serif Display", serif;
            font-size: clamp(46px, 5vw, 69px);
            font-weight: 400;
            line-height: 1.02;
        }

        .hero-description {
            max-width: 460px;
            margin: 20px 0 28px;
            color: var(--gray);
            font-size: 14px;
            line-height: 1.75;
        }

        .primary-button {
            min-height: 48px;
            padding: 0 26px;
            display: inline-flex;
            align-items: center;
            gap: 22px;
            color: white;
            background: var(--black);
            border-radius: 4px;
            font-size: 13px;
            font-weight: 600;
            transition: 0.2s;
        }

        .primary-button:hover {
            background: #5d4d3e;
            transform: translateY(-2px);
        }

        .hero-note {
            margin-top: 40px;
            padding-top: 18px;
            color: #6d6963;
            font-size: 11px;
            border-top: 1px solid #8e8982;
        }

        .hero-photo {
            min-height: 440px;
            align-self: end;
            position: relative;
        }

        .hero-photo img {
            width: 100%;
            height: 100%;
            min-height: 440px;
            display: block;
            object-fit: cover;
            object-position: center 15%;
            mix-blend-mode: multiply;
        }

        /* PRODUCT SIDE */

        .products-side {
            padding: 42px 34px 30px;
            background: #f9f7f3;
        }

        .products-heading {
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .products-heading h2 {
            margin: 0;
            font-size: 11px;
            letter-spacing: 3px;
            text-transform: uppercase;
        }

        .products-heading a {
            color: var(--gray);
            font-size: 11px;
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 13px;
        }

        .product-card {
            overflow: hidden;
            background: white;
            border: 1px solid var(--border);
            border-radius: 6px;
            transition: 0.2s;
        }

        .product-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 24px rgba(40, 37, 32, 0.09);
        }

        .product-image {
            height: 142px;
            overflow: hidden;
            background: #efede9;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            display: block;
            object-fit: cover;
        }

        .product-info {
            padding: 10px;
        }

        .product-info h3 {
            min-height: 30px;
            margin: 0 0 5px;
            font-size: 11px;
            line-height: 1.35;
        }

        .price {
            margin-bottom: 9px;
            font-size: 12px;
            font-weight: 700;
        }

        .product-bottom {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .colors {
            display: flex;
            gap: 5px;
        }

        .color {
            width: 10px;
            height: 10px;
            border: 1px solid #d5d1cb;
            border-radius: 50%;
        }

        .color.black {
            background: #1d1d1b;
        }

        .color.gray {
            background: #9d9b96;
        }

        .color.beige {
            background: #d9cbb9;
        }

        .small-cart {
            width: 28px;
            height: 28px;
            display: grid;
            place-items: center;
            color: white;
            background: var(--black);
            border-radius: 4px;
            font-size: 12px;
        }

        /* BENEFITS */

        .benefits {
            padding: 25px 34px;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            background: var(--white);
            border-top: 1px solid var(--border);
            border-radius: 0 0 14px 14px;
        }

        .benefit {
            display: flex;
            align-items: flex-start;
            gap: 11px;
        }

        .benefit-icon {
            width: 31px;
            height: 31px;
            display: grid;
            place-items: center;
            flex-shrink: 0;
            background: var(--cream);
            border-radius: 50%;
            font-size: 14px;
        }

        .benefit strong {
            display: block;
            margin-bottom: 4px;
            font-size: 10px;
        }

        .benefit span {
            color: var(--gray);
            font-size: 9px;
            line-height: 1.4;
        }

        /* RESPONSIVE */

        @media (max-width: 1100px) {
            .hero {
                grid-template-columns: 1fr;
            }

            .hero-left {
                grid-template-columns: 1fr 300px;
            }

            .products-grid {
                grid-template-columns: repeat(4, 1fr);
            }

            .nav-menu {
                display: none;
            }
        }

        @media (max-width: 780px) {
            .page {
                width: 100%;
                margin: 0;
                border: none;
                border-radius: 0;
            }

            .browser-bar {
                display: none;
            }

            nav {
                padding: 0 20px;
            }

            .search-form {
                display: none;
            }

            .hero-left {
                padding: 45px 25px 0;
                grid-template-columns: 1fr;
            }

            .hero-photo {
                margin-top: 30px;
            }

            .products-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .benefits {
                grid-template-columns: repeat(2, 1fr);
                border-radius: 0;
            }
        }

        @media (max-width: 480px) {
            .brand {
                font-size: 20px;
                letter-spacing: 4px;
            }

            .products-side {
                padding: 35px 20px;
            }

            .benefits {
                grid-template-columns: 1fr;
            }

            .profile-dropdown {
                right: -8px;
            }
        }
    </style>
</head>

<body>

<div class="page">

    <div class="browser-bar">
        <span class="browser-dot red"></span>
        <span class="browser-dot yellow"></span>
        <span class="browser-dot green"></span>
    </div>

    <nav>

        <a
            href="{{ route('customer.dashboard') }}"
            class="brand"
        >
            KALA
        </a>

        <div class="nav-menu">

            <a
                href="{{ route('customer.dashboard') }}"
                class="active"
            >
                Beranda
            </a>

            <a href="{{ route('customer.catalog') }}">
                Katalog
            </a>

            <a href="#">
                Promo
            </a>

            {{-- SEMENTARA (e2e): tautan ke halaman pesanan & profil sementara --}}
            <a href="{{ route('customer.orders.index') }}">
                Pesanan
            </a>

            <a href="{{ route('customer.profile') }}">
                Profil
            </a>

        </div>

        <div class="nav-right">

            <form
                action="{{ route('customer.catalog') }}"
                method="GET"
                class="search-form"
            >
                <span>⌕</span>

                <input
                    type="search"
                    name="search"
                    placeholder="Cari produk, kategori, atau gaya..."
                >
            </form>

            {{-- SEMENTARA (e2e): tautan & jumlah item keranjang sementara --}}
            <a
                href="{{ route('customer.cart') }}"
                class="icon-button"
                title="Keranjang"
            >
                <svg viewBox="0 0 24 24">
                    <path
                        d="M3 4h2l2.2 10.1a2 2 0 0 0 2 1.6h7.9a2 2 0 0 0 2-1.6L21 7H6"
                    />

                    <circle cx="10" cy="20" r="1"/>

                    <circle cx="18" cy="20" r="1"/>
                </svg>

                <span class="cart-count">{{ array_sum(session('cart', [])) }}</span>
            </a>

            <div class="profile-wrapper">

                <button
                    type="button"
                    class="icon-button"
                    id="profileButton"
                    title="Akun"
                    aria-label="Buka menu akun"
                    aria-expanded="false"
                >
                    <svg viewBox="0 0 24 24">
                        <circle cx="12" cy="8" r="4"/>

                        <path
                            d="M4.5 21a7.5 7.5 0 0 1 15 0"
                        />
                    </svg>
                </button>

                <div
                    class="profile-dropdown"
                    id="profileDropdown"
                >

                    <div class="profile-header">
                        <strong>
                            {{ auth()->user()->nama }}
                        </strong>

                        <span>
                            {{ auth()->user()->tipe_akun }}
                        </span>
                    </div>

                    {{-- SEMENTARA (e2e) --}}
                    <a href="{{ route('customer.profile') }}" class="dropdown-link">
                        <svg viewBox="0 0 24 24">
                            <circle cx="12" cy="8" r="4"/>

                            <path
                                d="M4.5 21a7.5 7.5 0 0 1 15 0"
                            />
                        </svg>

                        Profil Saya
                    </a>

                    {{-- SEMENTARA (e2e) --}}
                    <a href="{{ route('customer.orders.index') }}" class="dropdown-link">
                        <svg viewBox="0 0 24 24">
                            <path
                                d="M6 3h12a2 2 0 0 1 2 2v16l-3-2-3 2-3-2-3 2-2-1.3V5a2 2 0 0 1 2-2Z"
                            />

                            <path d="M9 8h6"/>
                            <path d="M9 12h6"/>
                        </svg>

                        Pesanan Saya
                    </a>

                    <div class="dropdown-divider"></div>

                    <form
                        method="POST"
                        action="{{ route('logout') }}"
                    >
                        @csrf

                        <button
                            type="submit"
                            class="dropdown-link logout-link"
                        >
                            <svg viewBox="0 0 24 24">
                                <path
                                    d="M10 17l5-5-5-5"
                                />

                                <path
                                    d="M15 12H3"
                                />

                                <path
                                    d="M14 3h5a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-5"
                                />
                            </svg>

                            Logout
                        </button>
                    </form>

                </div>

            </div>

        </div>

    </nav>

    <section class="hero">

        <div class="hero-left">

            <div class="hero-copy">

                <p class="eyebrow">
                    Fashion for real people
                </p>

                <h1>
                    Gaya sehari-hari,<br>
                    dibuat lebih mudah.
                </h1>

                <p class="hero-description">
                    Pakaian yang nyaman, versatile, dan selalu
                    relevan untuk setiap cerita harimu.
                </p>

                <a
                    href="{{ route('customer.catalog') }}"
                    class="primary-button"
                >
                    Belanja Sekarang
                    <span>→</span>
                </a>

                <div class="hero-note">
                    Nyaman dipakai · Mudah dipadukan · Selalu jadi pilihan
                </div>

            </div>

            <div class="hero-photo">
                <img
                    src="{{ asset('images/products/oversized-tshirt.jpg') }}"
                    alt="Koleksi KALA Studio"
                >
            </div>

        </div>

        <div class="products-side">

            <div class="products-heading">
                <h2>Produk Pilihan</h2>

                <a href="{{ route('customer.catalog') }}">
                    Lihat Semua →
                </a>
            </div>

            <div class="products-grid">

                <a
                    href="{{ route('customer.catalog') }}"
                    class="product-card"
                >
                    <div class="product-image">
                        <img
                            src="{{ asset('images/products/oversized-tshirt.jpg') }}"
                            alt="Oversized Essential Tee"
                        >
                    </div>

                    <div class="product-info">
                        <h3>Oversized Essential Tee</h3>

                        <div class="price">
                            Rp125.000
                        </div>

                        <div class="product-bottom">
                            <div class="colors">
                                <span class="color black"></span>
                                <span class="color gray"></span>
                                <span class="color beige"></span>
                            </div>

                            <span class="small-cart">🛒</span>
                        </div>
                    </div>
                </a>

                <a
                    href="{{ route('customer.catalog') }}"
                    class="product-card"
                >
                    <div class="product-image">
                        <img
                            src="{{ asset('images/products/hoodie.jpg') }}"
                            alt="Essential Hoodie"
                        >
                    </div>

                    <div class="product-info">
                        <h3>Essential Hoodie</h3>

                        <div class="price">
                            Rp199.000
                        </div>

                        <div class="product-bottom">
                            <div class="colors">
                                <span class="color black"></span>
                                <span class="color gray"></span>
                                <span class="color beige"></span>
                            </div>

                            <span class="small-cart">🛒</span>
                        </div>
                    </div>
                </a>

                <a
                    href="{{ route('customer.catalog') }}"
                    class="product-card"
                >
                    <div class="product-image">
                        <img
                            src="{{ asset('images/products/straight-pants.jpg') }}"
                            alt="Wide Leg Pants"
                        >
                    </div>

                    <div class="product-info">
                        <h3>Wide Leg Pants</h3>

                        <div class="price">
                            Rp189.000
                        </div>

                        <div class="product-bottom">
                            <div class="colors">
                                <span class="color black"></span>
                                <span class="color gray"></span>
                            </div>

                            <span class="small-cart">🛒</span>
                        </div>
                    </div>
                </a>

                <a
                    href="{{ route('customer.catalog') }}"
                    class="product-card"
                >
                    <div class="product-image">
                        <img
                            src="{{ asset('images/products/crewneck.jpg') }}"
                            alt="Minimal Sweatshirt"
                        >
                    </div>

                    <div class="product-info">
                        <h3>Minimal Sweatshirt</h3>

                        <div class="price">
                            Rp159.000
                        </div>

                        <div class="product-bottom">
                            <div class="colors">
                                <span class="color black"></span>
                                <span class="color gray"></span>
                                <span class="color beige"></span>
                            </div>

                            <span class="small-cart">🛒</span>
                        </div>
                    </div>
                </a>

            </div>

        </div>

    </section>

    <section class="benefits">

        <div class="benefit">
            <div class="benefit-icon">
                ▣
            </div>

            <div>
                <strong>
                    Pengiriman Seluruh Indonesia
                </strong>

                <span>
                    Aman dan terpercaya
                </span>
            </div>
        </div>

        <div class="benefit">
            <div class="benefit-icon">
                ◇
            </div>

            <div>
                <strong>
                    Produk Pilihan
                </strong>

                <span>
                    Kualitas terjamin
                </span>
            </div>
        </div>

        <div class="benefit">
            <div class="benefit-icon">
                ↻
            </div>

            <div>
                <strong>
                    Mudah Berbelanja
                </strong>

                <span>
                    Praktis dan sederhana
                </span>
            </div>
        </div>

        <div class="benefit">
            <div class="benefit-icon">
                ♡
            </div>

            <div>
                <strong>
                    KALA Member
                </strong>

                <span>
                    Kumpulkan poin belanja
                </span>
            </div>
        </div>

    </section>

</div>

<script>
    const profileButton =
        document.getElementById('profileButton');

    const profileDropdown =
        document.getElementById('profileDropdown');

    profileButton.addEventListener(
        'click',
        function (event) {
            event.stopPropagation();

            const isOpen =
                profileDropdown.classList.toggle('show');

            profileButton.setAttribute(
                'aria-expanded',
                isOpen ? 'true' : 'false'
            );
        }
    );

    profileDropdown.addEventListener(
        'click',
        function (event) {
            event.stopPropagation();
        }
    );

    document.addEventListener(
        'click',
        function () {
            profileDropdown.classList.remove('show');

            profileButton.setAttribute(
                'aria-expanded',
                'false'
            );
        }
    );

    document.addEventListener(
        'keydown',
        function (event) {
            if (event.key === 'Escape') {
                profileDropdown.classList.remove('show');

                profileButton.setAttribute(
                    'aria-expanded',
                    'false'
                );
            }
        }
    );
</script>

</body>
</html>