<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LoginController;

// ADMIN
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PromoController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\StaffAccountController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\VariantController;

// CUSTOMER MILIK TIM
use App\Http\Controllers\Customers\Catalog;

// SEMENTARA (e2e): controller alur belanja online sementara
use App\Http\Controllers\Customers\Cart;
use App\Http\Controllers\Customers\Checkout;
use App\Http\Controllers\Customers\Customerorder;
use App\Http\Controllers\Customers\Customerprofile;


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
        ->where('produk.status_produk', 'AKTIF')
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

    Route::get('/login', [
        LoginController::class,
        'showLogin'
    ])->name('login');

    Route::post('/login', [
        LoginController::class,
        'login'
    ])->name('login.process');


    Route::get('/register', [
        LoginController::class,
        'showRegister'
    ])->name('register');

    Route::post('/register', [
        LoginController::class,
        'register'
    ])->name('register.process');
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
])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Dashboard
        |--------------------------------------------------------------------------
        */

        Route::get('/', [
            AdminDashboardController::class,
            'index'
        ])->name('dashboard');


        /*
        |--------------------------------------------------------------------------
        | Akun
        |--------------------------------------------------------------------------
        */

        Route::get('/akun', [
            StaffAccountController::class,
            'index'
        ])->name('accounts.index');

        Route::get('/akun/tambah', [
            StaffAccountController::class,
            'create'
        ])->name('accounts.create');

        Route::post('/akun', [
            StaffAccountController::class,
            'store'
        ])->name('accounts.store');

        Route::get('/akun/{id}/edit', [
            StaffAccountController::class,
            'edit'
        ])->name('accounts.edit');

        Route::put('/akun/{id}', [
            StaffAccountController::class,
            'update'
        ])->name('accounts.update');

        Route::patch('/akun/{id}/status', [
            StaffAccountController::class,
            'toggleStatus'
        ])->name('accounts.status');


        /*
        |--------------------------------------------------------------------------
        | Kategori
        |--------------------------------------------------------------------------
        */

        Route::get('/kategori', [
            CategoryController::class,
            'index'
        ])->name('categories.index');

        Route::post('/kategori', [
            CategoryController::class,
            'store'
        ])->name('categories.store');

        Route::put('/kategori/{id}', [
            CategoryController::class,
            'update'
        ])->name('categories.update');

        Route::delete('/kategori/{id}', [
            CategoryController::class,
            'destroy'
        ])->name('categories.destroy');


        /*
        |--------------------------------------------------------------------------
        | Produk
        |--------------------------------------------------------------------------
        */

        Route::get('/produk', [
            ProductController::class,
            'index'
        ])->name('products.index');

        Route::get('/produk/tambah', [
            ProductController::class,
            'create'
        ])->name('products.create');

        Route::post('/produk', [
            ProductController::class,
            'store'
        ])->name('products.store');

        Route::get('/produk/{id}/edit', [
            ProductController::class,
            'edit'
        ])->name('products.edit');

        Route::put('/produk/{id}', [
            ProductController::class,
            'update'
        ])->name('products.update');

        Route::delete('/produk/{id}', [
            ProductController::class,
            'destroy'
        ])->name('products.destroy');


        /*
        |--------------------------------------------------------------------------
        | Varian
        |--------------------------------------------------------------------------
        */

        Route::get('/produk/{idProduk}/varian', [
            VariantController::class,
            'index'
        ])->name('variants.index');

        Route::post('/produk/{idProduk}/varian', [
            VariantController::class,
            'store'
        ])->name('variants.store');

        Route::put('/varian/{id}', [
            VariantController::class,
            'update'
        ])->name('variants.update');

        Route::delete('/varian/{id}', [
            VariantController::class,
            'destroy'
        ])->name('variants.destroy');


        /*
        |--------------------------------------------------------------------------
        | Supplier
        |--------------------------------------------------------------------------
        */

        Route::get('/supplier', [
            SupplierController::class,
            'index'
        ])->name('suppliers.index');

        Route::get('/supplier/tambah', [
            SupplierController::class,
            'create'
        ])->name('suppliers.create');

        Route::post('/supplier', [
            SupplierController::class,
            'store'
        ])->name('suppliers.store');

        Route::get('/supplier/{id}/edit', [
            SupplierController::class,
            'edit'
        ])->name('suppliers.edit');

        Route::put('/supplier/{id}', [
            SupplierController::class,
            'update'
        ])->name('suppliers.update');


        /*
        |--------------------------------------------------------------------------
        | Pembelian Produk
        |--------------------------------------------------------------------------
        */

        Route::get('/pembelian', [
            PurchaseController::class,
            'index'
        ])->name('purchases.index');

        Route::get('/pembelian/tambah', [
            PurchaseController::class,
            'create'
        ])->name('purchases.create');

        Route::post('/pembelian', [
            PurchaseController::class,
            'store'
        ])->name('purchases.store');

        Route::get('/pembelian/{id}', [
            PurchaseController::class,
            'show'
        ])->name('purchases.show');

        Route::patch('/pembelian/{id}/terima', [
            PurchaseController::class,
            'receive'
        ])->name('purchases.receive');

        Route::patch('/pembelian/{id}/batal', [
            PurchaseController::class,
            'cancel'
        ])->name('purchases.cancel');


        /*
        |--------------------------------------------------------------------------
        | Penjualan
        |--------------------------------------------------------------------------
        */

        Route::get('/penjualan', [
            SalesController::class,
            'index'
        ])->name('sales.index');

        Route::get('/penjualan/{id}', [
            SalesController::class,
            'show'
        ])->name('sales.show');

        Route::patch('/penjualan/{id}/status', [
            SalesController::class,
            'updateStatus'
        ])->name('sales.status');


        /*
        |--------------------------------------------------------------------------
        | Promo
        |--------------------------------------------------------------------------
        */

        Route::get('/promo', [
            PromoController::class,
            'index'
        ])->name('promos.index');

        Route::get('/promo/tambah', [
            PromoController::class,
            'create'
        ])->name('promos.create');

        Route::post('/promo', [
            PromoController::class,
            'store'
        ])->name('promos.store');

        Route::get('/promo/{id}/edit', [
            PromoController::class,
            'edit'
        ])->name('promos.edit');

        Route::put('/promo/{id}', [
            PromoController::class,
            'update'
        ])->name('promos.update');


        /*
        |--------------------------------------------------------------------------
        | Pengeluaran
        |--------------------------------------------------------------------------
        */

        Route::get('/pengeluaran', [
            ExpenseController::class,
            'index'
        ])->name('expenses.index');

        Route::get('/pengeluaran/tambah', [
            ExpenseController::class,
            'create'
        ])->name('expenses.create');

        Route::post('/pengeluaran', [
            ExpenseController::class,
            'store'
        ])->name('expenses.store');

        Route::get('/pengeluaran/{id}/edit', [
            ExpenseController::class,
            'edit'
        ])->name('expenses.edit');

        Route::put('/pengeluaran/{id}', [
            ExpenseController::class,
            'update'
        ])->name('expenses.update');


        /*
        |--------------------------------------------------------------------------
        | Laporan
        |--------------------------------------------------------------------------
        */

        Route::get('/laporan', [
            ReportController::class,
            'index'
        ])->name('reports.index');
    });


