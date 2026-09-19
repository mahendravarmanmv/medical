<?php

use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\CartController;
use App\Http\Controllers\Web\OrderController;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

// Standard Authentication UI View Render Frame
Route::get('/auth', function () {
    return view('auth');
})->middleware(['web', 'guest'])->name('login'); // Added named parameter link for security layers

// Global Application Core Actions (Throttled for Security & High Performance)
Route::middleware(['web', 'throttle:60,1'])->group(function () {
    
    // 1. Storefront Product Catalog Grid Views
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/products/{id}/details', [HomeController::class, 'getProductDetails'])->whereNumber('id')->name('products.details');
    
    // 2. State-Driven Cart Synchronization Engine
    Route::get('/cart/view', [CartController::class, 'viewCart'])->name('cart.view');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add'); 
    Route::post('/cart/remove', [CartController::class, 'removeFromCart'])->name('cart.remove');
    Route::post('/cart/update-quantity', [App\Http\Controllers\Web\CartController::class, 'updateQuantity'])->name('cart.update-quantity');

    // 3. High-Precision Checkout, Invoice Calculation, & Billing Engine
	Route::get(
	'/checkout',
	[CartController::class, 'showCheckoutPage']
	)->middleware('auth')->name('checkout');

	Route::get(
	'/checkout/summary',
	[CartController::class, 'getCheckoutCalculationSummary']
	)->middleware('auth')->name('checkout.summary');

	Route::post(
	'/checkout/place-order',
	[CartController::class, 'placeOrder']
	)->middleware('auth')->name('checkout.place-order');
	
	Route::get(
	'/order/success/{orderNumber}',
	[CartController::class, 'orderSuccess']
	)->middleware('auth')->name('order.success');

    // 5. Guest Authentication Post Actions
    Route::middleware('guest')->group(function () {
        Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
        Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    });

    // 6. Authenticated Only Security Actions
    Route::middleware('auth')->group(function () {        
		
		Route::get('/orders',[OrderController::class, 'index'])->name('orders.index');
		Route::get('/orders/{orderNumber}',[OrderController::class, 'show'])->name('orders.show');
		Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    });
});