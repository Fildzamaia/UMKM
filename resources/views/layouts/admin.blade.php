<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'FORMA Admin')</title>

    <link rel="stylesheet" href="{{ asset('css/forma.css') }}">

    <style>
        .admin-app {
            display: flex;
            min-height: 100vh;
        }

        .admin-side {
            position: fixed;
            inset: 0 auto 0 0;
            width: 235px;
            padding: 24px 15px;
            background: #fff;
            border-right: 1px solid #e9e5df;
        }

        .admin-side .brand {
            margin: 5px 11px 27px;
            font-size: 19px;
        }

        .admin-nav {
            display: grid;
            gap: 3px;
        }

        .admin-nav a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 8px;
            color: #3b3833;
            text-decoration: none;
            font-size: 14px;
        }

        .admin-nav a:hover,
        .admin-nav a.active {
            background: #eee9e1;
            color: #151515;
            font-weight: 700;
        }

        .admin-nav .icon {
            width: 18px;
            text-align: center;
            font-size: 13px;
        }

        .admin-main {
            width: calc(100% - 235px);
            margin-left: 235px;
        }

        .admin-topbar {
            position: sticky;
            top: 0;
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            min-height: 70px;
            padding: 10px 26px;
            background: rgba(255, 255, 255, .97);
            border-bottom: 1px solid #e9e5df;
        }

        .admin-user {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-right: 12px;
        }

        .admin-user img {
            width: 38px;
            height: 38px;
            object-fit: cover;
            border-radius: 50%;
        }

        .admin-user-meta {
            text-align: right;
            font-size: 12px;
        }

        .admin-content {
            padding: 28px;
        }

        @media (max-width: 900px) {
            .admin-side {
                width: 195px;
            }

            .admin-main {
                width: calc(100% - 195px);
                margin-left: 195px;
            }

            .admin-nav .icon {
                display: none;
            }
        }
    </style>
</head>
<body>
    @php
        $adminMenu = [
            ['admin.dashboard', '⌂', 'Dashboard'],
            ['admin.products.*', '▣', 'Produk'],
            ['admin.categories.*', '◇', 'Kategori '],
            ['admin.suppliers.*', '♧', 'Supplier'],
            ['admin.purchases.*', '□', 'Pembelian Produk'],
            ['admin.sales.*', '▤', 'Penjualan'],
            ['admin.promos.*', '○', 'Promo'],
            ['admin.expenses.*', '◫', 'Pengeluaran'],
            ['admin.accounts.*', '◎', 'Akun'],
            ['admin.reports.*', '▥', 'Laporan'],
        ];
    @endphp

    <div class="admin-app">
        <aside class="admin-side">
            <div class="brand">FORMA</div>

            <nav class="admin-nav">
                @foreach ($adminMenu as [$pattern, $icon, $label])
                    @php
                        $routeName = match ($pattern) {
                            'admin.dashboard' => 'admin.dashboard',
                            'admin.products.*' => 'admin.products.index',
                            'admin.categories.*' => 'admin.categories.index',
                            'admin.suppliers.*' => 'admin.suppliers.index',
                            'admin.purchases.*' => 'admin.purchases.index',
                            'admin.sales.*' => 'admin.sales.index',
                            'admin.promos.*' => 'admin.promos.index',
                            'admin.expenses.*' => 'admin.expenses.index',
                            'admin.accounts.*' => 'admin.accounts.index',
                            default => 'admin.reports.index',
                        };
                    @endphp

                    <a
                        href="{{ route($routeName) }}"
                        class="{{ request()->routeIs($pattern) ? 'active' : '' }}"
                    >
                        <span class="icon">{{ $icon }}</span>
                        <span>{{ $label }}</span>
                    </a>
                @endforeach
            </nav>
        </aside>

        <main class="admin-main">
            <header class="admin-topbar">
                <div class="admin-user">
                    <div class="admin-user-meta">
                        <strong>{{ auth()->user()->nama }}</strong><br>
                        Administrator · {{ auth()->user()->id_akun }}
                    </div>
                    <img
                        src="{{ asset('images/avatars/avatar-admin.png') }}"
                        alt="Avatar Admin"
                    >
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn sm light">
                        Logout
                    </button>
                </form>
            </header>

            <section class="admin-content">
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
</body>
</html>