/*
|--------------------------------------------------------------------------
| KASIR - MILIK TIM
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'role:KASIR'
])
    ->prefix('kasir')
    ->name('kasir.')
    ->group(function () {

        Route::get('/', fn () => view('kasir.dashboard'))
            ->name('dashboard');

        Route::get('/pos', [
            \App\Http\Controllers\KasirController::class,
            'pos'
        ])->name('pos');

        Route::post('/pos', [
            \App\Http\Controllers\KasirController::class,
            'store'
        ])->name('store');

        Route::get('/riwayat', [
            \App\Http\Controllers\KasirController::class,
            'riwayat'
        ])->name('riwayat');

        Route::get('/riwayat/{idPenjualan}', [
            \App\Http\Controllers\KasirController::class,
            'show'
        ])->name('struk');
    });


/*
|--------------------------------------------------------------------------
| CUSTOMER - MILIK TIM
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'role:CUSTOMER'
])->group(function () {

    Route::get('/customer', function () {
        return view('customer.dashboard');
    })->name('customer.dashboard');

    Route::get('/customer/katalog', [
        Catalog::class,
        'index'
    ])->name('customer.catalog');

    Route::get('/customer/produk/{idProduk}', [
        Catalog::class,
        'show'
    ])->name('customer.products.show');

    /*
    | SEMENTARA (e2e): keranjang, checkout, pembayaran simulasi, pesanan,
    | profil. Ganti ketika modul Customer resmi selesai.
    */

    Route::get('/customer/keranjang', [Cart::class, 'index'])
        ->name('customer.cart');
    Route::post('/customer/keranjang', [Cart::class, 'store'])
        ->name('customer.cart.store');
    Route::patch('/customer/keranjang/{idVarian}', [Cart::class, 'update'])
        ->name('customer.cart.update');
    Route::delete('/customer/keranjang/{idVarian}', [Cart::class, 'destroy'])
        ->name('customer.cart.destroy');

    Route::get('/customer/checkout', [Checkout::class, 'create'])
        ->name('customer.checkout');
    Route::post('/customer/checkout', [Checkout::class, 'store'])
        ->name('customer.checkout.store');

    Route::get('/customer/pesanan', [Customerorder::class, 'index'])
        ->name('customer.orders.index');
    Route::get('/customer/pesanan/{idPenjualan}', [Customerorder::class, 'show'])
        ->name('customer.orders.show');
    Route::get('/customer/pesanan/{idPenjualan}/bayar', [Customerorder::class, 'payment'])
        ->name('customer.payment');
    Route::post('/customer/pesanan/{idPenjualan}/bayar', [Customerorder::class, 'pay'])
        ->name('customer.payment.pay');
    Route::post('/customer/pesanan/{idPenjualan}/gagal', [Customerorder::class, 'fail'])
        ->name('customer.payment.fail');
    Route::patch('/customer/pesanan/{idPenjualan}/batal', [Customerorder::class, 'cancel'])
        ->name('customer.orders.cancel');

    Route::get('/customer/profil', [Customerprofile::class, 'show'])
        ->name('customer.profile');
});