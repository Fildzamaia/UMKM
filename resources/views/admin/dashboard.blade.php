<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Admin Dashboard</title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f5f6f8;
        }

        nav {
            background: #222;
            color: white;

            padding: 18px 6%;

            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        nav h2 {
            margin: 0;
        }

        nav button {
            padding: 8px 15px;
            cursor: pointer;
        }

        main {
            padding: 40px 6%;
        }

        .welcome {
            margin-bottom: 30px;
        }

        .stats {
            display: grid;

            grid-template-columns:
                repeat(
                    auto-fit,
                    minmax(190px, 1fr)
                );

            gap: 20px;
        }

        .card {
            background: white;

            padding: 24px;

            border-radius: 10px;

            box-shadow:
                0 3px 12px
                rgba(0, 0, 0, .05);
        }

        .number {
            font-size: 32px;
            font-weight: bold;
            margin-top: 10px;
        }

        .menus {
            margin-top: 40px;

            display: grid;

            grid-template-columns:
                repeat(
                    auto-fit,
                    minmax(220px, 1fr)
                );

            gap: 20px;
        }

        .menu-card {
            background: white;
            padding: 25px;
            border-radius: 10px;

            text-decoration: none;
            color: #222;

            border: 1px solid #eee;
        }

        .menu-card:hover {
            border-color: #222;
        }

    </style>

</head>

<body>


<nav>

    <h2>
        UMKM Pakaian — Admin
    </h2>

    <form
        method="POST"
        action="{{ route('logout') }}"
    >

        @csrf

        <button type="submit">
            Logout
        </button>

    </form>

</nav>


<main>

    <div class="welcome">

        <h1>
            Dashboard Admin
        </h1>

        <p>
            Selamat datang,
            <strong>
                {{ auth()->user()->nama }}
            </strong>
        </p>

    </div>


    <div class="stats">

        <div class="card">
            Produk
            <div class="number">
                {{ $stats['produk'] }}
            </div>
        </div>


        <div class="card">
            Varian Produk
            <div class="number">
                {{ $stats['varian'] }}
            </div>
        </div>


        <div class="card">
            Pembelian Produk

            <div class="number">
                {{ $stats['pembelian'] }}
            </div>
        </div>


        <div class="card">
            Low Stock
            <div class="number">
                {{ $stats['low_stock_produk'] }}
            </div>
        </div>


        <div class="card">
            Supplier
            <div class="number">
                {{ $stats['supplier'] }}
            </div>
        </div>

    </div>


    <h2 style="margin-top: 45px;">
        Menu
    </h2>


    <div class="menus">

        <a
            class="menu-card"
            href="{{ route('admin.products.index') }}"
        >

            <h3>
                Kelola Produk
            </h3>

            <p>
                Produk, varian, ukuran,
                warna dan stok.
            </p>

        </a>


        <div class="menu-card">

            <h3>
                Pembelian Produk
            </h3>

            <p>
                Mencatat pembelian produk jadi dari supplier. 
            </p>

        </div>


        <div class="menu-card">

            <h3>
                Supplier
            </h3>

            <p>
                Mengelola data supplier produk UMKM.
            </p>

        </div>


        <div class="menu-card">

            <h3>
                Penjualan
            </h3>

            <p>
                Monitoring transaksi
                online dan offline.
            </p>

        </div>


        <div class="menu-card">

            <h3>
                Keuangan
            </h3>

            <p>
                Monitoring penjualan, 
                pengeluaran, dan arus keuangan. 
            </p>

        </div>

    </div>

</main>

</body>
</html>