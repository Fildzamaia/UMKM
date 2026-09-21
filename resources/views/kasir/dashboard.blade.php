<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Kasir</title>
</head>

<body>

    <h1>Dashboard Kasir</h1>

    <p>
        Selamat datang,
        <strong>{{ auth()->user()->nama }}</strong>
    </p>

    <p>
        Role:
        {{ auth()->user()->tipe_akun }}
    </p>

    <hr>

    <h3>Menu Kasir</h3>

    <ul>
        <li>POS / Transaksi Baru</li>
        <li>Cari Member</li>
        <li>Daftar Customer</li>
        <li>Histori Transaksi</li>
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