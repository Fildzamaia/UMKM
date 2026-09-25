@extends('layouts.kasir')
@section('title', 'Struk '.$sale->id_penjualan)
@section('content')
    <div class="page-head no-print">
        <div>
            <h1>Struk Transaksi</h1>
            <p class="muted">{{ $sale->id_penjualan }}</p>
        </div>

        <div class="struk-actions">
            <button type="button" class="btn light" onclick="window.print()">
                Cetak
            </button>
            <a class="btn light" href="{{ route('kasir.riwayat') }}">Riwayat</a>
            <a class="btn" href="{{ route('kasir.pos') }}">Transaksi Baru</a>
        </div>
    </div>

    <div class="card struk">
        <div class="struk-head">
            <strong>FORMA</strong>
            <div>{{ $sale->id_penjualan }}</div>
            <div class="muted">{{ $sale->tanggal_penjualan->format('d/m/Y H:i') }}</div>
        </div>

        <dl class="struk-meta">
            <dt>Kasir</dt>
            <dd>{{ $sale->akun->nama }} ({{ $sale->id_akun }})</dd>

            <dt>Pembeli</dt>
            <dd>
                @if ($sale->penjualanMember)
                    Member ·
                    {{ $sale->penjualanMember->customer?->nama }}
                    ({{ $sale->penjualanMember->id_akun_customer }})
                @else
                    Guest
                @endif
            </dd>

            <dt>Pembayaran</dt>
            <dd>{{ $sale->metode_pembayaran }} · {{ $sale->status_pembayaran }}</dd>

            @if ($sale->referensi_pembayaran)
                <dt>Referensi</dt>
                <dd>{{ $sale->referensi_pembayaran }}</dd>
            @endif
        </dl>

        <table>
            <thead>
                <tr>
                    <th>Item</th>
                    <th class="num">Qty</th>
                    <th class="num">Harga</th>
                    <th class="num">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($sale->detailPenjualan as $detail)
                    <tr>
                        <td>
                            {{ $detail->varian->produk->nama_produk }}
                            <div class="muted">
                                {{ $detail->varian->ukuran }}/{{ $detail->varian->warna }}
                            </div>
                        </td>
                        <td class="num">{{ $detail->jumlah }}</td>
                        <td class="num">Rp{{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                        <td class="num">
                            Rp{{ number_format($detail->harga_satuan * $detail->jumlah, 0, ',', '.') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="struk-total">
            <span>Total</span>
            <strong>Rp{{ number_format($sale->nominal_bayar, 0, ',', '.') }}</strong>
        </div>

        @if ($sale->penjualanMember)
            <p class="muted">Poin didapat: {{ $sale->poin_didapat }}</p>
        @endif

        <p class="muted struk-foot">Status {{ $sale->status_penjualan }} · Terima kasih.</p>
    </div>
@endsection

@push('styles')
    <style>
        .struk-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .struk {
            max-width: 560px;
        }

        .struk-head {
            margin-bottom: 14px;
            text-align: center;
        }

        .struk-head strong {
            font-size: 18px;
            letter-spacing: .08em;
        }

        .struk-meta {
            display: grid;
            grid-template-columns: max-content 1fr;
            gap: 4px 14px;
            margin: 0 0 14px;
        }

        .struk-meta dt {
            color: #77716a;
        }

        .struk-meta dd {
            margin: 0;
        }

        .struk-total {
            display: flex;
            justify-content: space-between;
            margin-top: 14px;
            font-size: 18px;
        }

        .struk-foot {
            margin-top: 14px;
            text-align: center;
        }

        @media print {
            .struk {
                max-width: none;
                border: 0;
            }
        }
    </style>
@endpush
