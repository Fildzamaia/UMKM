<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Models\Akun;
use Illuminate\Http\Request;

class Customerprofile extends Controller
{
    public function show()
    {
        $akun = auth()->user();

        return view('customer.profile', compact('akun'));
    }
}