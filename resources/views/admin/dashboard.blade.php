@extends('layouts.admin')
@section('title','Dashboard Admin')
@section('content')
    <div class="page-head">
        <div>
            <h1>Dashboard</h1>
            <p class="muted">Ringkasan aktivitas dan performa bisnis.</p>
        </div>
        <span class="badge">Data real-time database</span>
    </div>

    <div class="stats">
        <div class="card stat">
            Total Penjualan
            <strong>Rp{{ number_format($totalSales,0,',','.') }}</strong>
        </div>
        <div class="card stat">
            Jumlah Transaksi
            <strong>{{ $transactions }}</strong>
        </div>
        <div class="card stat">
            Total Produk
            <strong>{{ $products }}</strong>
        </div>
        <div class="card stat">
            Total Pelanggan
            <strong>{{ $customers }}</strong>
        </div>
    </div>

    <div style="display:grid;grid-template-columns:1.4fr .6fr;gap:18px;margin-top:20px">
        <div class="card">
            <div class="page-head">
                <div>
                    <h3>Penjualan 7 Hari Terakhir</h3>
                </div>
            </div>

            @php
                $chartWidth = 760;
                $chartHeight = 240;
                $padLeft = 48;
                $padRight = 18;
                $padTop = 22;
                $padBottom = 38;
                $plotWidth = $chartWidth - $padLeft - $padRight;
                $plotHeight = $chartHeight - $padTop - $padBottom;
                $maxValue = max(1, (float) $daily->max('total'));
                $stepX = $daily->count() > 1 ? $plotWidth / ($daily->count() - 1) : 0;
                $chartPoints = $daily->values()->map(function ($item, $index) use (
                    $padLeft,
                    $padTop,
                    $plotHeight,
                    $stepX,
                    $maxValue
                ) {
                    $x = $padLeft + ($index * $stepX);
                    $y = $padTop + $plotHeight - (($item->total / $maxValue) * $plotHeight);

                    return (object) [
                        'x' => round($x, 2),
                        'y' => round($y, 2),
                        'label' => \Carbon\Carbon::parse($item->d)->format('d/m'),
                        'total' => (float) $item->total,
                    ];
                });
                $polyline = $chartPoints->map(fn($p) => $p->x.','.$p->y)->implode(' ');
            @endphp

            <div style="overflow-x:auto">
                <svg
                    viewBox="0 0 {{ $chartWidth }} {{ $chartHeight }}"
                    role="img"
                    aria-label="Grafik garis penjualan tujuh hari terakhir"
                    style="width:100%;min-width:620px;height:250px"
                >
                    @for($i = 0; $i <= 4; $i++)
                        @php
                            $gy = $padTop + (($plotHeight / 4) * $i);
                            $gridValue = $maxValue * (1 - ($i / 4));
                        @endphp
                        <line
                            x1="{{ $padLeft }}"
                            y1="{{ $gy }}"
                            x2="{{ $chartWidth - $padRight }}"
                            y2="{{ $gy }}"
                            stroke="#e8e1d8"
                            stroke-width="1"
                        />
                        <text
                            x="{{ $padLeft - 8 }}"
                            y="{{ $gy + 4 }}"
                            text-anchor="end"
                            font-size="10"
                            fill="#756b61"
                        >
                            {{ $gridValue >= 1000000
                                ? number_format($gridValue / 1000000, 1, ',', '.').'jt'
                                : number_format($gridValue / 1000, 0, ',', '.').'k' }}
                        </text>
                    @endfor

                    <polyline
                        points="{{ $polyline }}"
                        fill="none"
                        stroke="#6e5e4d"
                        stroke-width="4"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    />

                    @foreach($chartPoints as $point)
                        <circle
                            cx="{{ $point->x }}"
                            cy="{{ $point->y }}"
                            r="5"
                            fill="#ffffff"
                            stroke="#4e4236"
                            stroke-width="3"
                        >
                            <title>Rp{{ number_format($point->total,0,',','.') }}</title>
                        </circle>
                        <text
                            x="{{ $point->x }}"
                            y="{{ $chartHeight - 12 }}"
                            text-anchor="middle"
                            font-size="11"
                            fill="#514a43"
                        >
                            {{ $point->label }}
                        </text>
                    @endforeach
                </svg>
            </div>
        </div>

        <div class="card">
            <h3>Stok Hampir Habis</h3>
            @forelse($lowStock as $v)
                <div style="display:flex;justify-content:space-between;border-bottom:1px solid #eee;padding:10px 0;gap:12px">
                    <span>
                        {{ $v->nama_produk }}<br>
                        <small>{{ $v->ukuran }}/{{ $v->warna }} · min {{ $v->stok_minimum }}</small>
                    </span>
                    @if($v->stok == 0)
                        <span class="badge bad">HABIS</span>
                    @else
                        <span class="badge bad">{{ $v->stok }}</span>
                    @endif
                </div>
            @empty
                <p class="muted">Tidak ada low stock.</p>
            @endforelse
        </div>
    </div>

    <h3 style="margin-top:28px">Transaksi Terbaru</h3>
    <div class="table-wrap">
        <table>
            <tr>
                <th>ID</th>
                <th>Pelaksana</th>
                <th>Kanal</th>
                <th>Total</th>
                <th>Status</th>
            </tr>
            @foreach($recent as $s)
                <tr>
                    <td>
                        <a href="{{ route('admin.sales.show',$s->id_penjualan) }}">
                            {{ $s->id_penjualan }}
                        </a>
                    </td>
                    <td>{{ $s->nama }}</td>
                    <td>{{ $s->kanal_penjualan }}</td>
                    <td>Rp{{ number_format($s->nominal_bayar,0,',','.') }}</td>
                    <td>{{ $s->status_pembayaran }}</td>
                </tr>
            @endforeach
        </table>
    </div>
@endsection
