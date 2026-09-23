<?php

namespace App\Http\Controllers;

use App\Models\Akun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'username' => [
                'required',
                'string',
            ],
            'password' => [
                'required',
                'string',
            ],
        ]);

        $identifier = $validated['username'];

        $akun = Akun::query()
            ->where('id_akun', $identifier)
            ->orWhere('username', $identifier)
            ->orWhere('email', $identifier)
            ->first();

        if (
            !$akun ||
            !Hash::check(
                $validated['password'],
                $akun->password_hash
            )
        ) {
            return back()
                ->withErrors([
                    'username' =>
                        'ID akun, username, email, atau password salah.',
                ])
                ->onlyInput('username');
        }

        if ($akun->status_akun !== 'AKTIF') {
            return back()
                ->withErrors([
                    'username' => 'Akun sedang tidak aktif.',
                ])
                ->onlyInput('username');
        }

        Auth::login($akun);

        $request->session()->regenerate();

        return match ($akun->tipe_akun) {
            'ADMIN' => redirect()
                ->route('admin.dashboard'),

            'KASIR' => redirect()
                ->route('kasir.dashboard'),

            'CUSTOMER' => redirect()
                ->route('customer.dashboard'),

            default => $this->rejectUnknownRole($request),
        };
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'nama' => [
                'required',
                'string',
                'max:255',
            ],

            'username' => [
                'required',
                'string',
                'max:100',
                'unique:akun,username',
            ],

            'email' => [
                'nullable',
                'email',
                'max:255',
                'unique:akun,email',
            ],

            'no_telp' => [
                'required',
                'string',
                'max:30',
            ],

            'alamat' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'password' => [
                'required',
                'string',
                'min:6',
                'confirmed',
            ],
        ]);

        $lastCustomer = Akun::query()
            ->where('id_akun', 'like', 'CUST%')
            ->orderByRaw(
                'CAST(SUBSTRING(id_akun, 5) AS UNSIGNED) DESC'
            )
            ->first();

        $nextNumber = $lastCustomer
            ? ((int) substr($lastCustomer->id_akun, 4)) + 1
            : 1;

        $idAkun = 'CUST' . str_pad(
            $nextNumber,
            3,
            '0',
            STR_PAD_LEFT
        );

        $akun = Akun::create([
            'id_akun' => $idAkun,
            'nama' => $validated['nama'],
            'username' => $validated['username'],
            'password_hash' => Hash::make(
                $validated['password']
            ),
            'email' => $validated['email'] ?? null,
            'no_telp' => $validated['no_telp'],
            'alamat' => $validated['alamat'] ?? null,
            'tipe_akun' => 'CUSTOMER',
            'status_akun' => 'AKTIF',
        ]);

        Auth::login($akun);

        $request->session()->regenerate();

        return redirect()
            ->route('customer.dashboard');
    }

    private function rejectUnknownRole(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->withErrors([
                'username' => 'Tipe akun tidak dikenali.',
            ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('home');
    }
}