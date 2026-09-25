{{-- SEMENTARA (e2e): profil customer sederhana (baca saja) + saldo poin. --}}
@extends('layouts.customer-sementara')
@section('title', 'Profil Saya')
@section('content')
    <h1>Profil Saya</h1>

    <div class="card">
        <table>
            <tr><th>ID Akun</th><td>{{ $akun->id_akun }}</td></tr>
            <tr><th>Nama</th><td>{{ $akun->nama }}</td></tr>
            <tr><th>Username</th><td>{{ $akun->username }}</td></tr>
            <tr><th>Email</th><td>{{ $akun->email ?? '-' }}</td></tr>
            <tr><th>No. Telp</th><td>{{ $akun->no_telp }}</td></tr>
            <tr><th>Alamat</th><td>{{ $akun->alamat ?? '-' }}</td></tr>
            <tr><th>Saldo poin</th><td><strong>{{ $poin }} poin</strong></td></tr>
        </table>

        <p class="muted">
            Poin didapat dari belanja online maupun di kasir sebagai member
            (1 poin per Rp10.000). Penukaran poin belum tersedia.
        </p>

        <div class="actions">
            <a class="button light" href="{{ route('customer.orders.index') }}">Pesanan saya</a>
        </div>
    </div>
@endsection
