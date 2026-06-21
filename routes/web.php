<?php

use App\Http\Controllers\Web\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/auth', function () {
    return view('auth');
});
Route::middleware(['throttle:60,1'])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/products/{id}/details', [HomeController::class, 'getProductDetails'])->whereNumber('id')->name('products.details');
    Route::get('/cart/view', [HomeController::class, 'viewCart'])->name('cart.view');
    Route::post('/cart/remove', [HomeController::class, 'removeFromCart'])->name('cart.remove');
});