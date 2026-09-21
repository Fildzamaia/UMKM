<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Register Customer</title>

    <style>

        body {
            font-family: Arial;
            background: #f5f5f5;

            display: flex;
            justify-content: center;

            padding: 50px;
        }

        .card {
            background: white;

            width: 450px;

            padding: 30px;

            border-radius: 10px;

            box-shadow:
                0 5px 20px
                rgba(0, 0, 0, .08);
        }

        label {
            display: block;

            margin-top: 14px;
            margin-bottom: 5px;
        }

        input,
        textarea {
            width: 100%;
            padding: 11px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            margin-top: 22px;
            padding: 12px;
        }

        .error {
            background: #ffdede;
            padding: 10px;
            margin-bottom: 15px;
        }

    </style>

</head>

<body>

<div class="card">

    <h1>
        Daftar Customer
    </h1>

    @if ($errors->any())

        <div class="error">

            @foreach ($errors->all() as $error)

                <div>
                    {{ $error }}
                </div>

            @endforeach

        </div>

    @endif


    <form
        method="POST"
        action="{{ route('register.process') }}"
    >

        @csrf

        <label>Nama</label>

        <input
            type="text"
            name="nama"
            value="{{ old('nama') }}"
            required
        >


        <label>Username</label>

        <input
            type="text"
            name="username"
            value="{{ old('username') }}"
            required
        >


        <label>Email</label>

        <input
            type="email"
            name="email"
            value="{{ old('email') }}"
        >


        <label>No. Telepon</label>

        <input
            type="text"
            name="no_telp"
            value="{{ old('no_telp') }}"
            required
        >


        <label>Alamat</label>

        <textarea
            name="alamat"
        >{{ old('alamat') }}</textarea>


        <label>Password</label>

        <input
            type="password"
            name="password"
            required
        >


        <label>Konfirmasi Password</label>

        <input
            type="password"
            name="password_confirmation"
            required
        >


        <button type="submit">
            Daftar
        </button>

    </form>

    <p>
        Sudah mempunyai akun?

        <a href="{{ route('login') }}">
            Login
        </a>
    </p>

</div>

</body>
</html>