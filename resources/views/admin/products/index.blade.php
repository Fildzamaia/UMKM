@extends('layouts.admin')

@section('title', 'Produk')

@section('content')
    <div class="page-head">
        <div>
            <h1>Produk</h1>
            <div class="muted">
                Kelola koleksi produk yang tersedia di FORMA.
            </div>
        </div>

        <a class="btn" href="{{ route('admin.products.create') }}">
            + Tambah Produk
        </a>
    </div>

    <form method="GET" class="card" style="margin-bottom:15px">
        <input
            name="q"
            value="{{ request('q') }}"
            placeholder="Cari nama produk..."
        >
    </form>

    <div class="table-wrap">
        <table>
            <tr>
                <th>ID</th>
                <th>Produk</th>
                <th>Kategori</th>
                <th>Harga</th>
                <th>Varian</th>
                <th>Total Stok</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>

            @foreach($products as $p)
                <tr>
                    <td>{{ $p->id_produk }}</td>
                    <td>{{ $p->nama_produk }}</td>
                    <td>{{ $p->nama_kategori }}</td>
                    <td>Rp{{ number_format($p->harga_jual, 0, ',', '.') }}</td>
                    <td>{{ $p->jumlah_varian }}</td>
                    <td>{{ $p->total_stok }}</td>
                    <td>
                        <span class="badge {{ $p->status_produk === 'AKTIF' ? 'ok' : 'bad' }}">
                            {{ $p->status_produk }}
                        </span>
                    </td>
                    <td>
                        <div style="display:flex; gap:6px; align-items:center; flex-wrap:wrap;">
                            <a
                                class="btn sm light"
                                href="{{ route('admin.variants.index', $p->id_produk) }}"
                            >
                                Varian
                            </a>

                            <a
                                class="btn sm"
                                href="{{ route('admin.products.edit', $p->id_produk) }}"
                            >
                                Edit
                            </a>

                            @if((int) $p->jumlah_varian === 0)
                                <form
                                    method="POST"
                                    action="{{ route('admin.products.destroy', $p->id_produk) }}"
                                    style="display:inline; margin:0;"
                                    onsubmit="return confirm('Hapus produk {{ $p->nama_produk }} secara permanen?')"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn sm"
                                        style="background:#b91c1c; color:#fff;"
                                    >
                                        Hapus
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
@endsection
