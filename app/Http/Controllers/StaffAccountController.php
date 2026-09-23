<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use App\Models\Akun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class StaffAccountController extends Controller
{
    public function index(Request $request)
    {
        $query = Akun::query();

        if ($request->filled('role')) {
            $query->where('tipe_akun', $request->role);
        }

        if ($request->filled('q')) {
            $search = $request->q;

            $query->where(function ($subquery) use ($search) {
                $subquery
                    ->where('nama', 'like', '%'.$search.'%')
                    ->orWhere('id_akun', 'like', '%'.$search.'%')
                    ->orWhere('username', 'like', '%'.$search.'%');
            });
        }

        $accounts = $query
            ->orderBy('tipe_akun')
            ->orderBy('nama')
            ->get();

        return view(
            'admin.accounts.index',
            compact('accounts')
        );
    }

    public function create()
    {
        return view('admin.accounts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|max:255',
            'username' => 'required|max:100|unique:akun,username',
            'email' => 'nullable|email|unique:akun,email',
            'no_telp' => 'required|max:30',
            'alamat' => 'nullable|max:1000',
            'tipe_akun' => 'required|in:ADMIN,KASIR',
            'password' => 'required|min:6|confirmed',
        ]);

        $prefix = $validated['tipe_akun'] === 'ADMIN'
            ? 'ADM'
            : 'KSR';

        $lastId = Akun::where('id_akun', 'like', $prefix.'%')
            ->orderByDesc('id_akun')
            ->value('id_akun');

        $number = $lastId
            ? ((int) substr($lastId, strlen($prefix))) + 1
            : 1;

        Akun::create([
            'id_akun' =>
                $prefix.str_pad($number, 3, '0', STR_PAD_LEFT),
            'nama' => $validated['nama'],
            'username' => $validated['username'],
            'email' => $validated['email'] ?? null,
            'no_telp' => $validated['no_telp'],
            'alamat' => $validated['alamat'] ?? null,
            'tipe_akun' => $validated['tipe_akun'],
            'status_akun' => 'AKTIF',
            'password_hash' => Hash::make($validated['password']),
        ]);

        return redirect()
            ->route('admin.accounts.index')
            ->with('success', 'Akun staff dibuat.');
    }

    public function edit(string $id)
    {
        $account = Akun::findOrFail($id);

        return view(
            'admin.accounts.edit',
            compact('account')
        );
    }

    public function update(Request $request, string $id)
    {
        $account = Akun::findOrFail($id);

        $rules = [
            'nama' => 'required|max:255',
            'username' => [
                'required',
                'max:100',
                Rule::unique('akun', 'username')
                    ->ignore($id, 'id_akun'),
            ],
            'email' => [
                'nullable',
                'email',
                Rule::unique('akun', 'email')
                    ->ignore($id, 'id_akun'),
            ],
            'no_telp' => 'required|max:30',
            'alamat' => 'nullable|max:1000',
            'password' => 'nullable|min:6|confirmed',
        ];

        // Customer role is fixed. Admin may edit customer data/status,
        // but cannot silently turn a Customer into a staff role.
        if ($account->tipe_akun !== 'CUSTOMER') {
            $rules['tipe_akun'] = 'required|in:ADMIN,KASIR';
        }

        $validated = $request->validate($rules);

        $data = [
            'nama' => $validated['nama'],
            'username' => $validated['username'],
            'email' => $validated['email'] ?? null,
            'no_telp' => $validated['no_telp'],
            'alamat' => $validated['alamat'] ?? null,
        ];

        if (isset($validated['tipe_akun'])) {
            $data['tipe_akun'] = $validated['tipe_akun'];
        }

        if (!empty($validated['password'])) {
            $data['password_hash'] = Hash::make(
                $validated['password']
            );
        }

        $account->update($data);

        return redirect()
            ->route('admin.accounts.index')
            ->with('success', 'Akun diperbarui.');
    }

    public function toggleStatus(string $id)
    {
        $account = Akun::findOrFail($id);

        if ($id === Auth::id()) {
            return back()->withErrors([
                'account' =>
                    'Admin tidak dapat menonaktifkan akun yang sedang digunakan.',
            ]);
        }

        $account->update([
            'status_akun' => $account->status_akun === 'AKTIF'
                ? 'NONAKTIF'
                : 'AKTIF',
        ]);

        return back()->with(
            'success',
            'Status akun diperbarui.'
        );
    }
}
