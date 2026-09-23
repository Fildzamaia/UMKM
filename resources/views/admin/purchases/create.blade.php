@extends('layouts.admin')

@section('title', 'Pembelian Baru')

@section('content')
    <div class="page-head">
        <div>
            <h1>Pembelian Produk Baru</h1>
            <p class="muted">
                Buat transaksi restock. Stok belum berubah sampai pembelian diterima.
            </p>
        </div>
    </div>

    <form class="card" method="POST" action="{{ route('admin.purchases.store') }}">
        @csrf

        <label for="id_supplier">Supplier</label>
        <select id="id_supplier" name="id_supplier" required>
            @foreach ($suppliers as $supplier)
                <option value="{{ $supplier->id_supplier }}">
                    {{ $supplier->nama_supplier }}
                </option>
            @endforeach
        </select>

        <br><br>
        <h3>Item Pembelian</h3>

        <div id="items">
            <div class="form-grid item">
                <div>
                    <label>Varian</label>
                    <select name="id_varian[]" required>
                        @foreach ($variants as $variant)
                            <option value="{{ $variant->id_varian }}">
                                {{ $variant->nama_produk }} —
                                {{ $variant->warna }}/{{ $variant->ukuran }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label>Jumlah</label>
                    <input type="number" name="jumlah[]" min="1" required>
                </div>

                <div>
                    <label>Harga Beli / Unit</label>
                    <input type="number" name="harga_satuan[]" min="0" required>
                </div>
            </div>
        </div>

        <br>

        <button type="button" class="btn alt" onclick="addItem()">
            + Item
        </button>
        <button type="submit" class="btn">
            Simpan sebagai DIPESAN
        </button>
    </form>

    <script>
        function addItem() {
            const firstItem = document.querySelector('.item');
            const clone = firstItem.cloneNode(true);

            clone.querySelectorAll('input').forEach((input) => {
                input.value = '';
            });

            document.getElementById('items').appendChild(clone);
        }
    </script>
@endsection
