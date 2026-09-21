<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>
        Staff Login
    </title>

    <style>

        body {
            margin: 0;

            font-family: Arial, sans-serif;

            background: #202530;

            min-height: 100vh;

            display: flex;

            justify-content: center;
            align-items: center;
        }

        .card {
            width: 390px;

            background: white;

            padding: 35px;

            border-radius: 10px;
        }

        label {
            display: block;
            margin-top: 15px;
            margin-bottom: 6px;
        }

        input {
            width: 100%;

            box-sizing: border-box;

            padding: 12px;
        }

        button {
            width: 100%;

            margin-top: 22px;

            padding: 12px;

            cursor: pointer;
        }

        .info {
            color: #777;
        }

        .error {
            background: #ffdede;
            padding: 10px;
        }

    </style>

</head>

<body>

<div class="card">

    <h1>
        Staff Login
    </h1>

    <p class="info">
        Login khusus Admin dan Kasir.
    </p>

    @if ($errors->any())

        <div class="error">
            {{ $errors->first() }}
        </div>

    @endif

    <form
        method="POST"
        action="{{ route('staff.login.process') }}"
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
            Login Staff
        </button>

    </form>

    <p>
        <a href="{{ route('home') }}">
            ← Kembali ke toko
        </a>
    </p>

</div>

</body>

</html>