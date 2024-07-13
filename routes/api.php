<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CartItemsController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderItemsController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PaymentController;

Route::middleware('auth:sanctum')->group(function () {

    Route::middleware('role:admin')->group(function () {
        Route::get('admin/get-users', [AdminController::class, 'getAllUsers'])->name('admin.get.all.users');
        Route::post('admin/add-user', [AdminController::class, 'addUser'])->name('admin.add.user');
        Route::delete('admin/delete-user/{user}', [AdminController::class, 'deleteUser'])->name('admin.delete.user');
        Route::post('book/store', [BookController::class,'store'])->name('book.store');
        Route::put('book/update/{book}', [BookController::class,'update'])->name('book.update');
        Route::delete('book/delete/{book}', [BookController::class,'delete'])->name('book.delete');
    });

    Route::middleware('role:user')->group(function () {
        Route::get('cart/search', [CartController::class,'show'])->name('cart.search');
        Route::post('cart/store', [CartController::class,'store'])->name('cart.store');
        Route::delete('cart/delete', [CartController::class,'destroy'])->name('cart.delete');
        Route::resource('order', OrderController::class)->only(['index', 'show', 'destroy', 'update', 'store']);
        Route::resource('order-item', OrderItemsController::class)->only(['index', 'show', 'destroy', 'update', 'store']);
        Route::post('review/add-review', [ReviewController::class, 'store'])->name('user.add.review');
        Route::get('review/show-review/{review}', [ReviewController::class, 'show'])->name('user.show.review');
        Route::put('review/edit-review/{review}', [ReviewController::class, 'edit'])->name('user.edit.review');
        Route::delete('review/delete-review/{review}', [ReviewController::class, 'delete'])->name('user.delete.review');
        Route::post('/payment', [PaymentController::class, 'handlePayment'])->name('handle-payment');
    });

    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('profile',[AuthController::class, 'profile'])->name('profile');
});

Route::get('books', [BookController::class, 'index'])->name('book.index');
Route::get('book/details', [BookController::class, 'bookDetails']);
Route::get('book/search', [BookController::class, 'search'])->name('book.search');
Route::get('book/show', [BookController::class, 'show'])->name('book.show');
Route::get('review/get-reviews/{book}', [ReviewController::class, 'index'])->name('user.get.reviews');
Route::post('register', [AuthController::class, 'register'])->name('user.register');
Route::post('login', [AuthController::class,'userLogin'])->name('user.login');
Route::post('admin/login', [AuthController::class,'adminLogin'])->name('admin.login');

Route::get('/stripe-key-check', function(){
    return response()->json([
        'stripe_key' => config('services.stripe.key'),
        'stripe_secret_key' => config('services.stripe.secret'),
    ]);
});