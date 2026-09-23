@extends('layouts.admin')
@section('title','Edit Pengeluaran')
@section('content')
    <div class="page-head">
        <h1>Edit Pengeluaran</h1>
        <a class="btn light" href="{{ route('admin.expenses.index') }}">Kembali</a>
    </div>
    <form class="card" method="POST" action="{{ route('admin.expenses.update',$expense->id_pengeluaran) }}">
        @csrf
        @method('PUT')
        <div class="form-grid">
            <div>
                <label>Tanggal</label>
                <input
                    type="datetime-local"
                    name="tanggal_pengeluaran"
                    value="{{ \Carbon\Carbon::parse($expense->tanggal_pengeluaran)->format('Y-m-d\TH:i') }}"
                >
            </div>
            <div>
                <label>Jenis</label>
                <select name="jenis_pengeluaran">
                    @foreach(['LISTRIK','INTERNET','SEWA','ONGKIR','PERAWATAN','LAINNYA'] as $j)
                        <option @selected($expense->jenis_pengeluaran==$j)>{{ $j }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label>Nominal</label>
                <input type="number" name="nominal" value="{{ $expense->nominal }}">
            </div>
            <div class="full">
                <label>Keterangan</label>
                <textarea name="keterangan">{{ $expense->keterangan }}</textarea>
            </div>
        </div>
        <button class="btn" style="margin-top:16px">Simpan</button>
    </form>
@endsection
