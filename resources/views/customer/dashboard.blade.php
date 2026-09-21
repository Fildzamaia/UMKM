<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Customer</title>
</head>

<body>

    <h1>Dashboard Customer</h1>

    <p>
        Selamat datang,
        <strong>{{ auth()->user()->nama }}</strong>
    </p>

    <p>
        Role:
        {{ auth()->user()->tipe_akun }}
    </p>

    <hr>

    <h3>Menu Customer</h3>

    <ul>
        <li>Katalog Produk</li>
        <li>Promo</li>
        <li>Keranjang</li>
        <li>Checkout</li>
        <li>Histori Belanja</li>
        <li>Member Point</li>
    </ul>

    <form
        method="POST"
        action="{{ route('logout') }}"
    >
        @csrf

        <button type="submit">
            Logout
        </button>
    </form>

</body>
</html>