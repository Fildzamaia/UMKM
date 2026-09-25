@extends('layouts.kasir')
@section('title', 'Dashboard Kasir')
@section('content')
    <div class="page-head">
        <div>
            <h1>Dashboard Kasir</h1>
            <p class="muted">
                Selamat datang, <strong>{{ auth()->user()->nama }}</strong>
                · Role {{ auth()->user()->tipe_akun }}
            </p>
        </div>
    </div>

    <div class="card">
        <h2>Menu Kasir</h2>

        <ul class="kasir-menu">
            <li>
                <a href="{{ route('kasir.pos') }}">POS / Transaksi Baru</a>
            </li>
            <li>
                <a href="{{ route('kasir.pos') }}#member">Cari Member</a>
                <span class="muted">(pilih member di halaman POS)</span>
            </li>
            <li>
                Daftar Customer
                <span class="muted">(belum tersedia)</span>
            </li>
            <li>
                <a href="{{ route('kasir.riwayat') }}">Histori Transaksi</a>
            </li>
        </ul>
    </div>
@endsection

@push('styles')
    <style>
        .kasir-menu {
            display: grid;
            gap: 10px;
            margin: 0;
            padding-left: 18px;
        }

        .kasir-menu a {
            color: #151515;
            font-weight: 600;
        }
    </style>
@endpush
