@extends('layouts.admin')
@section('title','Catat Pengeluaran')
@section('content')
    <div class="page-head">
        <h1>Catat Pengeluaran</h1>
    </div>
    <form class="card" method="POST" action="{{ route('admin.expenses.store') }}">
        @csrf
        <div class="form-grid">
            <div>
                <label>Tanggal</label>
                <input
                    type="datetime-local"
                    name="tanggal_pengeluaran"
                    value="{{ now()->format('Y-m-d\TH:i') }}"
                    required
                >
            </div>
            <div>
                <label>Jenis</label>
                <select name="jenis_pengeluaran">
                    @foreach(['LISTRIK','INTERNET','SEWA','ONGKIR','PERAWATAN','LAINNYA'] as $j)
                        <option>{{ $j }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label>Nominal</label>
                <input type="number" name="nominal" min="1" required>
            </div>
            <div class="full">
                <label>Keterangan</label>
                <textarea name="keterangan">
                </textarea>
            </div>
        </div>
        <br>
        <button class="btn">Simpan</button>
    </form>
@endsection
