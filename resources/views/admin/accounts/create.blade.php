@extends('layouts.admin')
@section('title','Tambah Akun')
@section('content')
    <div class="page-head">
        <h1>Tambah Akun Staff</h1>
    </div>
    <form class="card" method="POST" action="{{ route('admin.accounts.store') }}">
        @csrf
        <div class="form-grid">
            <div>
                <label>Nama</label>
                <input name="nama" value="{{ old('nama') }}" required>
            </div>
            <div>
                <label>Username</label>
                <input name="username" value="{{ old('username') }}" required>
            </div>
            <div>
                <label>Email</label>
                <input type="email" name="email" value="{{ old('email') }}">
            </div>
            <div>
                <label>No. Telp</label>
                <input name="no_telp" value="{{ old('no_telp') }}" required>
            </div>
            <div class="full">
                <label>Alamat</label>
                <textarea name="alamat">{{ old('alamat') }}</textarea>
            </div>
            <div>
                <label>Role</label>
                <select name="tipe_akun">
                    <option>ADMIN</option>
                    <option>KASIR</option>
                </select>
            </div>
            <div>
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <div>
                <label>Konfirmasi</label>
                <input type="password" name="password_confirmation" required>
            </div>
        </div>
        <br>
        <button class="btn">Simpan</button>
    </form>
@endsection
