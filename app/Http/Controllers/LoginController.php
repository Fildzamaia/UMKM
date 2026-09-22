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
    | LOGIN SEMUA ROLE
    |--------------------------------------------------------------------------
    */

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $akun = Akun::where(
            'username', 
            $validated['username']
            )->first();

            if (
                !$akun ||
                !Hash::check(
                    $validated['password'],
                    $akun->password_hash
                )
            ){
                return back()
                ->withErrors([
                    'username' => 
                         'Username atau password salah.'
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

    /*
    REGISTER KHUSUS CUSTOMER
    */

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
                 'max:255',
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
            ],

            'password' => [
                'required',
                'min:6',
                'confirmed',
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

             $nectNumber = $lastCustomer
             ? ((int)substr(
                $lastCustomer->id_akun, 
                4
             )) + 1
             : 1; 

             $idAkun = 'CUST' . str_pad(
                $nextNumber,
                3,
                '0',
                STR_PAD_LEFT
            );

             $akun = Akun::create([
                'id_akun' => $idAkun,
                'nama' => validated['nama'],
                'username' => validated['username'],

                'password_hash' => Hash::make(
                    $validated['password']
                ),

                'email' => validated['email'] ?? null,
                'no_telp' => validated['no_telp'],
                'alamat' => validated['alamat'] ?? null,
                'tipe_akun' => 'CUSTOMER',
                'status_akun' => 'AKTIF'
            ]);

            Auth::Login($akun);

            $request->session()->regenerate();

            return redirect()
                ->route('customer.dashboard');
    }

    /*
    |--------------------------------------------------------------------------
    | ROLE TIDAK DIKENAL
    |--------------------------------------------------------------------------
    */
    
    private function rejectUnknownRole(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $reqeust->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->withErrors([
                'username' => 'Tipe akun tidak dikenali.',
            ]);
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

        return redirect()
            ->route('home');
    }
}