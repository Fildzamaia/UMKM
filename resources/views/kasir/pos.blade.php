@extends('layouts.kasir')
@section('title', 'POS Kasir')
@section('content')
    <div class="page-head">
        <div>
            <h1>POS / Transaksi Baru</h1>
            <p class="muted">Pilih varian untuk menambahkannya ke keranjang.</p>
        </div>
    </div>

    <div class="pos-grid">
        <section>
            <div class="card pos-filter">
                <input
                    type="search"
                    id="pos-search"
                    placeholder="Cari nama produk..."
                    aria-label="Cari nama produk"
                >

                <select id="pos-kategori" aria-label="Filter kategori">
                    <option value="">Semua Kategori</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id_kategori }}">
                            {{ $category->nama_kategori }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="pos-products">
                @forelse ($products as $product)
                    <article
                        class="card pos-product"
                        data-name="{{ mb_strtolower($product->nama_produk) }}"
                        data-kategori="{{ $product->id_kategori }}"
                    >
                        <div class="pos-product-head">
                            <div>
                                <strong>{{ $product->nama_produk }}</strong>
                                <p class="muted">{{ $product->nama_kategori }}</p>
                            </div>

                            <div class="num">
                                @if ($product->promo)
                                    <span class="badge promo">
                                        {{ $product->promo->nama_promo }}
                                        −{{ (float) $product->promo->persen_diskon }}%
                                    </span>
                                    <div class="muted">
                                        <s>Rp{{ number_format($product->harga_jual, 0, ',', '.') }}</s>
                                    </div>
                                @endif

                                <strong>Rp{{ number_format($product->harga_final, 0, ',', '.') }}</strong>
                            </div>
                        </div>

                        <div class="pos-variants">
                            @forelse ($variants->get($product->id_produk, collect()) as $variant)
                                <button
                                    type="button"
                                    class="variant-btn"
                                    data-id="{{ $variant->id_varian }}"
                                    data-label="{{ $product->nama_produk }} ({{ $variant->ukuran }}/{{ $variant->warna }})"
                                    data-harga="{{ $product->harga_final }}"
                                    data-stok="{{ $variant->stok }}"
                                    @disabled($variant->stok <= 0)
                                >
                                    <span>{{ $variant->ukuran }} · {{ $variant->warna }}</span>
                                    <small>
                                        {{ $variant->stok > 0 ? 'Stok '.$variant->stok : 'Habis' }}
                                    </small>
                                </button>
                            @empty
                                <p class="muted">Belum ada varian.</p>
                            @endforelse
                        </div>
                    </article>
                @empty
                    <div class="card muted">Belum ada produk aktif.</div>
                @endforelse

                <div class="card muted" id="pos-no-result" hidden>
                    Tidak ada produk yang cocok.
                </div>
            </div>
        </section>

        <aside>
            <form
                method="POST"
                action="{{ route('kasir.store') }}"
                class="card pos-cart"
                id="pos-form"
            >
                @csrf

                <h2>Keranjang</h2>

                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th class="num">Qty</th>
                            <th class="num">Subtotal</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="cart-body"></tbody>
                </table>

                <p class="muted" id="cart-empty">Keranjang masih kosong.</p>

                <fieldset id="member">
                    <legend>Pembeli</legend>

                    <label class="inline">
                        <input
                            type="radio"
                            name="tipe_pembeli"
                            value="guest"
                            @checked(old('tipe_pembeli', 'guest') === 'guest')
                        >
                        Guest
                    </label>

                    <label class="inline">
                        <input
                            type="radio"
                            name="tipe_pembeli"
                            value="member"
                            @checked(old('tipe_pembeli') === 'member')
                        >
                        Member
                    </label>

                    <div id="member-fields" class="stack">
                        <input
                            type="search"
                            id="member-search"
                            placeholder="Cari nama, ID, atau no. telp"
                            aria-label="Cari member"
                        >

                        <select
                            name="id_akun_customer"
                            id="member-select"
                            aria-label="Member"
                        >
                            <option value="">— Pilih member —</option>
                            @foreach ($customers as $customer)
                                <option
                                    value="{{ $customer->id_akun }}"
                                    @selected(old('id_akun_customer') === $customer->id_akun)
                                >
                                    {{ $customer->nama }} · {{ $customer->id_akun }} · {{ $customer->no_telp }}
                                </option>
                            @endforeach
                        </select>

                        <p class="muted">Member mendapat 1 poin per Rp10.000 belanja.</p>
                    </div>
                </fieldset>

                <fieldset>
                    <legend>Pembayaran</legend>

                    <label class="inline">
                        <input
                            type="radio"
                            name="metode_pembayaran"
                            value="TUNAI"
                            @checked(old('metode_pembayaran', 'TUNAI') === 'TUNAI')
                        >
                        Tunai
                    </label>

                    <label class="inline">
                        <input
                            type="radio"
                            name="metode_pembayaran"
                            value="QR"
                            @checked(old('metode_pembayaran') === 'QR')
                        >
                        QR
                    </label>

                    <div id="cash-fields" class="stack">
                        <label for="uang-diterima">Uang diterima</label>
                        <input
                            type="number"
                            id="uang-diterima"
                            min="0"
                            step="500"
                            inputmode="numeric"
                        >
                        <p class="muted">
                            Kembalian: <strong id="kembalian">Rp0</strong>
                        </p>
                    </div>

                    <div id="qr-fields" class="stack">
                        <label for="referensi">Referensi pembayaran (opsional)</label>
                        <input
                            type="text"
                            id="referensi"
                            name="referensi_pembayaran"
                            maxlength="255"
                            value="{{ old('referensi_pembayaran') }}"
                        >
                    </div>
                </fieldset>

                <div class="pos-total">
                    <span>Total</span>
                    <strong id="cart-total">Rp0</strong>
                </div>

                <p class="muted" id="poin-preview"></p>

                <button type="submit" class="btn pos-submit" id="pos-submit" disabled>
                    Simpan Transaksi
                </button>

                <p class="muted pos-note">
                    Harga dan stok dicek ulang di server saat transaksi disimpan.
                </p>
            </form>
        </aside>
    </div>
@endsection

@push('styles')
    <style>
        .pos-grid {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 390px;
            gap: 20px;
            align-items: start;
        }

        .pos-filter {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 200px;
            gap: 10px;
            margin-bottom: 12px;
            padding: 12px;
        }

        .pos-products {
            display: grid;
            gap: 12px;
        }

        .pos-product-head {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 12px;
        }

        .pos-variants {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }

        .variant-btn {
            display: grid;
            gap: 2px;
            padding: 7px 11px;
            border: 1px solid #d9d3cb;
            border-radius: 8px;
            background: #fff;
            font: inherit;
            text-align: left;
            cursor: pointer;
        }

        .variant-btn:hover:not(:disabled) {
            border-color: #151515;
        }

        .variant-btn small {
            color: #77716a;
        }

        .variant-btn:disabled {
            background: #f3f1ee;
            color: #a8a29a;
            text-decoration: line-through;
            cursor: not-allowed;
        }

        .pos-cart {
            position: sticky;
            top: 90px;
        }

        .cart-table th,
        .cart-table td {
            padding: 8px 4px;
        }

        .cart-table input {
            width: 64px;
            padding: 5px 6px;
            text-align: right;
        }

        .cart-remove {
            padding: 2px 8px;
            border: 0;
            background: none;
            color: #a3261b;
            font-size: 18px;
            cursor: pointer;
        }

        #cart-empty {
            margin: 10px 0;
        }

        .pos-cart fieldset {
            margin: 16px 0 0;
            padding: 0;
            border: 0;
        }

        .pos-cart legend {
            margin-bottom: 8px;
            font-weight: 700;
        }

        label.inline {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-right: 14px;
            font-weight: 500;
        }

        label.inline input {
            width: auto;
        }

        .stack {
            display: grid;
            gap: 8px;
            margin-top: 10px;
        }

        .pos-total {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            margin-top: 18px;
            padding-top: 14px;
            border-top: 1px solid #e9e5df;
            font-size: 18px;
        }

        #poin-preview {
            min-height: 1.4em;
            margin: 4px 0 12px;
        }

        .pos-submit {
            width: 100%;
        }

        .pos-note {
            margin-top: 8px;
            font-size: 12px;
        }

        @media (max-width: 1100px) {
            .pos-grid {
                grid-template-columns: minmax(0, 1fr);
            }

            .pos-cart {
                position: static;
            }
        }

        @media (max-width: 560px) {
            .pos-filter {
                grid-template-columns: minmax(0, 1fr);
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        (() => {
            const RUPIAH_PER_POIN = 10000;
            const oldItems = @json(array_values(old('items', [])));

            const form = document.getElementById('pos-form');
            const cartBody = document.getElementById('cart-body');
            const cartEmpty = document.getElementById('cart-empty');
            const cartTotal = document.getElementById('cart-total');
            const submitBtn = document.getElementById('pos-submit');
            const poinPreview = document.getElementById('poin-preview');
            const cashInput = document.getElementById('uang-diterima');
            const kembalian = document.getElementById('kembalian');

            // id_varian -> { label, harga, stok, qty }
            const cart = new Map();

            const rupiah = (value) =>
                'Rp' + Math.round(value).toLocaleString('id-ID');

            const selected = (name) =>
                form.querySelector(`input[name="${name}"]:checked`)?.value;

            function total() {
                let sum = 0;
                cart.forEach((item) => { sum += item.harga * item.qty; });
                return sum;
            }

            function addItem(button, qty = 1) {
                const id = button.dataset.id;
                const stok = Number(button.dataset.stok);
                const item = cart.get(id) ?? {
                    label: button.dataset.label,
                    harga: Number(button.dataset.harga),
                    stok,
                    qty: 0,
                };

                item.qty = Math.min(item.qty + qty, stok);
                cart.set(id, item);
                renderCart();
            }

            function renderCart() {
                cartBody.replaceChildren();

                let index = 0;

                cart.forEach((item, id) => {
                    const row = document.createElement('tr');

                    const labelCell = document.createElement('td');
                    labelCell.textContent = item.label;
                    const unit = document.createElement('div');
                    unit.className = 'muted';
                    unit.textContent = rupiah(item.harga) + ' · stok ' + item.stok;
                    labelCell.append(unit);

                    const hidden = document.createElement('input');
                    hidden.type = 'hidden';
                    hidden.name = `items[${index}][id_varian]`;
                    hidden.value = id;
                    labelCell.append(hidden);

                    const qtyCell = document.createElement('td');
                    qtyCell.className = 'num';
                    const qtyInput = document.createElement('input');
                    qtyInput.type = 'number';
                    qtyInput.name = `items[${index}][jumlah]`;
                    qtyInput.min = 1;
                    qtyInput.max = item.stok;
                    qtyInput.value = item.qty;
                    qtyInput.setAttribute('aria-label', 'Jumlah ' + item.label);
                    qtyCell.append(qtyInput);

                    const subtotalCell = document.createElement('td');
                    subtotalCell.className = 'num';
                    subtotalCell.textContent = rupiah(item.harga * item.qty);

                    qtyInput.addEventListener('input', () => {
                        const value = Math.max(
                            1,
                            Math.min(Number(qtyInput.value) || 1, item.stok)
                        );
                        item.qty = value;
                        subtotalCell.textContent = rupiah(item.harga * value);
                        renderSummary();
                    });

                    qtyInput.addEventListener('change', () => {
                        qtyInput.value = item.qty;
                    });

                    const removeCell = document.createElement('td');
                    const removeBtn = document.createElement('button');
                    removeBtn.type = 'button';
                    removeBtn.className = 'cart-remove';
                    removeBtn.textContent = '×';
                    removeBtn.setAttribute('aria-label', 'Hapus ' + item.label);
                    removeBtn.addEventListener('click', () => {
                        cart.delete(id);
                        renderCart();
                    });
                    removeCell.append(removeBtn);

                    row.append(labelCell, qtyCell, subtotalCell, removeCell);
                    cartBody.append(row);
                    index++;
                });

                cartEmpty.hidden = cart.size > 0;
                renderSummary();
            }

            function renderSummary() {
                const sum = total();

                cartTotal.textContent = rupiah(sum);
                submitBtn.disabled = cart.size === 0;

                poinPreview.textContent =
                    selected('tipe_pembeli') === 'member' && sum > 0
                        ? 'Poin didapat: ' + Math.floor(sum / RUPIAH_PER_POIN)
                        : '';

                const paid = Number(cashInput.value);
                kembalian.textContent = cashInput.value === ''
                    ? rupiah(0)
                    : paid < sum
                        ? 'Uang kurang ' + rupiah(sum - paid)
                        : rupiah(paid - sum);
            }

            function syncBuyer() {
                const isMember = selected('tipe_pembeli') === 'member';

                document.getElementById('member-fields').hidden = !isMember;
                document.getElementById('member-select').disabled = !isMember;
                renderSummary();
            }

            function syncPayment() {
                const isCash = selected('metode_pembayaran') !== 'QR';

                document.getElementById('cash-fields').hidden = !isCash;
                document.getElementById('qr-fields').hidden = isCash;
                document.getElementById('referensi').disabled = isCash;
            }

            function filterProducts() {
                const query = document.getElementById('pos-search')
                    .value.trim().toLowerCase();
                const category = document.getElementById('pos-kategori').value;
                let visible = 0;

                document.querySelectorAll('.pos-product').forEach((card) => {
                    const match = card.dataset.name.includes(query)
                        && (category === '' || card.dataset.kategori === category);

                    card.hidden = !match;
                    visible += match ? 1 : 0;
                });

                document.getElementById('pos-no-result').hidden =
                    visible > 0 || document.querySelectorAll('.pos-product').length === 0;
            }

            document.querySelectorAll('.variant-btn').forEach((button) => {
                button.addEventListener('click', () => addItem(button));
            });

            document.getElementById('pos-search')
                .addEventListener('input', filterProducts);
            document.getElementById('pos-kategori')
                .addEventListener('change', filterProducts);

            document.getElementById('member-search')
                .addEventListener('input', (event) => {
                    const query = event.target.value.trim().toLowerCase();

                    document.querySelectorAll('#member-select option')
                        .forEach((option) => {
                            option.hidden = option.value !== ''
                                && !option.textContent.toLowerCase().includes(query);
                        });
                });

            form.querySelectorAll('input[name="tipe_pembeli"]')
                .forEach((radio) => radio.addEventListener('change', syncBuyer));
            form.querySelectorAll('input[name="metode_pembayaran"]')
                .forEach((radio) => radio.addEventListener('change', syncPayment));
            cashInput.addEventListener('input', renderSummary);

            // Cegah transaksi tersimpan dua kali karena klik ganda.
            form.addEventListener('submit', () => {
                submitBtn.disabled = true;
                submitBtn.textContent = 'Menyimpan...';
            });

            // Pulihkan keranjang setelah validasi gagal.
            oldItems.forEach((item) => {
                const button = document.querySelector(
                    `.variant-btn[data-id="${CSS.escape(String(item.id_varian ?? ''))}"]`
                );

                if (button && !button.disabled) {
                    addItem(button, Math.max(1, Number(item.jumlah) || 1));
                }
            });

            syncBuyer();
            syncPayment();
            renderCart();
        })();
    </script>
@endpush
