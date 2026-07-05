<?php

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\VariantController;
use Illuminate\Support\Facades\Route;

/*
 * |--------------------------------------------------------------------------
 * | Admin Routes
 * |--------------------------------------------------------------------------
 * | Add this line to routes/web.php:
 * |     require __DIR__.'/admin.php';
 */

Route::prefix('/admin')->name('admin.')->group(function () {
    // Guest-only admin auth
    Route::middleware('guest')->group(function () {
        Route::get('login', [AdminAuthController::class, 'showLogin'])->name('login');
        Route::post('login', [AdminAuthController::class, 'login']);
        Route::get('register', [AdminAuthController::class, 'showRegister'])->name('register');
        Route::post('register', [AdminAuthController::class, 'register']);
    });

    Route::post('logout', [AdminAuthController::class, 'logout'])
        ->middleware('auth')
        ->name('logout');

    // Protected admin area
    Route::middleware('admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('products', [ProductController::class, 'index'])->name('products.index');
        Route::get('products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('products', [ProductController::class, 'store'])->name('products.store');

        // ⬇️ must be declared BEFORE products/{product} so "bulk-delete" isn't captured as {product}
        Route::delete('products/bulk-delete', [ProductController::class, 'bulkDestroy'])->name('products.bulkDestroy');

        Route::get('products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('products/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

        Route::resource('variants', VariantController::class);

        Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('orders/{orderId}', [OrderController::class, 'show'])->name('orders.show');
        Route::put('orders/{order}', [OrderController::class, 'update'])->name('orders.update');

        // ⬇️ same rule as products/bulk-delete above — declare specific
        // literal segments BEFORE orders/{orderId} so "advance-status" and
        // "cancel" aren't swallowed as the {order} wildcard.
        Route::post('orders/{order}/advance-status', [OrderController::class, 'advanceStatus'])->name('orders.advanceStatus');
        Route::post('orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
        Route::delete('orders/{order}', [OrderController::class, 'destroy'])->name('admin.orders.destroy');

        Route::get('customers', [CustomerController::class, 'index'])->name('customers.index');
        Route::get('customers/{customer}', [CustomerController::class, 'show'])->name('customers.show');

        Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
        Route::put('settings/profile', [SettingController::class, 'updateProfile'])->name('settings.profile.update');
        Route::put('settings/store', [SettingController::class, 'updateStore'])->name('settings.store.update');
        Route::put('settings/notifications', [SettingController::class, 'updateNotifications'])->name('settings.notifications.update');
        Route::put('settings/password', [SettingController::class, 'updatePassword'])->name('settings.password.update');
    });
});
