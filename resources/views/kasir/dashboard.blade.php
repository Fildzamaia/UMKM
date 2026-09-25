@extends('layouts.kasir')
@section('title', 'Dashboard Kasir')
@section('content')
    <div class="page-head">
        <div>
            <p class="eyebrow">Dashboard Kasir</p>
            <h1>Selamat datang, {{ auth()->user()->nama }}</h1>
            <p class="muted">Role {{ auth()->user()->tipe_akun }} · {{ now()->format('d/m/Y') }}</p>
        </div>
    </div>

    <h2>Menu Kasir</h2>

    <div class="menu-grid">
        <a class="menu-card primary" href="{{ route('kasir.pos') }}">
            <svg class="icon" viewBox="0 0 24 24" aria-hidden="true">
                <path d="M6 7h12l-1 13H7L6 7Z"/>
                <path d="M9 7a3 3 0 0 1 6 0"/>
            </svg>
            <strong>POS / Transaksi Baru</strong>
            <span>Catat penjualan di toko untuk member atau guest.</span>
        </a>

        <a class="menu-card" href="{{ route('kasir.pos') }}#member">
            <svg class="icon" viewBox="0 0 24 24" aria-hidden="true">
                <circle cx="11" cy="11" r="6"/>
                <path d="m20 20-4.2-4.2"/>
            </svg>
            <strong>Cari Member</strong>
            <span>Pilih member langsung di halaman POS.</span>
        </a>

        <div class="menu-card disabled" aria-disabled="true">
            <svg class="icon" viewBox="0 0 24 24" aria-hidden="true">
                <circle cx="12" cy="8" r="4"/>
                <path d="M4.5 21a7.5 7.5 0 0 1 15 0"/>
            </svg>
            <strong>Daftar Customer</strong>
            <span>Belum tersedia.</span>
        </div>

        <a class="menu-card" href="{{ route('kasir.riwayat') }}">
            <svg class="icon" viewBox="0 0 24 24" aria-hidden="true">
                <path d="M6 3h12a2 2 0 0 1 2 2v16l-3-2-3 2-3-2-3 2-2-1.3V5a2 2 0 0 1 2-2Z"/>
                <path d="M9 8h6"/>
                <path d="M9 12h6"/>
            </svg>
            <strong>Histori Transaksi</strong>
            <span>Lihat dan cetak ulang struk transaksi Anda.</span>
        </a>
    </div>
@endsection

@push('styles')
    <style>
        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 14px;
        }

        .menu-card {
            min-height: 170px;
            padding: 22px;
            display: flex;
            flex-direction: column;
            gap: 8px;
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 8px;
            text-decoration: none;
            transition: transform .2s, box-shadow .2s;
        }

        a.menu-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 26px rgba(40, 37, 32, .1);
        }

        .menu-card .icon {
            width: 26px;
            height: 26px;
            margin-bottom: auto;
        }

        .menu-card strong {
            font-size: 15px;
        }

        .menu-card span {
            color: var(--gray);
            font-size: 12px;
            line-height: 1.5;
        }

        .menu-card.primary {
            color: #fff;
            background: var(--black);
            border-color: var(--black);
        }

        .menu-card.primary span {
            color: #cfc9c0;
        }

        .menu-card.disabled {
            color: #9a958e;
            background: var(--field);
            border-style: dashed;
        }
    </style>
@endpush
