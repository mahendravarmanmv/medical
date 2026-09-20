<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Web\CartController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\OrderController;
use App\Http\Controllers\Web\PincodeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/

Route::get('/auth', function () {
    return view('auth');
})->middleware('guest')->name('login');

Route::middleware('guest')->group(function () {
    Route::post('/register', [AuthController::class, 'register'])
        ->name('register.submit');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('login.submit');
});

/*
|--------------------------------------------------------------------------
| Storefront
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])
    ->name('home');

Route::get('/products/{id}/details', [HomeController::class, 'getProductDetails'])
    ->whereNumber('id')
    ->name('products.details');

/*
|--------------------------------------------------------------------------
| Pincode
|--------------------------------------------------------------------------
*/

Route::post('/pincode/select', [PincodeController::class, 'select'])
    ->name('pincode.select');

/*
|--------------------------------------------------------------------------
| Cart
|--------------------------------------------------------------------------
*/

Route::get('/cart/view', [CartController::class, 'viewCart'])
    ->name('cart.view');

Route::post('/cart/add', [CartController::class, 'add'])
    ->name('cart.add');

Route::post('/cart/remove', [CartController::class, 'removeFromCart'])
    ->name('cart.remove');

Route::post('/cart/update-quantity', [CartController::class, 'updateQuantity'])
    ->name('cart.update-quantity');

Route::post('/cart/save-for-later', [CartController::class, 'saveForLater'])
    ->name('cart.save-for-later');

/*
|--------------------------------------------------------------------------
| Checkout
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/checkout', [CartController::class, 'showCheckoutPage'])
        ->name('checkout');

    Route::get('/checkout/summary', [CartController::class, 'getCheckoutCalculationSummary'])
        ->name('checkout.summary');

    Route::post('/checkout/place-order', [CartController::class, 'placeOrder'])
        ->name('checkout.place-order');

    Route::get('/order/success/{orderNumber}', [CartController::class, 'orderSuccess'])
        ->name('order.success');
});

/*
|--------------------------------------------------------------------------
| Customer Orders & Account
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/orders', [OrderController::class, 'index'])
        ->name('orders.index');

    Route::get('/orders/{orderNumber}', [OrderController::class, 'show'])
        ->name('orders.show');

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');
});