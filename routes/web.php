<?php

use App\Http\Controllers\MenuController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;    
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('menu');
});

Route::get('/menu', [MenuController::class,'index'])->name('menu');
Route::post('/cart/add', [MenuController::class, 'addToCart'])->name('cart.add');

Route::get('/cart', [CartController::class,'cart'])->name('cart');
Route::post('/cart/update', [CartController::class, 'updateCart'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'removeCart'])->name('cart.remove');
Route::get('/cart/clear', [CartController::class, 'clearCart'])->name('cart.clear');

Route::get('/checkout', [CheckoutController::class, 'checkout'])->name('checkout');
Route::post('/checkout/store', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/checkout/success/{orderId}', [CheckoutController::class, 'checkoutSuccess'])->name('checkout.success');


///admin

Route::get('/dashboard', function(){
    return view('admin.dashboard');
})->name('dashboard');

Route::resource('/categories', CategoryController::class);

Route::resource('/roles', RoleController::class);
Route::resource('/orders', OrderController::class);
Route::resource('/users', UserController::class);

Route::resource('/items', ItemController::class);
Route::post('items/update-status/{order}', [ItemController::class, 'updateStatus'])->name('items.updateStatus');