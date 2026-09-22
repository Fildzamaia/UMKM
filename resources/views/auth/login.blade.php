<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Login Customer - UMKM Pakaian</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f5f5f5;

            min-height: 100vh;

            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-card {
            background: white;
            width: 400px;
            padding: 35px;
            border-radius: 12px;

            box-shadow:
                0 5px 20px rgba(0, 0, 0, 0.08);
        }

        h1 {
            margin-top: 0;
            margin-bottom: 8px;
        }

        .subtitle {
            color: #666;
            margin-bottom: 30px;
        }

        label {
            display: block;
            margin-top: 15px;
            margin-bottom: 6px;
        }

        input {
            width: 100%;
            padding: 12px;

            border: 1px solid #ccc;
            border-radius: 6px;
        }

        input:focus {
            outline: none;
            border-color: #333;
        }

        button {
            margin-top: 24px;
            width: 100%;
            padding: 12px;

            border: 0;
            border-radius: 6px;

            background: #222;
            color: white;

            cursor: pointer;
            font-size: 15px;
        }

        button:hover {
            background: #444;
        }

        .error {
            margin-bottom: 15px;
            padding: 10px;

            background: #f7dddd;

            border-radius: 5px;
            color: #a94442;
        }

        .bottom-links {
            margin-top: 25px;
            text-align: center;

            font-size: 14px;
            color: #666;
        }

        .bottom-links p {
            margin: 10px 0;
        }

        .bottom-links a {
            color: #222;
            font-weight: bold;
            text-decoration: none;
        }

        .bottom-links a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

<div class="login-card">

    <h1>Login Akun</h1>

    <div class="subtitle">
        Masuk sebagai Customer, Kasir, atau Admin.
    </div>

    @if ($errors->any())

        <div class="error">
            {{ $errors->first() }}
        </div>

    @endif


    <form
        method="POST"
        action="{{ route('login.process') }}"
    >

        @csrf

        <label>
            Username
        </label>

        <input
            type="text"
            name="username"
            value="{{ old('username') }}"
            required
            autofocus
        >


        <label>
            Password
        </label>

        <input
            type="password"
            name="password"
            required
        >


        <button type="submit">
            Login
        </button>

    </form>


    <div class="bottom-links">

        <p>
            Belum memiliki akun?

            <a href="{{ route('register') }}">
                Daftar sekarang
            </a>
        </p>

        <p>
            <a href="{{ route('home') }}">
                ← Kembali ke toko
            </a>
        </p>

    </div>

</div>

</body>

</html>