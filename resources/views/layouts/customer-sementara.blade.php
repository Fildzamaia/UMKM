{{--
    SEMENTARA (e2e): layout sederhana untuk halaman keranjang, checkout,
    pembayaran, pesanan, dan profil customer. Gaya mengikuti katalog.
    Ganti ketika developer Customer menyelesaikan desain resminya.
--}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'UMKM Pakaian')</title>

    <style>
        * {
            box-sizing: border-box;
        }

        [hidden] {
            display: none !important;
        }

        body {
            margin: 0;
            background: #f5f5f5;
            color: #222;
            font-family: Arial, sans-serif;
        }

        nav {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            padding: 18px 8%;
            background: #fff;
            border-bottom: 1px solid #ddd;
        }

        nav a {
            margin-left: 18px;
            color: #222;
            text-decoration: none;
        }

        nav a.active {
            font-weight: bold;
        }

        nav form {
            display: inline;
            margin-left: 18px;
        }

        .sementara {
            padding: 8px 8%;
            background: #fff7d6;
            color: #6b5400;
            font-size: 13px;
        }

        .container {
            width: 84%;
            margin: 35px auto;
        }

        .card {
            padding: 22px;
            background: #fff;
            border-radius: 14px;
        }

        .muted {
            color: #777;
        }

        .flash,
        .errors {
            margin-bottom: 18px;
            padding: 12px 14px;
            border-radius: 10px;
        }

        .flash {
            background: #e7f6ec;
            color: #1f6b3a;
        }

        .errors {
            background: #ffe4e4;
            color: #a21b1b;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 12px 8px;
            border-bottom: 1px solid #eee;
            text-align: left;
            vertical-align: middle;
        }

        .num {
            text-align: right;
            white-space: nowrap;
        }

        .button {
            display: inline-block;
            padding: 11px 18px;
            color: #fff;
            background: #222;
            border: 0;
            border-radius: 8px;
            font: inherit;
            text-decoration: none;
            cursor: pointer;
        }

        .button.light {
            color: #222;
            background: #fff;
            border: 1px solid #ccc;
        }

        .button.danger {
            background: #a21b1b;
        }

        .button.link {
            padding: 0;
            color: #a21b1b;
            background: none;
        }

        input,
        textarea {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font: inherit;
        }

        textarea {
            width: 100%;
            min-height: 90px;
        }

        .badge {
            display: inline-block;
            padding: 4px 9px;
            border-radius: 999px;
            background: #eee;
            font-size: 12px;
            font-weight: bold;
        }

        .promo {
            color: #a21b1b;
            font-size: 13px;
        }

        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 18px;
        }

        .total {
            display: flex;
            justify-content: space-between;
            margin-top: 16px;
            font-size: 20px;
            font-weight: bold;
        }

        @media (max-width: 800px) {
            .container {
                width: 92%;
            }

            nav a {
                margin-left: 0;
                margin-right: 14px;
            }
        }
    </style>
</head>
<body>
    @php
        $cartCount = array_sum(session('cart', []));
    @endphp

    <nav>
        <strong>UMKM Pakaian</strong>

        <div>
            <a href="{{ route('customer.dashboard') }}">Beranda</a>
            <a href="{{ route('customer.catalog') }}">Katalog</a>
            <a
                href="{{ route('customer.cart') }}"
                class="{{ request()->routeIs('customer.cart', 'customer.checkout') ? 'active' : '' }}"
            >
                Keranjang ({{ $cartCount }})
            </a>
            <a
                href="{{ route('customer.orders.index') }}"
                class="{{ request()->routeIs('customer.orders.*', 'customer.payment') ? 'active' : '' }}"
            >
                Pesanan
            </a>
            <a
                href="{{ route('customer.profile') }}"
                class="{{ request()->routeIs('customer.profile') ? 'active' : '' }}"
            >
                Profil
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="button link">Logout</button>
            </form>
        </div>
    </nav>

    <div class="sementara">
        Halaman sementara untuk uji alur end-to-end. Pembayaran di sini hanya simulasi.
    </div>

    <main class="container">
        @if (session('success'))
            <div class="flash">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="errors">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>
