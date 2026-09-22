<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Customers\Catalog;

/*
|--------------------------------------------------------------------------
| PUBLIC HOMEPAGE
|--------------------------------------------------------------------------
*/

Route::get('/', function () {

    $products = DB::table('produk')
        ->join(
            'kategori_produk',
            'produk.id_kategori',
            '=',
            'kategori_produk.id_kategori'
        )
        ->where(
            'produk.status_produk',
            'AKTIF'
        )
        ->select(
            'produk.*',
            'kategori_produk.nama_kategori'
        )
        ->get();

    return view('home', compact('products'));

})->name('home');


/*
|--------------------------------------------------------------------------
| GUEST AUTH
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {

    // Customer login
    Route::get('/login', [
        LoginController::class,
        'showLogin'
    ])->name('login');

    Route::post('/login', [
        LoginController::class,
        'Login'
    ])->name('login.process');


    // Customer register
    Route::get('/register', [
        LoginController::class,
        'showRegister'
    ])->name('register');

    Route::post('/register', [
        LoginController::class,
        'register'
    ])->name('register.process');


    Route::get(
        '/produk/{idProduk}/varian',
        [
            ProductController::class,
            'variants'
        ]
    )->name('admin.products.variants');

});


/*
|--------------------------------------------------------------------------
| LOGOUT
|--------------------------------------------------------------------------
*/

Route::post('/logout', [
    LoginController::class,
    'logout'
])
    ->middleware('auth')
    ->name('logout');


/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'role:ADMIN'
])->prefix('admin')->group(function () {

    Route::get(
        '/',
        [
            AdminDashboardController::class,
            'index'
        ]
    )->name('admin.dashboard');


    Route::get(
        '/produk',
        [
            ProductController::class,
            'index'
        ]
    )->name('admin.products.index');


    Route::get(
        '/produk/tambah',
        [
            ProductController::class,
            'create'
        ]
    )->name('admin.products.create');


    Route::post(
        '/produk',
        [
            ProductController::class,
            'store'
        ]
    )->name('admin.products.store');

});


/*
|--------------------------------------------------------------------------
| KASIR
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'role:KASIR'
])->group(function () {

    Route::get('/kasir', function () {
        return view('kasir.dashboard');
    })->name('kasir.dashboard');

});


/*
|--------------------------------------------------------------------------
| CUSTOMER
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'role:CUSTOMER'
])->group(function () {

    Route::get('/customer', function () {
        return view('customer.dashboard');
    })->name('customer.dashboard');

    Route::get(
        '/customer/katalog',
        [Catalog::class, 'index']
    )->name('customer.catalog');
    
    Route::get(
        '/customer/produk/{idProduk}',
        [Catalog::class, 'show']
    )->name('customer.products.show');
});