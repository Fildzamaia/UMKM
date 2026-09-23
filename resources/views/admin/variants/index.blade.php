@extends('layouts.admin')
@section('title','Varian Produk')
@section('content')
    <div class="page-head">
        <div>
            <h1>Varian — {{ $product->nama_produk }}</h1>
            <div class="muted">
                Varian menyimpan ukuran, warna, stok, dan batas stok minimum. Stok hanya berubah melalui pembelian diterima atau penjualan berhasil.
            </div>
        </div>
        <a class="btn light" href="{{ route('admin.products.index') }}">Kembali ke Produk</a>
    </div>

    <div style="display:grid;grid-template-columns:1fr 2fr;gap:18px">
        <form class="card" method="POST" action="{{ route('admin.variants.store',$product->id_produk) }}">
            @csrf
            <h3>Tambah Varian</h3>
            <label>Ukuran</label>
            <input name="ukuran" placeholder="S/M/L/XL" required>
            <br><br>
            <label>Warna</label>
            <input name="warna" required>
            <br><br>
            <label>Stok Minimum</label>
            <input type="number" name="stok_minimum" value="5" min="0" required>
            <br><br>
            <button class="btn">Tambah</button>
            <p class="muted" style="margin-top:12px">
                Varian baru dibuat dengan stok 0 sehingga akan masuk daftar stok habis sampai direstock.
            </p>
        </form>

        <div class="table-wrap">
            <table>
                <tr>
                    <th>ID</th>
                    <th>Ukuran</th>
                    <th>Warna</th>
                    <th>Stok</th>
                    <th>Minimum</th>
                    <th>Aksi</th>
                </tr>
                @foreach($variants as $v)
                    <tr>
                        <td>{{ $v->id_varian }}</td>
                        <td>
                            <span id="var-size-text-{{ $v->id_varian }}">{{ $v->ukuran }}</span>
                            <input
                                id="var-size-input-{{ $v->id_varian }}"
                                name="ukuran"
                                value="{{ $v->ukuran }}"
                                form="var-update-{{ $v->id_varian }}"
                                style="display:none;width:80px"
                                required
                            >
                        </td>
                        <td>
                            <span id="var-color-text-{{ $v->id_varian }}">{{ $v->warna }}</span>
                            <input
                                id="var-color-input-{{ $v->id_varian }}"
                                name="warna"
                                value="{{ $v->warna }}"
                                form="var-update-{{ $v->id_varian }}"
                                style="display:none"
                                required
                            >
                        </td>
                        <td>
                            @if($v->stok == 0)
                                <span class="badge bad">HABIS · 0</span>
                            @elseif($v->stok <= $v->stok_minimum)
                                <span class="badge bad">LOW · {{ $v->stok }}</span>
                            @else
                                <span class="badge ok">{{ $v->stok }}</span>
                            @endif
                        </td>
                        <td>
                            <span id="var-min-text-{{ $v->id_varian }}">{{ $v->stok_minimum }}</span>
                            <input
                                id="var-min-input-{{ $v->id_varian }}"
                                type="number"
                                min="0"
                                name="stok_minimum"
                                value="{{ $v->stok_minimum }}"
                                form="var-update-{{ $v->id_varian }}"
                                style="display:none;width:90px"
                                required
                            >
                        </td>
                        <td>
                            <div id="var-normal-{{ $v->id_varian }}">
                                <button
                                    type="button"
                                    class="btn sm light"
                                    onclick="toggleVariant('{{ $v->id_varian }}', true)"
                                >
                                    Edit
                                </button>
                            </div>

                            <div
                                id="var-edit-{{ $v->id_varian }}"
                                style="display:none;gap:7px;align-items:center;flex-wrap:wrap"
                            >
                                <form
                                    id="var-update-{{ $v->id_varian }}"
                                    method="POST"
                                    action="{{ route('admin.variants.update',$v->id_varian) }}"
                                >
                                    @csrf
                                    @method('PUT')
                                </form>

                                <button
                                    class="btn sm"
                                    type="submit"
                                    form="var-update-{{ $v->id_varian }}"
                                >
                                    Simpan
                                </button>

                                <button
                                    class="btn sm light"
                                    type="button"
                                    onclick="toggleVariant('{{ $v->id_varian }}', false)"
                                >
                                    Batal
                                </button>

                                <form
                                    method="POST"
                                    action="{{ route('admin.variants.destroy',$v->id_varian) }}"
                                    onsubmit="return confirm('Hapus varian ini? Varian yang sudah pernah dibeli atau dijual akan ditolak oleh sistem.')"
                                >
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn sm red" type="submit">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>

    <script>
        function toggleVariant(id, editing) {
            const pairs = [
                ['var-size-text-', 'var-size-input-'],
                ['var-color-text-', 'var-color-input-'],
                ['var-min-text-', 'var-min-input-']
            ];

            pairs.forEach(([textPrefix, inputPrefix]) => {
                const text = document.getElementById(textPrefix + id);
                const input = document.getElementById(inputPrefix + id);
                text.style.display = editing ? 'none' : '';
                input.style.display = editing ? 'block' : 'none';
                if (!editing) input.value = text.textContent.trim();
            });

            document.getElementById('var-normal-' + id).style.display = editing ? 'none' : 'block';
            document.getElementById('var-edit-' + id).style.display = editing ? 'flex' : 'none';

            if (editing) {
                document.getElementById('var-size-input-' + id).focus();
            }
        }
    </script>
@endsection
