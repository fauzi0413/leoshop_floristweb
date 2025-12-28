<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductManageController;
use App\Http\Controllers\UserManageController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CategoryController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

# 🏠 Halaman Umum
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/shop', [PageController::class, 'shop'])->name('shop');

Route::post('/checkout/midtrans', [PaymentController::class, 'checkout'])->name('cart.checkout.midtrans');
Route::post('/midtrans/callback', [PaymentController::class, 'handleCallback'])->name('midtrans.callback');
Route::get('/payment/status/{status}', function ($status) {
    abort_unless(in_array($status, ['success', 'pending', 'failed']), 404);
    return view('payment-status', compact('status'));
})->name('payment.status');

/*
|--------------------------------------------------------------------------
| 🔒 Halaman Khusus Login
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');

    Route::get('/favorites', [PageController::class, 'favorites'])->name('favorites.index');
    Route::post('/favorite/{id}', [PageController::class, 'toggleFavorite'])->name('favorite.toggle');

    Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');

    Route::get('/checkout', [OrderController::class, 'checkout'])->name('order.checkout');
    Route::post('/checkout', [OrderController::class, 'process'])->name('order.process');
    Route::get('/payment/{id}', [OrderController::class, 'paymentPage'])->name('payment.show');
    Route::get('/orders', [OrderController::class, 'myOrders'])->name('orders.index');
    Route::put('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('order.cancel');
    Route::post('/orders/{order}/confirm-received', [OrderController::class, 'confirmReceived'])->name('order.confirm_received');
    Route::post('/orders/{order}/return-request', [OrderController::class, 'returnRequest'])->name('order.return_request');

    Route::post('/payment/{id}/upload-proof', [OrderController::class, 'uploadProof'])
    ->name('payment.upload_proof')
    ->middleware('auth');


    # 🙋‍♀️ User Biasa
    Route::middleware(['role:user'])->group(function () {
    });

    # 👑 Admin (mengelola produk)
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('index');
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
        Route::get('/show-product', [ProductManageController::class, 'index'])->name('show_product');
        Route::resource('/products', ProductManageController::class)->names('products');

        Route::get('/show-user', [UserManageController::class, 'index'])->name('show_user');
        Route::resource('/users', UserManageController::class)->names('users');
         
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
        Route::put('/orders/{order}', [OrderController::class, 'updateStatus'])->name('orders.update');
        Route::delete('/orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');

        Route::post('/categories/store', [CategoryController::class, 'store'])->name('categories.store');
        Route::put('/categories/update/{id}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/delete/{id}', [CategoryController::class, 'destroy'])->name('categories.delete');

    });
});


/*
|--------------------------------------------------------------------------
| 🚫 Halaman Tidak Ditemukan
|--------------------------------------------------------------------------
*/
Route::fallback(function () {
    return response()->view('404', [], 404);
});


require __DIR__.'/auth.php';
