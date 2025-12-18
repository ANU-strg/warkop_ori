<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\TableController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\MidtransCallbackController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Route;

// Home route
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Public routes for customers
Route::get('/scan/{uuid}', [PublicController::class, 'scan'])->name('scan');
Route::get('/menu', [PublicController::class, 'menu'])->name('menu');

// Cart routes
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::patch('/cart/{menuId}', [CartController::class, 'update'])->name('cart.update');
Route::patch('/cart/{menuId}/note', [CartController::class, 'updateNote'])->name('cart.updateNote');
Route::delete('/cart/{menuId}', [CartController::class, 'remove'])->name('cart.remove');
Route::delete('/cart', [CartController::class, 'clear'])->name('cart.clear');
Route::post('/cart/save-notes', [CartController::class, 'saveNotes'])->name('cart.saveNotes');

// Checkout routes
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
Route::get('/payment/{orderId}', [CheckoutController::class, 'payment'])->name('payment');
Route::get('/order/success/{orderId}', [CheckoutController::class, 'success'])->name('order.success');
Route::delete('/order/{orderId}/cancel', [CheckoutController::class, 'cancel'])->name('order.cancel');

// Midtrans callback routes (no middleware, accessed by Midtrans server)
Route::post('/midtrans/callback', [MidtransCallbackController::class, 'receive'])->name('midtrans.callback');
Route::get('/midtrans/finish', [MidtransCallbackController::class, 'finish'])->name('midtrans.finish');
Route::get('/midtrans/unfinish', [MidtransCallbackController::class, 'unfinish'])->name('midtrans.unfinish');
Route::get('/midtrans/error', [MidtransCallbackController::class, 'error'])->name('midtrans.error');

// Admin routes (protected by auth middleware)
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Table Management
    Route::resource('tables', TableController::class);
    
    // Category Management
    Route::resource('categories', CategoryController::class);
    
    // Menu Management
    Route::resource('menus', MenuController::class);
    
    // Order Management
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::post('/orders/{order}/mark-paid', [OrderController::class, 'markAsPaid'])->name('orders.markPaid');
});

// Breeze default auth routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';