@extends('layouts.admin')
@section('title','Kategori')
@section('content')
    <div class="page-head">
        <div>
            <h1>Kategori Produk</h1>
            <div class="muted">
                Kelola kategori produk.
            </div>
        </div>
    </div>

    <div style="display:grid;grid-template-columns:1fr 2fr;gap:18px">
        <form class="card" method="POST" action="{{ route('admin.categories.store') }}">
            @csrf
            <h3>Tambah Kategori</h3>
            <label>Nama Kategori</label>
            <input name="nama_kategori" value="{{ old('nama_kategori') }}" required>
            <br><br>
            <button class="btn">Tambah</button>
        </form>

        <div class="table-wrap">
            <table>
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Aksi</th>
                </tr>
                @foreach($categories as $c)
                    <tr>
                        <td>{{ $c->id_kategori }}</td>
                        <td>
                            <span id="cat-text-{{ $c->id_kategori }}">
                                {{ $c->nama_kategori }}
                            </span>
                            <input
                                id="cat-input-{{ $c->id_kategori }}"
                                name="nama_kategori"
                                value="{{ $c->nama_kategori }}"
                                form="cat-update-{{ $c->id_kategori }}"
                                style="display:none"
                                required
                            >
                        </td>
                        <td>
                            <div id="cat-normal-{{ $c->id_kategori }}">
                                <button
                                    type="button"
                                    class="btn sm light"
                                    onclick="toggleCategory('{{ $c->id_kategori }}', true)"
                                >
                                    Edit
                                </button>
                            </div>

                            <div
                                id="cat-edit-{{ $c->id_kategori }}"
                                style="display:none;gap:7px;align-items:center;flex-wrap:wrap"
                            >
                                <form
                                    id="cat-update-{{ $c->id_kategori }}"
                                    method="POST"
                                    action="{{ route('admin.categories.update', $c->id_kategori) }}"
                                >
                                    @csrf
                                    @method('PUT')
                                </form>

                                <button
                                    type="submit"
                                    form="cat-update-{{ $c->id_kategori }}"
                                    class="btn sm"
                                >
                                    Simpan
                                </button>

                                <button
                                    type="button"
                                    class="btn sm light"
                                    onclick="toggleCategory('{{ $c->id_kategori }}', false)"
                                >
                                    Batal
                                </button>

                                <form
                                    method="POST"
                                    action="{{ route('admin.categories.destroy', $c->id_kategori) }}"
                                    onsubmit="return confirm('Hapus kategori ini? Kategori yang masih dipakai produk akan ditolak oleh sistem.')"
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
        function toggleCategory(id, editing) {
            const text = document.getElementById('cat-text-' + id);
            const input = document.getElementById('cat-input-' + id);
            const normal = document.getElementById('cat-normal-' + id);
            const edit = document.getElementById('cat-edit-' + id);

            text.style.display = editing ? 'none' : '';
            input.style.display = editing ? 'block' : 'none';
            normal.style.display = editing ? 'none' : 'block';
            edit.style.display = editing ? 'flex' : 'none';

            if (!editing) {
                input.value = text.textContent.trim();
            } else {
                input.focus();
                input.select();
            }
        }
    </script>
@endsection
