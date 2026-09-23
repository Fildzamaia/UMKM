@extends('layouts.admin')
@section('title','Edit Akun')
@section('content')
    <div class="page-head">
        <div>
            <h1>Edit Akun</h1>
            <p class="muted">{{ $account->id_akun }} · {{ $account->tipe_akun }}</p>
        </div>
    </div>
    <form class="card" method="POST" action="{{ route('admin.accounts.update',$account->id_akun) }}">
        @csrf
        @method('PUT')
        <div class="form-grid">
            <div>
                <label>Nama</label>
                <input name="nama" value="{{ old('nama',$account->nama) }}" required>
            </div>
            <div>
                <label>Username</label>
                <input name="username" value="{{ old('username',$account->username) }}" required>
            </div>
            <div>
                <label>Email</label>
                <input type="email" name="email" value="{{ old('email',$account->email) }}">
            </div>
            <div>
                <label>No. Telp</label>
                <input name="no_telp" value="{{ old('no_telp',$account->no_telp) }}" required>
            </div>
            <div class="full">
                <label>Alamat</label>
                <textarea name="alamat">{{ old('alamat',$account->alamat) }}</textarea>
            </div>
            @if($account->tipe_akun!=='CUSTOMER')
                <div>
                    <label>Role Staff</label>
                    <select name="tipe_akun">
                        <option @selected($account->tipe_akun==='ADMIN')>ADMIN</option>
                        <option @selected($account->tipe_akun==='KASIR')>KASIR</option>
                    </select>
                </div>
            @endif
            <div>
                <label>Password Baru (opsional)</label>
                <input type="password" name="password">
            </div>
            <div>
                <label>Konfirmasi Password</label>
                <input type="password" name="password_confirmation">
            </div>
        </div>
        <br>
        <button class="btn">Simpan Perubahan</button>
    </form>
@endsection
