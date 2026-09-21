<?php

namespace App\Http\Controllers;

use App\Models\Akun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | CUSTOMER LOGIN
    |--------------------------------------------------------------------------
    */

    public function showCustomerLogin()
    {
        return view('auth.login');
    }

    public function customerLogin(Request $request)
    {
        $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);

        $akun = Akun::where('username', $request->username)
            ->where('status_akun', 'AKTIF')
            ->first();

        if (!$akun || !Hash::check($request->password, $akun->password_hash)) {
            return back()
                ->withErrors([
                    'username' => 'Username atau password salah.'
                ])
                ->onlyInput('username');
        }

        if ($akun->tipe_akun !== 'CUSTOMER') {
            return back()
                ->withErrors([
                    'username' => 'Akun staf harus login melalui Staff Login.'
                ])
                ->onlyInput('username');
        }

        Auth::login($akun);

        $request->session()->regenerate();

        return redirect()->route('customer.dashboard');
    }

    /*
    |--------------------------------------------------------------------------
    | CUSTOMER REGISTER
    |--------------------------------------------------------------------------
    */

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'nama' => ['required', 'max:255'],

            'username' => [
                'required',
                'max:255',
                'unique:akun,username'
            ],

            'email' => [
                'nullable',
                'email',
                'max:255',
                'unique:akun,email'
            ],

            'no_telp' => ['required', 'max:30'],

            'alamat' => ['nullable'],

            'password' => [
                'required',
                'min:6',
                'confirmed'
            ],
        ]);

        $lastCustomer = Akun::where(
            'id_akun',
            'like',
            'CUST%'
        )
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

        return redirect()->route('customer.dashboard');
    }

    /*
    |--------------------------------------------------------------------------
    | STAFF LOGIN
    |--------------------------------------------------------------------------
    */

    public function showStaffLogin()
    {
        return view('auth.staff-login');
    }

    public function staffLogin(Request $request)
    {
        $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);

        $akun = Akun::where('username', $request->username)
            ->where('status_akun', 'AKTIF')
            ->first();

        if (!$akun || !Hash::check($request->password, $akun->password_hash)) {
            return back()
                ->withErrors([
                    'username' => 'Username atau password staf salah.'
                ])
                ->onlyInput('username');
        }

        if (!in_array(
            $akun->tipe_akun,
            ['ADMIN', 'KASIR'],
            true
        )) {
            return back()
                ->withErrors([
                    'username' => 'Akun Customer tidak dapat login melalui Staff Login.'
                ])
                ->onlyInput('username');
        }

        Auth::login($akun);

        $request->session()->regenerate();

        if ($akun->tipe_akun === 'ADMIN') {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('kasir.dashboard');
    }

    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}