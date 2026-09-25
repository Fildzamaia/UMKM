<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'FORMA Kasir')</title>

    <link rel="stylesheet" href="{{ asset('css/forma.css') }}">

    <style>
        /*
         * Gaya dasar kasir. forma.css belum ada di public/css, jadi komponen
         * yang dipakai halaman kasir didefinisikan di sini.
         */
        * {
            box-sizing: border-box;
        }

        [hidden] {
            display: none !important;
        }

        body {
            margin: 0;
            background: #f7f5f2;
            color: #151515;
            font-family: system-ui, -apple-system, "Segoe UI", sans-serif;
            font-size: 14px;
        }

        h1 {
            margin: 0 0 4px;
            font-size: 22px;
        }

        h2 {
            margin: 0 0 12px;
            font-size: 16px;
        }

        .muted {
            margin: 0;
            color: #77716a;
        }

        .card {
            padding: 18px;
            background: #fff;
            border: 1px solid #e9e5df;
            border-radius: 12px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 9px 16px;
            border: 1px solid #151515;
            border-radius: 8px;
            background: #151515;
            color: #fff;
            font: inherit;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
        }

        .btn.sm {
            padding: 6px 11px;
            font-size: 13px;
        }

        .btn.light {
            background: #fff;
            color: #151515;
            border-color: #d9d3cb;
        }

        .btn:disabled {
            opacity: .45;
            cursor: not-allowed;
        }

        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 999px;
            background: #eee9e1;
            font-size: 12px;
            font-weight: 600;
        }

        .badge.promo {
            background: #fdecea;
            color: #a3261b;
        }

        .page-head {
            display: flex;
            flex-wrap: wrap;
            align-items: flex-end;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 20px;
        }

        .table-wrap {
            overflow-x: auto;
            background: #fff;
            border: 1px solid #e9e5df;
            border-radius: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 10px 12px;
            border-bottom: 1px solid #f0ece6;
            text-align: left;
            vertical-align: top;
        }

        th {
            color: #77716a;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .num {
            text-align: right;
            white-space: nowrap;
        }

        input,
        select {
            width: 100%;
            padding: 8px 10px;
            border: 1px solid #d9d3cb;
            border-radius: 8px;
            background: #fff;
            font: inherit;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
        }

        .flash,
        .errors {
            margin-bottom: 16px;
            padding: 12px 14px;
            border-radius: 10px;
        }

        .flash {
            background: #e8f5ec;
            color: #1f6b3a;
        }

        .errors {
            background: #fdecea;
            color: #a3261b;
        }

        .pagination {
            display: flex;
            flex-wrap: wrap;
            gap: 4px;
            margin: 16px 0 0;
            padding: 0;
            list-style: none;
        }

        .pagination li > * {
            display: block;
            padding: 6px 11px;
            border: 1px solid #e9e5df;
            border-radius: 8px;
            background: #fff;
            color: inherit;
            text-decoration: none;
        }

        .pagination .active > * {
            background: #151515;
            color: #fff;
        }

        /* Kerangka halaman, pola sama dengan layouts/admin */
        .kasir-app {
            display: flex;
            min-height: 100vh;
        }

        .kasir-side {
            position: fixed;
            inset: 0 auto 0 0;
            width: 235px;
            padding: 24px 15px;
            background: #fff;
            border-right: 1px solid #e9e5df;
        }

        .kasir-side .brand {
            display: block;
            margin: 5px 11px 27px;
            color: inherit;
            font-size: 19px;
            font-weight: 800;
            text-decoration: none;
        }

        .kasir-nav {
            display: grid;
            gap: 3px;
        }

        .kasir-nav a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 8px;
            color: #3b3833;
            text-decoration: none;
        }

        .kasir-nav a:hover,
        .kasir-nav a.active {
            background: #eee9e1;
            color: #151515;
            font-weight: 700;
        }

        .kasir-nav .icon {
            width: 18px;
            text-align: center;
            font-size: 13px;
        }

        .kasir-main {
            width: calc(100% - 235px);
            margin-left: 235px;
        }

        .kasir-topbar {
            position: sticky;
            top: 0;
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 12px;
            min-height: 70px;
            padding: 10px 26px;
            background: rgba(255, 255, 255, .97);
            border-bottom: 1px solid #e9e5df;
        }

        .kasir-user-meta {
            text-align: right;
            font-size: 12px;
        }

        .kasir-content {
            padding: 28px;
        }

        @media (max-width: 900px) {
            .kasir-side {
                position: static;
                width: auto;
                padding: 12px 16px;
                border-right: 0;
                border-bottom: 1px solid #e9e5df;
            }

            .kasir-side .brand {
                margin: 0 0 10px;
            }

            .kasir-nav {
                grid-auto-flow: column;
                justify-content: start;
            }

            .kasir-app {
                display: block;
            }

            .kasir-main {
                width: auto;
                margin-left: 0;
            }

            .kasir-content {
                padding: 18px 16px;
            }
        }

        @media print {
            .kasir-side,
            .kasir-topbar,
            .no-print {
                display: none !important;
            }

            body {
                background: #fff;
            }

            .kasir-main {
                width: auto;
                margin-left: 0;
            }

            .kasir-content {
                padding: 0;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    @php
        $kasirMenu = [
            ['kasir.pos', '▣', 'POS / Transaksi Baru'],
            ['kasir.riwayat', '▤', 'Riwayat Transaksi'],
        ];
    @endphp

    <div class="kasir-app">
        <aside class="kasir-side">
            <a class="brand" href="{{ route('kasir.dashboard') }}">
                FORMA Kasir
            </a>

            <nav class="kasir-nav">
                @foreach ($kasirMenu as [$routeName, $icon, $label])
                    <a
                        href="{{ route($routeName) }}"
                        class="{{ request()->routeIs($routeName) || ($routeName === 'kasir.riwayat' && request()->routeIs('kasir.struk')) ? 'active' : '' }}"
                    >
                        <span class="icon">{{ $icon }}</span>
                        <span>{{ $label }}</span>
                    </a>
                @endforeach
            </nav>
        </aside>

        <main class="kasir-main">
            <header class="kasir-topbar">
                <div class="kasir-user-meta">
                    <strong>{{ auth()->user()->nama }}</strong><br>
                    Kasir · {{ auth()->user()->id_akun }}
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn sm light">
                        Logout
                    </button>
                </form>
            </header>

            <section class="kasir-content">
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
            </section>
        </main>
    </div>

    @stack('scripts')
</body>
</html>
