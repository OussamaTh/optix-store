<?php

use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WishlistController;
use App\Models\CartItem;
use Illuminate\Support\Facades\Route;

require __DIR__ . '/admin.php';

Route::get('/', function () {
    $products = \App\Models\Product::latest()->take(8)->get();
    return view('welcome', compact('products'));
})->name('main-page');

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{productId}', [ProductController::class, 'show'])->name('products.show');

Route::middleware('guest')->group(function () {
    // Registration
    Route::get('/register', [RegisterController::class, 'create'])
        ->name('register');

    Route::post('/register', [RegisterController::class, 'store']);

    // Login
    Route::get('/login', [LoginController::class, 'create'])
        ->name('login');

    Route::post('/login', [LoginController::class, 'store']);

    // Google OAuth
    Route::get('/auth/google', [GoogleController::class, 'redirect'])
        ->name('auth.google');

    Route::get('/auth/google/callback', [GoogleController::class, 'callback']);
});

Route::middleware(['auth', "customer"])->group(function () {
    // Logout
    Route::post('/logout', [LoginController::class, 'destroy'])
        ->name('logout');

    // Account Dashboard
    Route::get('/account', [AccountController::class, 'index'])->name('account.index');
    Route::post('/account/orders/simulate', [AccountController::class, 'simulateOrder'])->name('account.orders.simulate');

    // Profile Updates
    Route::put('/account/profile', [ProfileController::class, 'update'])->name('account.profile.update');
    Route::put('/account/password', [ProfileController::class, 'updatePassword'])->name('account.password.update');

    // Saved Addresses
    Route::post('/account/addresses', [AddressController::class, 'store'])->name('account.addresses.store');
    Route::put('/account/addresses/{address}', [AddressController::class, 'update'])->name('account.addresses.update');
    Route::delete('/account/addresses/{address}', [AddressController::class, 'destroy'])->name('account.addresses.destroy');
    Route::post('/account/addresses/{address}/default', [AddressController::class, 'makeDefault'])->name('account.addresses.default');

    // Wishlist
    Route::post('/account/wishlist/toggle', [WishlistController::class, 'toggle'])->name('account.wishlist.toggle');
    Route::delete('/account/wishlist/{wishlist}', [WishlistController::class, 'destroy'])->name('account.wishlist.destroy');

    Route::get('/account/orders/{order}/tracking', [AccountController::class, 'tracking'])
        ->name('account.orders.tracking');

    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::get('/cart/sidebar', [CartController::class, 'sidebar'])->name('cart.sidebar');
    Route::patch('/cart/{cartItem}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{cartItem}', [CartController::class, 'destroy'])->name('cart.destroy');
    Route::post("/cart/add-to-cart", [CartController::class, "store"])->name("cart.store");
    Route::post('/cart/{cartItem}/increment', [CartController::class, 'incQt'])->name('cart.incQt');
    Route::post('/cart/{cartItem}/decrement', [CartController::class, 'decQt'])->name('cart.decQt');

    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
});


// routes/web.php — remove after testing
Route::get('/test-error/{code}', function ($code) {
    abort((int) $code);
});
