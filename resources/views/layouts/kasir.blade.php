<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Kasir') — KALA Studio</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet"
    >

    <style>
        /* Palet dan tipografi mengikuti halaman Customer (KALA Studio). */
        :root {
            --black: #1d1d1b;
            --cream: #f5f1e9;
            --white: #fffdf9;
            --beige: #e8dfd3;
            --brown: #917d68;
            --accent: #765039;
            --gray: #706d68;
            --border: #dfdbd4;
            --field: #faf9f7;
            --canvas: #ebe8e2;
            --success-bg: #edf1e7;
            --success: #3f5a34;
            --danger-bg: #f6e7e1;
            --danger: #8a3b25;
        }

        * {
            box-sizing: border-box;
        }

        [hidden] {
            display: none !important;
        }

        body {
            margin: 0;
            color: var(--black);
            background: var(--canvas);
            font-family: "Inter", sans-serif;
            font-size: 14px;
            -webkit-font-smoothing: antialiased;
        }

        a {
            color: inherit;
        }

        button,
        input,
        select {
            font: inherit;
        }

        h1 {
            margin: 0;
            font-family: "DM Serif Display", serif;
            font-size: clamp(30px, 3vw, 38px);
            font-weight: 400;
            line-height: 1.1;
        }

        h2 {
            margin: 0 0 14px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 3px;
            text-transform: uppercase;
        }

        .eyebrow {
            margin: 0 0 10px;
            color: var(--gray);
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 4px;
            text-transform: uppercase;
        }

        .muted {
            margin: 0;
            color: var(--gray);
        }

        .page-head {
            display: flex;
            flex-wrap: wrap;
            align-items: flex-end;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 26px;
        }

        .page-head .muted {
            margin-top: 8px;
        }

        .card {
            padding: 22px;
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 8px;
            box-shadow: 0 10px 24px rgba(40, 37, 32, .05);
        }

        /* Tombol */
        .btn {
            min-height: 42px;
            padding: 0 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            color: #fff;
            background: var(--black);
            border: 1px solid var(--black);
            border-radius: 4px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: background .2s, transform .2s, border-color .2s;
        }

        .btn:hover:not(:disabled) {
            background: #5d4d3e;
            border-color: #5d4d3e;
        }

        .btn.sm {
            min-height: 34px;
            padding: 0 14px;
            font-size: 12px;
        }

        .btn.light {
            color: var(--black);
            background: #fff;
            border-color: var(--border);
        }

        .btn.light:hover:not(:disabled) {
            background: var(--cream);
            border-color: var(--black);
        }

        .btn:disabled {
            opacity: .4;
            cursor: not-allowed;
        }

        .badge {
            display: inline-block;
            padding: 4px 9px;
            color: var(--accent);
            background: var(--cream);
            border-radius: 999px;
            font-size: 11px;
            font-weight: 600;
        }

        .badge.promo {
            color: #fff;
            background: var(--accent);
        }

        /* Tabel */
        .table-wrap {
            overflow-x: auto;
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 8px;
            box-shadow: 0 10px 24px rgba(40, 37, 32, .05);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 13px 16px;
            border-bottom: 1px solid var(--border);
            text-align: left;
            vertical-align: top;
        }

        tbody tr:last-child td {
            border-bottom: 0;
        }

        th {
            color: var(--gray);
            background: var(--field);
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .num {
            text-align: right;
            white-space: nowrap;
            font-variant-numeric: tabular-nums;
        }

        /* Form */
        input,
        select {
            width: 100%;
            height: 42px;
            padding: 0 13px;
            color: var(--black);
            background: var(--field);
            border: 1px solid var(--border);
            border-radius: 6px;
            outline: none;
        }

        input:focus,
        select:focus {
            border-color: var(--black);
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-size: 12px;
            font-weight: 600;
        }

        /* Pesan */
        .flash,
        .errors {
            margin-bottom: 20px;
            padding: 13px 16px;
            border-radius: 6px;
            font-size: 13px;
        }

        .flash {
            color: var(--success);
            background: var(--success-bg);
        }

        .errors {
            color: var(--danger);
            background: var(--danger-bg);
        }

        .errors div + div {
            margin-top: 4px;
        }

        .pagination {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin: 18px 0 0;
            padding: 0;
            list-style: none;
        }

        .pagination li > * {
            min-width: 36px;
            height: 36px;
            padding: 0 11px;
            display: grid;
            place-items: center;
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 4px;
            text-decoration: none;
        }

        .pagination .active > * {
            color: #fff;
            background: var(--black);
            border-color: var(--black);
        }

        .pagination .disabled > * {
            color: #b6b1aa;
        }

        /* Kerangka */
        .kasir-app {
            min-height: 100vh;
            display: flex;
        }

        .kasir-side {
            position: fixed;
            inset: 0 auto 0 0;
            width: 240px;
            padding: 26px 18px;
            display: flex;
            flex-direction: column;
            background: var(--white);
            border-right: 1px solid var(--border);
        }

        .brand {
            display: block;
            margin: 4px 10px 34px;
            text-decoration: none;
        }

        .brand strong {
            display: block;
            font-size: 24px;
            font-weight: 700;
            letter-spacing: 7px;
        }

        .brand span {
            color: var(--gray);
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 4px;
            text-transform: uppercase;
        }

        .kasir-nav {
            display: grid;
            gap: 4px;
        }

        .kasir-nav a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 12px;
            color: #4a4640;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
            text-decoration: none;
        }

        .kasir-nav a:hover {
            background: var(--cream);
        }

        .kasir-nav a.active {
            color: #fff;
            background: var(--black);
        }

        .kasir-nav svg,
        .icon {
            width: 19px;
            height: 19px;
            flex-shrink: 0;
            fill: none;
            stroke: currentColor;
            stroke-width: 1.8;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .side-note {
            margin: auto 10px 0;
            color: var(--gray);
            font-size: 11px;
            line-height: 1.5;
        }

        .kasir-main {
            width: calc(100% - 240px);
            margin-left: 240px;
        }

        .kasir-topbar {
            position: sticky;
            top: 0;
            z-index: 10;
            min-height: 72px;
            padding: 12px 32px;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 14px;
            background: rgba(255, 253, 249, .96);
            border-bottom: 1px solid var(--border);
            backdrop-filter: blur(6px);
        }

        .kasir-user {
            display: flex;
            align-items: center;
            gap: 11px;
        }

        .kasir-user-meta {
            text-align: right;
            font-size: 12px;
            line-height: 1.4;
        }

        .kasir-user-meta span {
            color: var(--gray);
        }

        .avatar {
            width: 38px;
            height: 38px;
            display: grid;
            place-items: center;
            color: var(--accent);
            background: var(--cream);
            border: 1px solid var(--beige);
            border-radius: 50%;
            font-weight: 700;
        }

        .kasir-content {
            padding: 32px;
        }

        @media (max-width: 900px) {
            .kasir-app {
                display: block;
            }

            .kasir-side {
                position: static;
                width: auto;
                padding: 14px 16px;
                flex-direction: row;
                flex-wrap: wrap;
                align-items: center;
                gap: 12px 20px;
                border-right: 0;
                border-bottom: 1px solid var(--border);
            }

            .brand {
                margin: 0;
            }

            .kasir-nav {
                grid-auto-flow: column;
            }

            .side-note {
                display: none;
            }

            .kasir-main {
                width: auto;
                margin-left: 0;
            }

            .kasir-topbar {
                position: static;
                padding: 10px 16px;
            }

            .kasir-content {
                padding: 22px 16px;
            }
        }

        @media (max-width: 520px) {
            .kasir-nav a span {
                display: none;
            }

            .kasir-user-meta {
                display: none;
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
            [
                'kasir.pos',
                'POS / Transaksi Baru',
                '<path d="M6 7h12l-1 13H7L6 7Z"/><path d="M9 7a3 3 0 0 1 6 0"/>',
            ],
            [
                'kasir.riwayat',
                'Riwayat Transaksi',
                '<path d="M6 3h12a2 2 0 0 1 2 2v16l-3-2-3 2-3-2-3 2-2-1.3V5a2 2 0 0 1 2-2Z"/><path d="M9 8h6"/><path d="M9 12h6"/>',
            ],
        ];

        $namaKasir = auth()->user()->nama;
    @endphp

    <div class="kasir-app">
        <aside class="kasir-side">
            <a class="brand" href="{{ route('kasir.dashboard') }}">
                <strong>KALA</strong>
                <span>Kasir</span>
            </a>

            <nav class="kasir-nav">
                @foreach ($kasirMenu as [$routeName, $label, $iconPaths])
                    <a
                        href="{{ route($routeName) }}"
                        class="{{ request()->routeIs($routeName) || ($routeName === 'kasir.riwayat' && request()->routeIs('kasir.struk')) ? 'active' : '' }}"
                    >
                        <svg viewBox="0 0 24 24" aria-hidden="true">{!! $iconPaths !!}</svg>
                        <span>{{ $label }}</span>
                    </a>
                @endforeach
            </nav>

            <p class="side-note">
                Transaksi offline toko. Stok berkurang otomatis setiap transaksi disimpan.
            </p>
        </aside>

        <main class="kasir-main">
            <header class="kasir-topbar">
                <div class="kasir-user">
                    <div class="kasir-user-meta">
                        <strong>{{ $namaKasir }}</strong><br>
                        <span>Kasir · {{ auth()->user()->id_akun }}</span>
                    </div>
                    <div class="avatar" aria-hidden="true">
                        {{ mb_strtoupper(mb_substr($namaKasir, 0, 1)) }}
                    </div>
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
