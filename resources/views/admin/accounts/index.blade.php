@extends('layouts.admin')
@section('title','Kelola Akun')
@section('content')
    <div class="page-head">
        <div>
            <h1>Kelola Akun</h1>
            <div class="muted">Kelola akun staff, customer, dan admin.</div>
        </div>
        <a class="btn" href="{{ route('admin.accounts.create') }}">+ Tambah Staff</a>
    </div>
    <form method="GET" class="card" style="display:flex;gap:10px;margin-bottom:15px">
        <input name="q" value="{{ request('q') }}" placeholder="Cari ID, nama, username...">
        <select name="role">
            <option value="">Semua Role</option>
            @foreach(['CUSTOMER','KASIR','ADMIN'] as $r)
                <option value="{{ $r }}" @selected(request('role')===$r)>{{ $r }}</option>
            @endforeach
        </select>
        <button class="btn">Filter</button>
    </form>
    <div class="table-wrap">
        <table>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Username</th>
                <th>Role</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
            @foreach($accounts as $a)
                <tr>
                    <td>{{ $a->id_akun }}</td>
                    <td>{{ $a->nama }}</td>
                    <td>{{ $a->username }}</td>
                    <td>{{ $a->tipe_akun }}</td>
                    <td>
                        <span class="badge {{ $a->status_akun==='AKTIF'?'ok':'bad' }}">{{ $a->status_akun }}</span>
                    </td>
                    <td>
                        <a class="btn sm light" href="{{ route('admin.accounts.edit',$a->id_akun) }}">Edit</a>
                        @if($a->id_akun!==auth()->id())
                            <form
                                style="display: inline"
                                method="POST"
                                action="{{ route('admin.accounts.status', $a->id_akun) }}"
                            >
                                @csrf
                                @method('PATCH')
                                <button
                                    class="btn sm {{ $a->status_akun === 'AKTIF' ? 'red' : 'green' }}"
                                >
                                    {{ $a->status_akun === 'AKTIF' ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
@endsection
